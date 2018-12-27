<?php 
/**
 * Reusable functions for the project
 * @author RN Kushwaha <Rn.kushwaha022@gmail.com>
 * @since v1 Date: 3rd Sept, 2018
 */

 //load css, js and images with last modified time appended to the url
 //so that static resources will not be served from cache

if(!function_exists('loadStaticResource')){
  function loadStaticResource($file, $version=true){
    if(file_exists($file)){
      if($version===true ) {
          echo $file.'?v='.filemtime($file);
      } else{
          echo $file;
      }
    } else{
      echo $file;
    }
  }
}


if (!function_exists('_')) {
    function _($string = null, $print = false)
    {
        if (func_num_args()==2) {
            if ($print) {
                echo htmlspecialchars($string);
            } else {
                 return htmlspecialchars($string);
            }
        } else {
            return htmlspecialchars($string);
        }
    }
}


if (!function_exists('__')) {
    function __($string = null, $escape = true)
    {
        if ($escape === true) {
            echo htmlspecialchars($string);
        } else {
            echo $string;
        }
    }
}