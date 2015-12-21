<?php
namespace Phybrid;

use \Michelf\Markdown;

/**
 * ページクラス
 */
class PhybridPage {
  public $id = null; // url
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
    
    $page_path = ltrim(str_replace($env['PAGES_ROOT_PATH'], '', $file), '/');
    
    $this->id = str_replace('.'.$this->type, '', $page_path);
    $this->header = $file_header;
    
    if (!isset($this->header['order'])) $this->header['order'] = '';
    
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
  
  public static function fullall($app = null) {
    $env = $app->environment;
    
    $pages = array();
    
    $files = PhybridLib::get_file_list($env['PAGES_ROOT_PATH']);
    
    foreach($files as $file) {
      $page = new PhybridPage($file, $app);
      $pages[] = $page;
    }
    
    usort($pages, '\Phybrid\PhybridPage::cmp');
    
    return $pages;
  }
  
  public static function all($app = null) {
    $env = $app->environment;
    
    $pages = array();
    
    $files = PhybridLib::get_file_list($env['PAGES_ROOT_PATH']);
    
    foreach($files as $file) {
      $page = new PhybridPage($file, $app);
      $ignore = false;
      foreach($env['IGNORE_PAGE_IDS'] as $id) {
        if ($id == $page->id) {
          $ignore = true;
          break;
        }
      }
     
      if (!$ignore) $pages[] = $page;
    }
    
    usort($pages, '\Phybrid\PhybridPage::cmp');
    
    return $pages;
  }
  private static function cmp($a, $b)
  {
    $cmp = strcmp($a->header['order'], $b->header['order']);
    return $cmp;
  }
  
  public static function find($filepath, $app = null) {
    $env = $app->environment;
    
    $root_path = $env['PAGES_ROOT_PATH'];
    
    $filepath = str_replace('.html', '', $filepath);
    
    $subdirs = explode('/', $filepath);
    
    for($i = 0; $i < count($subdirs) - 1; $i++)
      $root_path .= '/'.$subdirs[$i];
    
    $filename = $subdirs[count($subdirs) - 1];
    if (substr($filepath, -1, 1) == '/') $filename .= 'index';
    
    $file = PhybridLib::find_file($root_path, $filename);
    if (!$file) return null;
    
    $page = new PhybridPage($file, $app);
    
    return $page;
  }
}
