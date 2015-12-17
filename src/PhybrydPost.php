<?php
namespace Phybryd;

use \Michelf\Markdown;

/**
 * 投稿記事クラス
 */
class PhybrydPost {
  public $id = null; // url
  public $url = array(); // y,m,d,t
  public $type = null; // md,txt,html(htm)
  public $header = null;
  public $contents = null;
  
  public function __construct($file, $app = null) {
    $env = $app->environment;
    
    $info = new \SplFileInfo($file);
    $this->type = $info->getExtension();
    
    $file_text = file_get_contents($file);
    
    $file_header = substr($file_text, 0, strpos($file_text, '}') + 1);
    $file_header = json_decode($file_header, true);
    
    $article_path = ltrim(str_replace($env['ARTICLES_ROOT_PATH'], '', $file), '/');
    
    $a = explode('/', $article_path);
    if (count($a) >= 1) $this->url['y'] = $a[0];
    if (count($a) >= 2) $this->url['m'] = $a[1];
    if (count($a) >= 3) $this->url['d'] = $a[2];
    if (count($a) >= 4) $this->url['t'] = explode('.', $a[3])[0];
    
    $this->id = str_replace('.'.$this->type, '', $article_path);
    $this->header = $file_header;
    
    $file_body = substr_replace($file_text, '', 0, strpos($file_text, '}') + 2);
    
    $this->contents = $file_body;
    if ($this->type == 'md') {
      $this->contents = str_replace('@@siteurl@@', $env['ROOT_PATH'], $this->contents);
      $this->contents = Markdown::defaultTransform($this->contents);
    } else if ($this->type == 'html') {
      $this->contents = str_replace('@@siteurl@@', $env['ROOT_PATH'], $this->contents);
    } else if ($this->type == 'txt') {
      $this->contents = '<p>'.str_replace("\n", '</p><p>', $this->contents).'</p>';
    }
  }
  
  public static function all($app = null) {
    $env = $app->environment;
    
    $posts = array();
    
    $files = PhybrydLib::get_file_list($env['ARTICLES_ROOT_PATH']);
    rsort($files);
    foreach($files as $file) {
      $posts[] = new PhybrydPost($file, $app);
    }
    
    return $posts;
  }
  
  public static function find($id, $app = null) {
    $env = $app->environment;
    
    $posts = PhybrydPost::all($app);
    foreach($posts as $post) {
      if ($post->id == $id) {
        return $post;
      }
    }
    
    return null;
  }
  
  public static function find_by_month($y, $m, $app = null) {
    $env = $app->environment;
    
    $posts = array();
    
    $files = PhybrydLib::get_file_list($env['ARTICLES_ROOT_PATH'].'/'.$y.'/'.$m);
    rsort($files);
    
    foreach($files as $file) {
      $post = new PhybrydPost($file, $app);
      if ($post->url['y'] == $y &&
          $post->url['m'] == $m) {
        $posts[] = $post;
      }
    }
    
    return $posts;
  }
  
  public static function months($app = null) {
    $env = $app->environment;
    
    $dirs = PhybrydLib::get_dir_list($env['ARTICLES_ROOT_PATH'].'/*', 0, 1);
    rsort($dirs);
    
    $archives = array();
    foreach($dirs as $dir) {
      $ym = explode('/', substr($dir, -7));
      $archives[] = array('y' => $ym[0], 'm' => $ym[1]);   
    }
    
    return $archives;
  }
}
