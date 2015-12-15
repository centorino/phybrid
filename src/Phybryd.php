<?php
namespace Phybryd;

class Phybryd {
  public static function initialize(array $settings) {
    $settings['view'] = new \Phybryd\PhybrydView();
    
    $app = new \Slim\Slim($settings);
    $app->setName('default');
    $app->router = new \Phybryd\PhybrydRouter($app->router);

    $app->get('/404.html', function () use ($app) { $app->notFound(); });
    
    // GET root
    $app->get(
      '/',
      '\Phybryd\PhybrydLib::all_filter',
      function () use ($app) {
        Phybryd::get_root($app);
      }
    );
    $app->get(
      '/index.html',
      '\Phybryd\PhybrydLib::all_filter',
      function () use ($app) {
        Phybryd::get_root($app);
      }
    );
    


    // GET /pages/:page_no/
    $app->get(
      '/pages/:page_no/',
      '\Phybryd\PhybrydLib::all_filter',
      function ($page_no) use ($app) {
        Phybryd::get_pages_with_page_no($app, $page_no);
      }
    )->conditions(array('page_no' => '[0-9]+'));
    $app->get(
      '/pages/:page_no/index.html',
      '\Phybryd\PhybrydLib::all_filter',
      function ($page_no) use ($app) {
        Phybryd::get_pages_with_page_no($app, $page_no);
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
        
        PhybrydLib::return_file_contents($app, $type, $filename, $size, $contents);
      }
    );
    
    
    
    // GET /:y/:m/:d/:t
    $app->get(
      '/:y/:m/:d/:t',
      '\Phybryd\PhybrydLib::all_filter',
      function ($y, $m, $d, $t) use ($app) {
        $env = $app->environment;
        
        $filename = str_replace('.html', '', $t);
        $id = $y.'/'.$m.'/'.$d.'/'.$filename;
        
        $post = PhybrydPost::find($id, $app);
        
        if (!$post) {
          $app->notFound();
          return;
        }
        
        $app->render('post.phtml', array('env' => $env, 'title' => $post->header->title, 'post' => $post));
      }
    )->conditions(array('y' => '[0-9]+', 'm' => '[0-9]+', 'd' => '[0-9]+'));
    
    
    
    // GET /:y/:m
    $app->get(
      '/:y/:m/',
      '\Phybryd\PhybrydLib::all_filter',
      function ($y, $m) use ($app) {
        Phybryd::get_ym($app, $y, $m);
      }
    )->conditions(array('y' => '[0-9]+', 'm' => '[0-9]+'));
    $app->get(
      '/:y/:m/index.html',
      '\Phybryd\PhybrydLib::all_filter',
      function ($y, $m) use ($app) {
        Phybryd::get_ym($app, $y, $m);
      }
    )->conditions(array('y' => '[0-9]+', 'm' => '[0-9]+'));
    
    
    
    // GET *
    $app->get(
      '/.*?',
      '\Phybryd\PhybrydLib::all_filter',
      function () use ($app) {
        Phybryd::get_page($app);
      }
    );
    
    
    
    // 404
    $app->notFound(
      function () use ($app) {
        $env = $app->environment;
        
        $app->render('404.phtml', array('env' => $env, 'title' => 'Not found', 'layout' => false));
      }
    );
    
    
    
    return $app;
  }
  static function get_root($app) {
    $env = $app->environment;
    
    $page = PhybrydPage::find($env['PATH_INFO'], $app);
    
    if ($page) {
      Phybryd::get_page($app);
    } else {
      Phybryd::get_pages_with_page_no($app);
    }
  }
  static function get_page($app) {
    $env = $app->environment;
    
    $page = PhybrydPage::find($env['PATH_INFO'], $app);
    
    if (!$page) {
      $app->notFound();
      return;
    }
    
    $app->render('page.phtml', array('env' => $env, 'title' => $page->header->title, 'page' => $page));
  }
  static function get_pages_with_page_no($app, $page_no = 1) {
    $env = $app->environment;
    
    $posts = PhybrydPost::all($app);
    
    if (!$posts) {
      $app->notFound();
      return;
    }
    
    $app->render('index.phtml', array('env' => $env, 'posts' => $posts, 'page_no' => $page_no));
  }
  static function get_ym($app, $y, $m) {
    $env = $app->environment;
    
    $posts = PhybrydPost::find_by_month($y, $m, $app);
    
    if (!$posts) {
      $app->notFound();
      return;
    }
    
    $app->render('archive.phtml', array('env' => $env, 'title' => 'Archive of '.$y.'-'.$m, 'posts' => $posts));
  }
}
