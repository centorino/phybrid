<?php
namespace Phybrid;

class PhybridView extends \Slim\LayoutView
{
  public function render($template, $data = null)
  {
    if (!file_exists($this->templatesDirectory.'/'.$template)) {
      $template = '../vendor/centorino/phybrid/src/templates/'.$template;
    }
    return parent::render($template, $data);
  }
}
