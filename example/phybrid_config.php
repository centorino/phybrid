<?php
$env = $app->environment();

// information of the site
$env['SITE_NAME'] = "Phybrid Blog";
$env['SITE_COPY'] = "A simple blog generate engine based on php and slim framework";
$env['SITE_DESCRIPTION'] = "This blog is made by Phybrid. It's a simple blog generate engine based on php and slim framework.";
$env['SITE_KEYWORDS'] = "keywords,separated,by,comma";

// number of article per page
$env['COUNT_PER_PAGE'] = 5;
// pager type
$env['PAGER_TYPE'] = 1; // 0: none, 1:pager , 2:pagination

// url
$env['RELATIVE_PATH_MODE'] = true;
$env['ROOT_PATH'] = $env['SCRIPT_NAME'];

// ignore pages for all display in pages.phtml
$env['IGNORE_PAGE_IDS'] = array(
  'index',
  'thanks'
);

// google analytics tag tracking id
$env['GOOGLE_ANALYTICS_ID'] = $app->settings['mode'] == 'production' ? 'XX-XXXXXXXX-X' : '';

// google custome search engine id
$env['GOOGLE_CUSTOME_SEARCH_ID'] = '';



// internal path
$env['CONTENTS_ROOT_PATH'] = $_SERVER['DOCUMENT_ROOT'].'/../contents';
$env['ARTICLES_ROOT_PATH'] = $env['CONTENTS_ROOT_PATH'].'/articles';
$env['MEDIAS_ROOT_PATH'] = $env['CONTENTS_ROOT_PATH'].'/medias';
$env['PAGES_ROOT_PATH'] = $env['CONTENTS_ROOT_PATH'].'/pages';
