<?php define('_ACCESS_OK', true);
      require_once 'init.php';?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="File manager, Online Editor, Image crop, Image Resize, Image filters and Online document viewer">
        <meta name="keywords" content="File manager, Online Editor, Image crop, Image Resize, Image filters and Online document viewer">
        <title>fileMagician: Adding Awesomeness to the web</title>
        <link rel='shortcut icon' type='image/x-icon' href='./favicon.ico' />
        <link type="text/css" href="<?php loadStaticResource('vendor/tui/css/tui-color-picker.css');?>" rel="stylesheet">
        <link type="text/css" href="<?php loadStaticResource('vendor/tui/css/tui-image-editor.min.css');?>" rel="stylesheet">
        <style>
            html, body {height: 100%; margin: 0;}
            .tui-image-editor-header-logo{display: none;height: 0;width: 0;opacity: 0;}
        </style>
    </head>
    <body>
        <div id="tui-image-editor-container"></div>

        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/fabric.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/tui-code-snippet.min.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/FileSaver.min.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/tui-color-picker.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/tui-image-editor.min.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/white-theme.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/tui/js/black-theme.js');?>"></script>
        <script type="text/javascript" src="<?php loadStaticResource('vendor/jquery/js/jquery-3.2.1.min.js');?>"></script>
        <script>
         var fileName = '<?php echo $_REQUEST['image']?>';
         var imageEditor = new tui.ImageEditor('#tui-image-editor-container', {
             includeUI: {
                 loadImage: {
                     path: fileName,
                     name: fileName
                 },
                 theme: blackTheme, // or whiteTheme
                 initMenu: 'filter',
                 menuBarPosition: 'left'
             },
             cssMaxWidth: 700,
             cssMaxHeight: 500
         });

         window.onresize = function() {
             imageEditor.ui.resizeEditor();
         }

         $().ready(function(e){
            $(document).find('#tui-image-editor-container .lower-canvas').attr('id','canvas');
            $(document).find('.tui-image-editor-header-buttons').append('<button type="button" id="saveImage">Save</button>');
            $(document).on('click','#saveImage', function(e){
                var canvas = document.getElementById('canvas');
                var fileExt = fileName.substr((fileName.lastIndexOf('.') + 1)).toLowerCase();
                if(fileExt=='jpg') fileExt = 'jpeg';

                $.ajax({
                  type: "POST",
                  url: "handler.php",
                  data: { 
                     name: fileName,
                     img: canvas.toDataURL("image/"+fileExt)
                     // img: imageEditor.toDataURL()
                  }
                }).done(function(result) {
                  alert(result); 
                });
            })
         })
        </script>
    </body>
</html>
