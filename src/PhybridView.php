<?php
namespace Phybrid;

class PhybridView extends \Slim\LayoutView
{
  public function render($template, $data = null)
  {
    if (!file_exists($this->templatesDirectory.'/'.$template)) {
      $template = '/../../vendor/centorino/phybrid/src/templates/'.$template;
    }
    return parent::render($template, $data);
  }
  public static function include_path($template)
  {
    $app = \Slim\Slim::getInstance();
    $templatesDirectory = $app->view->templatesDirectory;
    if (file_exists($templatesDirectory.'/'.$template)) {
      return $templatesDirectory.'/'.$template;
    } else {
      return $templatesDirectory.'/../../vendor/centorino/phybrid/src/templates/'.$template;
    }
  }
}
