<?php

$src_path="./vendor/centorino/phybryd";

echo "\n";
echo "phybryd installer start...\n";
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

if (!file_exists('phybryd_config.php')) {
  copy($src_path.'/example/phybryd_config.php', 'phybryd_config.php');
  echo "copied phybryd_config.php\n";
}

if (!file_exists('phybryd_wget.php')) {
  copy($src_path.'/utils/phybryd_wget.php', 'phybryd_wget.php');
  echo "copied phybryd_wget.php\n";
}

echo "\n";
echo "phybryd installer finished.\n";
echo "\n";
