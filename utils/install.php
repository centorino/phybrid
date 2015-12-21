<?php

$src_path="./vendor/centorino/phybrid";

echo "\n";
echo "phybrid installer start...\n";
echo "\n";

if (!file_exists('contents')) {
  system('cp -pfr '.$src_path.'/example/contents contents');
  echo "copied example contents directory.\n";
  mkdir('contents/medias');
  echo "created contents/medias directory.\n";
}

if (!file_exists('public')) {
  system('cp -pfr '.$src_path.'/example/public public');
  echo "copied example public directory.\n";
}

if (!file_exists('phybrid_config.php')) {
  copy($src_path.'/example/phybrid_config.php', 'phybrid_config.php');
  echo "copied phybrid_config.php\n";
}

if (!file_exists('phybrid_wget.php')) {
  copy($src_path.'/utils/phybrid_wget.php', 'phybrid_wget.php');
  echo "copied phybrid_wget.php\n";
}

echo "\n";
echo "phybrid installer finished.\n";
echo "\n";
