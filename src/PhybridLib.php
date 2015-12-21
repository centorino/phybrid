<?php
namespace Phybrid;

/**
 * アプリ共通処理クラス
 */
class PhybridLib {
  const FILE_TYPES = array('md', 'html', 'htm', 'txt');
  
  /**
   * ファイル一覧取得
   */
  public static function get_file_list($dir) {
    $files = glob(rtrim($dir, '/').'/*');
    $list = array();
    foreach ($files as $file) {
      $info = new \SplFileInfo($file);
      
      if (is_file($file)) {
        $type = $info->getExtension();
        if ($type != PhybridLib::FILE_TYPES[0] &&
            $type != PhybridLib::FILE_TYPES[1] &&
            $type != PhybridLib::FILE_TYPES[2] &&
            $type != PhybridLib::FILE_TYPES[3]) break;
        $list[] = $file;
      }
      if (is_dir($file)) {
        $list = array_merge($list, PhybridLib::get_file_list($file));
      }
    }
    
    return $list;
  }
  
  /**
   * ディレクトリ一覧取得
   */
  public static function get_dir_list($dir, $l = 0, $e = -1) {
    $files = glob(rtrim($dir, '/').'/*');
    $list = array();
    foreach ($files as $file) {
      if (is_dir($file)) {
        if ($e < 0 || ($e > 0 && $l < $e)) {
          $list[] = $file;
          $list = array_merge($list, PhybridLib::get_dir_list($file, $l + 1, $e));
        }
      }
    }
    
    return $list;
  }
  
  /**
   * ファイル検索
   */
  public static function find_file($dir, $filename) {
    foreach(PhybridLib::FILE_TYPES as $type) {
      $files = glob(rtrim($dir, '/').'/'.$filename.'.'.$type);
      foreach($files as $file) {
        if (is_file($file)) {
          return $file;
        }
      }
    }
    return null;
  }
  
  /**
   * ファイルコンテンツ返却
   */
  public static function return_file_contents($app, $type, $name, $size, $file_contents) {
    if (!empty($type)) $app->response->headers->set('Content-Type', $type);
    $app->response->headers->set('Pragma', 'public');
    $app->response->headers->set('Content-disposition:', 'attachment; filename=' . $name);
    $app->response->headers->set('Content-Transfer-Encoding', 'binary');
    $app->response->headers->set('Content-Length', $size);
    
    echo $file_contents;
  }

  /**
   * フィルタ処理
   */
  public static function all_filter(\Slim\Route $route) {
    PhybridLib::read_common_data($route);
    PhybridLib::get_breadcrumb($route);
    PhybridLib::get_relative_path($route);
  }
  public static function read_common_data(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
    $_SESSION['archives'] = PhybridPost::months($app);
    $_SESSION['pages'] = PhybridPage::all($app);
  }
  public static function get_breadcrumb(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
    $env = $app->environment; 
    
    $pattern = $app->router()->getCurrentRoute()->getPattern();
    
    if ($pattern == '/medias/:filename') return;
    
    $breadcrumbs = array();
    $breadcrumbs[] = array('id' => 1, 'text' => 'Home', 'url' => '/index.html');
    
    $page = PhybridPage::find($env['PATH_INFO'], $app);
    
    if ($page && $page->id != 'index') {
      $path_array = explode('/', $page->id);
      $findpath = '';
      $idx = 2;
      foreach($path_array as $path) {
        $findpath .= (!empty($findpath) ? '/' : '').$path;
        $tmp_page = PhybridPage::find($findpath, $app);
        
        if (!$tmp_page && $findpath.'/index' != $page->id)
          $tmp_page = PhybridPage::find($findpath.'/index', $app);
        
        if (!$tmp_page) continue;
        
        $breadcrumbs[] = array('id' => $idx, 'text' => $tmp_page->header['title'], 'url' => '/'.$tmp_page->id.'.html');
        $idx++;
      }
    } else {
      $params = explode('/', str_replace('/index.html', '', $env['PATH_INFO']));
      
      if ($pattern == '/:y/:m/:d/:t') {
        $id = str_replace('.html', '', $env['PATH_INFO']);
        $id = substr($id, 1, strlen($id) - 1);
        $post = PhybridPost::find($id, $app);
        
        $breadcrumbs[] = array('id' => 2, 'text' => $params[1].'-'.$params[2], 'url' => '/'.$params[1].'/'.$params[2].'/index.html');
        
        if ($post) {
          $breadcrumbs[] = array('id' => 3, 'text' => $post->header['title'], 'url' => '/'.$params[1].'/'.$params[2].'/'.$params[3].'/'.$params[4]);
        } else {
          $breadcrumbs[] = array('id' => 3, 'text' => $params[4], 'url' => '/'.$params[1].'/'.$params[2].'/'.$params[3].'/'.$params[4]);
        }
      } else if ($pattern == '/:y/:m/' || $pattern == '/:y/:m/index.html') {
        $breadcrumbs[] = array('id' => 2, 'text' => $params[1].'-'.$params[2], 'url' => '/'.$params[1].'/'.$params[2].'/index.html');
      } else if ($pattern == '/pages/:page_no/' || $pattern == '/pages/:page_no/index.html') {
        $breadcrumbs[] = array('id' => 2, 'text' => 'Page '.$params[2], 'url' => '/'.$params[1].'/'.$params[2].'/index.html');
      }
    }
    
    if (count($breadcrumbs) == 1)
      $breadcrumbs[0]['text'] = 'Home of '.$env['SITE_NAME'];
    
    $_SESSION['breadcrumbs'] = $breadcrumbs;
  }
  public static function get_relative_path(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
    $env = $app->environment;
    
    if (!$env['RELATIVE_PATH_MODE']) return;
    
    $params = explode('/', $env['PATH_INFO']);
    $param_count = count($params) - 1;
    
    $path = '';
    for($i = 0; $i < $param_count - 1; $i++)
      $path .= '../';
    
    if (empty($path)) $path = './';
    
    $env['ROOT_PATH'] = rtrim($path, '/');
  }
}
