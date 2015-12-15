<?php
$_SERVER['DOCUMENT_ROOT'] = __DIR__.'/public';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SERVER_NAME'] = 'localhost';

if ($argv != null) {
  foreach($argv as $arg) {
    $kv = explode('=', $arg);
    if ($kv[0] != 'SCRIPT_NAME') continue;
    
    $_SERVER[$kv[0]] = $kv[1];
  }
  
  if ($_SERVER['SCRIPT_NAME'] == $_SERVER['SCRIPT_FILENAME']) {
    if (false) {
      echo "\n";
      echo "parameter error has occured...\n";
      echo "\n";
      echo "usage:\n";
      echo "  If you have a blog is http://example.com/\n";
      echo "    $ php phybryd_wget.php SCRIPT_NAME=/\n";
      echo "\n";
      echo "  And, if you have http://example.com/myblog\n";
      echo "    $ php phybryd_wget.php SCRIPT_NAME=/myblog\n";
      echo "\n";
      exit;
    } else {
      $_SERVER['SCRIPT_NAME'] = '/';
    }
  }
}

$debug = false;

$script_name = $_SERVER['SCRIPT_NAME'];
$count_per_page = null;
$pub_path = $_SERVER['DOCUMENT_ROOT'];
$out_path = $_SERVER['DOCUMENT_ROOT'].'/../your_web_site';
$contents_path = $_SERVER['DOCUMENT_ROOT'].'/../contents';

/**
 * create a output root directory
 */
if (!file_exists($out_path)) {
  mkdir($out_path);
  if ($debug) echo 'created directory '.$out_path."\n";
}

/**
 * download root index.html
 */
$req_uri = '/index.html';
$_SERVER['REQUEST_URI'] = $script_name.$req_uri;
ob_start();
require $pub_path.'/index.php';
$contents = ob_get_contents();
ob_end_clean();
file_put_contents($out_path.$req_uri, $contents);

$count_per_page = $app->environment['COUNT_PER_PAGE'];

/**
 * download monthly archived pages and individual article pages
 */
$posts = Phybryd\PhybrydPost::all($app);
foreach($posts as $post) {
  $ymd = array($post->url['y'], $post->url['m'], $post->url['d']);
  if (!file_exists($out_path.'/'.implode('/', $ymd))) {
    mkdir($out_path.'/'.implode('/', $ymd), 0777, true);
    if ($debug) echo 'creaated directory '.$out_path.'/'.implode('/', $ymd)."\n";
  }
  
  $ym = array($post->url['y'], $post->url['m']);
  if (!file_exists($out_path.'/'.implode('/', $ym).'/index.html')) {
    //
    // download monthly archived pages
    //
    $req_uri = '/'.implode('/', $ym).'/index.html';
    $_SERVER['REQUEST_URI'] = $script_name.$req_uri;
    $app->environment = \Slim\Environment::getInstance(true);
    ob_start();
    require $pub_path.'/index.php';
    $contents = ob_get_contents();
    ob_end_clean();
    file_put_contents($out_path.$req_uri, $contents);
    if ($debug) echo 'wrote '.$out_path.$req_uri."\n";
  }
  
  //
  // download individual article pages
  //
  $req_uri = '/'.$post->id.'.html';
  $_SERVER['REQUEST_URI'] = $script_name.$req_uri;
  $app->environment = \Slim\Environment::getInstance(true);
  ob_start();
  require $pub_path.'/index.php';
  $contents = ob_get_contents();
  ob_end_clean();
  file_put_contents($out_path.$req_uri, $contents);
  if ($debug) echo 'wrote '.$out_path.$req_uri."\n";
}

/**
 * download individual free pages
 */
$pages = Phybryd\PhybrydPage::fullall($app);
foreach($pages as $page) {
  $fullpath_arr = explode('/', $page->id);
  $path_arr = array();
  for($i = 0; $i < count($fullpath_arr) - 1; $i++)
    $path_arr[] = $fullpath_arr[$i];
  if (!file_exists($out_path.'/'.implode('/', $path_arr))) {
    mkdir($out_path.'/'.implode('/', $path_arr), 0777, true);
    if ($debug) echo 'creaated directory '.$out_path.'/'.implode('/', $path_arr)."\n";
  }
  
  $req_uri = '/'.$page->id.'.html';
  $_SERVER['REQUEST_URI'] = $script_name.$req_uri;
  $app->environment = \Slim\Environment::getInstance(true);
  ob_start();
  require $pub_path.'/index.php';
  $contents = ob_get_contents();
  ob_end_clean();
  file_put_contents($out_path.$req_uri, $contents);
  if ($debug) echo 'wrote '.$out_path.$req_uri."\n";
}

/**
 * download paginated pages
 */
$page_cnt = count($posts) / $count_per_page;
$remain = count($posts) % $count_per_page;
if ($remain > 0) $page_cnt++;

for($i = 1; $i <= $page_cnt; $i++) {
  if (!file_exists($out_path.'/pages/'.$i)) {
    mkdir($out_path.'/pages/'.$i, 0777, true);
    if ($debug) echo 'created directory '.$out_path.'/pages/'.$i."\n";
  }
  
  $req_uri = '/pages/'.$i.'/index.html';
  $_SERVER['REQUEST_URI'] = $script_name.$req_uri;
  $app->environment = \Slim\Environment::getInstance(true);
  ob_start();
  require $pub_path.'/index.php';
  $contents = ob_get_contents();
  ob_end_clean();
  file_put_contents($out_path.$req_uri, $contents);
  if ($debug) echo 'wrote '.$out_path.$req_uri."\n";
}

/**
 * copy php file
 */
$files = glob($pub_path.'/*');
foreach($files as $file) {
  if (!is_file($file)) continue;
  
  $info = new \SplFileInfo($file);
  if ($info->getExtension() != 'php') continue;
  
  if ($info->getFileName() == 'index.php' || $info->getFileName() == 'phybryd_generator.php') continue;
  
  copy($file, $out_path.'/'.$info->getFileName());
  if ($debug) echo 'copied '.$file."\n";
}

/**
 * copy assets files and image files
 */
if (file_exists($pub_path.'/assets')) {
  system('cp -pfr '.$pub_path.'/assets '.$out_path.'/.');
  if ($debug) echo "copied assets directory\n";
}
if (file_exists($contents_path.'/medias')) {
  system('cp -pfr '.$contents_path.'/medias '.$out_path.'/.');
  if ($debug) echo "copied medias directory\n";
}

/**
 * archive output directory as public/site.zip
 */
system('cd '.$out_path.'; zip -q -r '.$pub_path.'/site.zip ./*');
if ($debug) echo "zipped your site as public/site.zip\n";

system("rm -fr ".$out_path);
