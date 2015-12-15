<?php
namespace Phybryd;

class PhybrydView extends \Slim\LayoutView
{
  public function render($template, $data = null)
  {
    if (!file_exists($this->templatesDirectory.'/'.$template)) {
      $template = '../vendor/centorino/phybryd/src/templates/'.$template;
    }
    return parent::render($template, $data);
  }
}
