<?php
namespace Phybrid;

class Phybrid {
  public static function initialize(array $settings) {
    $settings['view'] = new \Phybrid\PhybridView();
    
    $app = new \Slim\Slim($settings);
    $app->setName('default');
    $app->router = new \Phybrid\PhybridRouter($app->router);

    $app->get('/404.html', function () use ($app) { $app->notFound(); });
    
    // GET root
    $app->get(
      '/',
      '\Phybrid\PhybridLib::all_filter',
      function () use ($app) {
        Phybrid::get_root($app);
      }
    );
    $app->get(
      '/index.html',
      '\Phybrid\PhybridLib::all_filter',
      function () use ($app) {
        Phybrid::get_root($app);
      }
    );
    


    // GET /pages/:page_no/
    $app->get(
      '/pages/:page_no/',
      '\Phybrid\PhybridLib::all_filter',
      function ($page_no) use ($app) {
        Phybrid::get_pages_with_page_no($app, $page_no);
      }
    )->conditions(array('page_no' => '[0-9]+'));
    $app->get(
      '/pages/:page_no/index.html',
      '\Phybrid\PhybridLib::all_filter',
      function ($page_no) use ($app) {
        Phybrid::get_pages_with_page_no($app, $page_no);
      }
    )->conditions(array('page_no' => '[0-9]+'));
    
    
    
    // GET /medias/:filename
    $app->get(
      '/medias/:filename',
      function ($filename) use ($app) {
        $env = $app->environment;
        
        $type = mime_content_type($env['MEDIAS_ROOT_PATH'].'/'.$filename);
        $size = filesize($env['MEDIAS_ROOT_PATH'].'/'.$filename);
        $contents = readfile($env['MEDIAS_ROOT_PATH'].'/'.$filename);
        
        PhybridLib::return_file_contents($app, $type, $filename, $size, $contents);
      }
    );
    
    
    
    // GET /:y/:m/:d/:t
    $app->get(
      '/:y/:m/:d/:t',
      '\Phybrid\PhybridLib::all_filter',
      function ($y, $m, $d, $t) use ($app) {
        $env = $app->environment;
        
        $filename = str_replace('.html', '', $t);
        $id = $y.'/'.$m.'/'.$d.'/'.$filename;
        
        $post = PhybridPost::find($id, $app);
        
        if (!$post) {
          $app->notFound();
          return;
        }
        
        $app->render('post.phtml', array('env' => $env, 'header' => $post->header, 'post' => $post));
      }
    )->conditions(array('y' => '[0-9]+', 'm' => '[0-9]+', 'd' => '[0-9]+'));
    
    
    
    // GET /:y/:m
    $app->get(
      '/:y/:m/',
      '\Phybrid\PhybridLib::all_filter',
      function ($y, $m) use ($app) {
        Phybrid::get_ym($app, $y, $m);
      }
    )->conditions(array('y' => '[0-9]+', 'm' => '[0-9]+'));
    $app->get(
      '/:y/:m/index.html',
      '\Phybrid\PhybridLib::all_filter',
      function ($y, $m) use ($app) {
        Phybrid::get_ym($app, $y, $m);
      }
    )->conditions(array('y' => '[0-9]+', 'm' => '[0-9]+'));
    
    
    
    // GET *
    $app->get(
      '/.*?',
      '\Phybrid\PhybridLib::all_filter',
      function () use ($app) {
        Phybrid::get_page($app);
      }
    );
    
    
    
    // 404
    $app->notFound(
      function () use ($app) {
        $env = $app->environment;
        
        $app->render('404.phtml', array('env' => $env, 'header' => Phybrid::init_header('Not found'), 'layout' => false));
      }
    );
    
    
    
    return $app;
  }
  static function get_root($app) {
    $env = $app->environment;
    
    $page = PhybridPage::find($env['PATH_INFO'], $app);
    
    if ($page) {
      Phybrid::get_page($app);
    } else {
      Phybrid::get_pages_with_page_no($app);
    }
  }
  static function get_page($app) {
    $env = $app->environment;
    
    $page = PhybridPage::find($env['PATH_INFO'], $app);
    
    if (!$page) {
      $app->notFound();
      return;
    }
    
    $app->render('page.phtml', array('env' => $env, 'header' => $page->header, 'page' => $page));
  }
  static function get_pages_with_page_no($app, $page_no = 1) {
    $env = $app->environment;
    
    $posts = PhybridPost::all($app);
    
    if (!$posts) {
      $app->notFound();
      return;
    }
    
    $app->render('index.phtml', array('env' => $env, 'posts' => $posts, 'page_no' => $page_no));
  }
  static function get_ym($app, $y, $m) {
    $env = $app->environment;
    
    $posts = PhybridPost::find_by_month($y, $m, $app);
    
    if (!$posts) {
      $app->notFound();
      return;
    }
    
    $app->render('archive.phtml', array('env' => $env, 'header' => Phybrid::init_header('Archive of '.$y.'-'.$m), 'posts' => $posts));
  }
  static function init_header($title = null, $description = null, $keywords = null) {
    return array(
      'title' => $title,
      'description' => $description,
      'keywords' => $keywords
    );
  }
}
