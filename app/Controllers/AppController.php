<?php

namespace App\Controllers;

class AppController {

    public $theme  = 'default';
    public $layout = 'default';

    public function __construct(){
    }

    public function handleError( $errorType, $msg ) {
        ob_start();
        if( ENVIRONMENT == 'development' ){
            $bodyContent = $msg;
        }

        $loadTheme  = empty($this->theme) ? 'default' : $this->theme;
        include ( THEME_PATH.$loadTheme.'/404.php');
        $themeNlayOut = ob_get_clean();
        echo $themeNlayOut;
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        exit;
    }

    public function _view( $view, array $vars = array() ){
        $loader = new \Twig_Loader_Filesystem(APP_DIR.DS.'Views');
        $twig   = new \Twig_Environment($loader, array(
            'cache' => false
        ));
        //$template_dir.'/compiled',
    	echo $twig->render($view, $vars);
    }
}
