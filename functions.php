<?php 
/**
 * Reusable functions for the project
 * @author RN Kushwaha <Rn.kushwaha022@gmail.com>
 * @since v1 Date: 3rd Sept, 2018
 */

if( !defined('_ACCESS_OK') ){
  header("HTTP/1.0 404 Not Found");
  die('Page not found!');
}

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
