<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Image Editor</title>
        <link type="text/css" href="vendors/tui/css/tui-color-picker.css" rel="stylesheet">
        <link type="text/css" href="vendors/tui/css/tui-image-editor.min.css" rel="stylesheet">
        <style>
            @import url(//fonts.googleapis.com/css?family=Noto+Sans);
            html, body {height: 100%; margin: 0;}
            .tui-image-editor-header-logo{display: none;height: 0;width: 0;opacity: 0;}
        </style>
    </head>
    <body>

        <div id="tui-image-editor-container"></div>

        <script type="text/javascript" src="vendors/tui/js/fabric.js"></script>
        <script type="text/javascript" src="vendors/tui/js/tui-code-snippet.min.js"></script>
        <script type="text/javascript" src="vendors/tui/js/FileSaver.min.js"></script>
        <script type="text/javascript" src="vendors/tui/js/tui-color-picker.js"></script>
        <script type="text/javascript" src="vendors/tui/js/tui-image-editor.min.js"></script>
        <script type="text/javascript" src="vendors/tui/js/white-theme.js"></script>
        <script type="text/javascript" src="vendors/tui/js/black-theme.js"></script>
        <script type="text/javascript" src="vendors/jquery/jquery-3.2.1.min.js"></script>
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
