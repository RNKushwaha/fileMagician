<?php

namespace App\Controllers;

class PagesController extends AppController {

    public function index(){
        //get cookie to make form selected
        $settings['theme']          = 'eclipse';
        $settings['font_size']      = '14px';
        $settings['wrap_text']      = false;
        $settings['soft_tab']       = true;
        $settings['soft_tab_size']  = 4;
        $settings['show_invisible'] = false;
        $settings['show_gutter']    = true;
        $settings['show_indent']    = true;

        if(isset($_COOKIE['theme'])) $settings['theme'] = $_COOKIE['theme'];
        if(isset($_COOKIE['font_size'])) $settings['font_size'] = $_COOKIE['font_size'];
        if(isset($_COOKIE['wrap_text'])) $settings['wrap_text'] = (boolean)$_COOKIE['wrap_text'];
        if(isset($_COOKIE['soft_tab'])) $settings['soft_tab'] = (boolean)$_COOKIE['soft_tab'];
        if(isset($_COOKIE['soft_tab_size'])) $settings['soft_tab_size'] = $_COOKIE['soft_tab_size'];
        if(isset($_COOKIE['show_invisible'])) $settings['show_invisible'] = (boolean)$_COOKIE['show_invisible'];
        if(isset($_COOKIE['show_gutter'])) $settings['show_gutter'] = (boolean)$_COOKIE['show_gutter'];
        if(isset($_COOKIE['show_indent'])) $settings['show_indent'] = (boolean)$_COOKIE['show_indent'];

        return $this->_view('home.html', [
            'baseurl'   => SITE_URL,
            'settings'  => $settings,
            'font_size' => ['10px','11px','12px','13px','14px','15px','16px','17px','18px','20px','22px','25px','30px','35px','40px'],
            'pageTitle' => 'Home Page'
        ]);
    }

}