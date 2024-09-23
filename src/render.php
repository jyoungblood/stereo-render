<?php

/**
 * @package    STEREO Render
 * @version    1.0.1
 * @author     Jonathan Youngblood <jonathan.youngblood@gmail.com>
 * @license    https://github.com/jyoungblood/stereo-render/blob/master/LICENSE.md (MIT License)
 * @source     https://github.com/jyoungblood/stereo-render
 */

namespace Stereo;

use eftec\bladeone\BladeOne;
use eftec\bladeonehtml\BladeOneHtml;

class BladeHTML extends BladeOne {
  use BladeOneHtml;
}

class render {

	// render data as json string
  public static function json($req, $res, $data = [], $status = 200){
    $res->getBody()->write(json_encode($data));
    return $res->withHeader('content-type', 'application/json')->withStatus($status);
  }

  // return a url redirect
  public static function redirect($req, $res, $location, $status = 302){
    return $res->withHeader('Location', $location)->withStatus($status);
  }

  // return an HTML string or file
  public static function html($req, $res, $html, $status = 200){
    $body = $res->getBody();
    if (substr($html, -5) == '.html' && file_exists('./'.$html)){
      $html = file_get_contents('./'.$html);
    }
    $body->write($html);
    return $res->withStatus($status);
  }

  // return a plain text string
  public static function text($req, $res, $text, $status = 200){
    $body = $res->getBody();
    $body->write($text);
    return $res->withHeader('Content-Type', 'text/plain')->withStatus($status);
  }

  // return a rendered Blade template
  public static function blade_template($template, $data = []){
    $cache_modes = [
      'slow' => 1,
      'fast' => 2,
      'debug' => 5
    ];
    $cache_mode = isset($_ENV['BLADE_MODE']) ? ($cache_modes[strtolower($_ENV['BLADE_MODE'])] ?? 0) : 0;
    $blade = new BladeHTML(
      isset($_ENV['BLADE_VIEWS_PATH']) ? $_ENV['BLADE_VIEWS_PATH'] : 'views',
      isset($_ENV['BLADE_CACHE_PATH']) ? $_ENV['BLADE_CACHE_PATH'] : 'cache',
      $cache_mode
    );
    $blade->pipeEnable=true;
    return $blade->run($template, $data);
  }

  // return a rendered Blade template
  public static function blade($req, $res, $args, $status = 200){
    $body = $res->getBody();
    $body->write(
      render::blade_template(
        $args['template'],
        $args['data'] ?? []
      )
    );
    return $res->withStatus($status);
  }
  
}

?>