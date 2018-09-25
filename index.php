<?php define('_ACCESS_OK', true);
require_once 'init.php';
//get cookie to make form selected
if(isset($_COOKIE['theme'])) $theme = $_COOKIE['theme'];
else $theme = 'eclipse';

if(isset($_COOKIE['font_size'])) $font_size = $_COOKIE['font_size'];
else $font_size = '14px';

if(isset($_COOKIE['wrap_text'])) $wrap_text = (boolean)$_COOKIE['wrap_text'];
else $wrap_text = false;

if(isset($_COOKIE['soft_tab'])) $soft_tab = (boolean)$_COOKIE['soft_tab'];
else $soft_tab = true;

if(isset($_COOKIE['soft_tab_size'])) $soft_tab_size = $_COOKIE['soft_tab_size'];
else $soft_tab_size = 4;

if(isset($_COOKIE['show_invisible'])) $show_invisible = (boolean)$_COOKIE['show_invisible'];
else $show_invisible = false;

if(isset($_COOKIE['show_gutter'])) $show_gutter = (boolean)$_COOKIE['show_gutter'];
else $show_gutter = true;

if(isset($_COOKIE['show_indent'])) $show_indent = (boolean)$_COOKIE['show_indent'];
else $show_indent = true;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="File manager, Online Editor, Image crop, Resize, filter and document viewer">
        <meta name="author" content="RN Kushwaha">
        <title>fileMagician: Adding Awesomeness to the web</title>
        <link rel="stylesheet" href="<?php loadStaticResource('vendors/bootstrap/css/bootstrap.min.css');?>">
        <link rel="stylesheet" type="text/css" href="<?php loadStaticResource('vendors/cruzersoftwares/css/style.css');?>" />
        <script defer src="//use.fontawesome.com/releases/v5.3.1/js/all.js" integrity="sha384-kW+oWsYx3YpxvjtZjFXqazFpA7UP/MbiY4jvs+RWZo2+N94PFZ36T6TFkc9O3qoB" crossorigin="anonymous"></script>
    </head>
    
    <body>
    <div id="msg"></div>
    <div class="container-fluid">
        <nav class="navbar navbar-dark fixed-top bg-primary flex-md-nowrap p-0 shadow col-sm-3 col-md-2 mr-0">
            <a class="navbar-brand1" target="_blank" href="https://cruzersoftwares.github.io/fileMagician/"> fileMagician</a>
            <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search" style="display:none">
            <a id="infoHandler" class="icon_custom"><i class="fas fa-info-circle fa-lg"></i></a>
            <a id="settingsHandler" class="icon_custom"><i class="fas fa-cog fa-lg"></i></a>
            <a id="refreshHandler" class="icon_custom"><i class="fas fa-sync-alt fa-lg"></i></a>
            <a href="logout.php" id="refreshHandler" class="icon_custom"><i class="fas fa-sign-out-alt fa-lg"></i></a>
            <a>&nbsp;</a>
        </nav>

       <div class="row">
          <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <div class="" id="fileMleftsidebar">
                    <div id="fileTree"></div>
                </div>
            </div>
          </nav>

          <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="padding-top:5px;padding-left:0px;">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" id="data">
                    <div id="metadata">
                        <!-- <label for="path">Path: <span></span></label> -->
                        <label for="size">Size: <span></span></label>
                        <label for="dimension">Dimension: <span></span></label>
                        <label for="contains">Contains: <span></span></label>
                        <label for="created">Created: <span></span></label>
                        <label for="lastmodified">Modified: <span></span></label>
                        <label for="line">Line: <span></span></label>
                        <label for="permission">Permission: <span></span>
                            <input type="text" class="input input-sm " value="" required="required" id="updatePermission">
                            <a href="" id="updatePermissionBtn"><i class="fas fa-check-circle fa-lg"></i></a>
                        </label>
                        <a href ="imageEditor.php?image=" target="_blank" class="btn btn-primary btn-sm" id="editImage">Edit Image</a>
                    </div>
                </div>
                <div id="tabs">
                   <ul class="nav nav-tabs" id="filesTab" role="tablist">
                        <li class="nav-item" id="newTab" style="margin-left:10px">
                            <a class="nav-link" id="new-tab" data-toggle="tab" role="tab" aria-controls="contact" aria-selected="false">+</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        
                    </div>
                </div>
                <div id="info" style="display: none;">
                    <table class="table table-bordered" border="1" cellpadding="10" cellspacing="1">
                    <?php echo '<tr><th>Browser </th><td>'.$_SERVER['HTTP_USER_AGENT']."</td></tr>";
                          echo '<tr><th>Root </th><td>'.$_SERVER['DOCUMENT_ROOT']."</td></tr>";
                          echo '<tr><th>Server Name </th><td>'.$_SERVER['SERVER_NAME']."</td></tr>";
                          echo '<tr><th>Server OS </th><td>'.$_SERVER['SERVER_SOFTWARE']."</td></tr>";
                          echo '<tr><th>Server IP </th><td>'.$_SERVER['SERVER_ADDR']."</td></tr>";
                          echo '<tr><th>Your IP </th><td>'.$_SERVER['REMOTE_ADDR']."</td></tr>";
                          echo '<tr><th>PHP Version </th><td>'.phpversion()."</td></tr>";
                          echo '<tr><th>Server Timezone </th><td>' . date_default_timezone_get().'</td></tr>';
                          echo '<tr><th>Server Time </th><td>' .strftime("%A, %B %d, %Y, %X %Z").'</td></tr>';
                    ?>
                    </table>
                    <a class="btn btn-danger btncloseDiv">Close</a>        
                </div>
                <div id="settings" style="display: none;"> 
                    <table class="table table-bordered" border="1" cellpadding="10" cellspacing="1">
                        <tr>
                            <th>Theme</th>
                            <td>
                                <select name="theme" id="theme">
                                    <option value="ambiance" <?php if($theme=='ambiance') echo 'selected';?>>ambiance</option>
                                    <option value="chaos" <?php if($theme=='chaos') echo 'selected';?>>chaos</option>
                                    <option value="chrome" <?php if($theme=='chrome') echo 'selected';?>>chrome</option>
                                    <option value="clouds_midnight" <?php if($theme=='clouds_midnight') echo 'selected';?>>clouds_midnight</option>
                                    <option value="clouds" <?php if($theme=='clouds') echo 'selected';?>>clouds</option>
                                    <option value="cobalt" <?php if($theme=='cobalt') echo 'selected';?>>cobalt</option>
                                    <option value="crimson_editor" <?php if($theme=='crimson_editor') echo 'selected';?>>crimson_editor</option>
                                    <option value="dawn" <?php if($theme=='dawn') echo 'selected';?>>dawn</option>
                                    <option value="dracula" <?php if($theme=='dracula') echo 'selected';?>>dracula</option>
                                    <option value="dreamweaver" <?php if($theme=='dreamweaver') echo 'selected';?>>dreamweaver</option>
                                    <option value="eclipse" <?php if($theme=='eclipse') echo 'selected';?>>eclipse</option>
                                    <option value="github" <?php if($theme=='github') echo 'selected';?>>github</option>
                                    <option value="gob" <?php if($theme=='gob') echo 'selected';?>>gob</option>
                                    <option value="gruvbox" <?php if($theme=='gruvbox') echo 'selected';?>>gruvbox</option>
                                    <option value="idle_fingers" <?php if($theme=='idle_fingers') echo 'selected';?>>idle_fingers</option>
                                    <option value="iplastic" <?php if($theme=='iplastic') echo 'selected';?>>iplastic</option>
                                    <option value="katzenmilch" <?php if($theme=='katzenmilch') echo 'selected';?>>katzenmilch</option>
                                    <option value="kr_theme" <?php if($theme=='kr_theme') echo 'selected';?>>kr_theme</option>
                                    <option value="kuroir" <?php if($theme=='kuroir') echo 'selected';?>>kuroir</option>
                                    <option value="merbivore_soft" <?php if($theme=='merbivore_soft') echo 'selected';?>>merbivore_soft</option>
                                    <option value="merbivore" <?php if($theme=='merbivore') echo 'selected';?>>merbivore</option>
                                    <option value="mono_industrial" <?php if($theme=='mono_industrial') echo 'selected';?>>mono_industrial</option>
                                    <option value="monokai" <?php if($theme=='monokai') echo 'selected';?>>monokai</option>
                                    <option value="pastel_on_dark" <?php if($theme=='pastel_on_dark') echo 'selected';?>>pastel_on_dark</option>
                                    <option value="solarized_dark" <?php if($theme=='solarized_dark') echo 'selected';?>>solarized_dark</option>
                                    <option value="solarized_light" <?php if($theme=='solarized_light') echo 'selected';?>>solarized_light</option>
                                    <option value="sqlserver" <?php if($theme=='sqlserver') echo 'selected';?>>sqlserver</option>
                                    <option value="terminal" <?php if($theme=='terminal') echo 'selected';?>>terminal</option>
                                    <option value="textmate" <?php if($theme=='textmate') echo 'selected';?>>textmate</option>
                                    <option value="tomorrow_night_blue" <?php if($theme=='tomorrow_night_blue') echo 'selected';?>>tomorrow_night_blue</option>
                                    <option value="tomorrow_night_bright" <?php if($theme=='tomorrow_night_bright') echo 'selected';?>>tomorrow_night_bright</option>
                                    <option value="tomorrow_night_eighties" <?php if($theme=='tomorrow_night_eighties') echo 'selected';?>>tomorrow_night_eighties</option>
                                    <option value="tomorrow_night" <?php if($theme=='tomorrow_night') echo 'selected';?>>tomorrow_night</option>
                                    <option value="tomorrow" <?php if($theme=='tomorrow') echo 'selected';?>>tomorrow</option>
                                    <option value="twilight" <?php if($theme=='twilight') echo 'selected';?>>twilight</option>
                                    <option value="vibrant_ink" <?php if($theme=='vibrant_ink') echo 'selected';?>>vibrant_ink</option>
                                    <option value="xcode" <?php if($theme=='xcode') echo 'selected';?>>xcode</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Font Size</th>
                            <td>
                                <select name="font_size" id="font_size">
                                    <?php foreach(['10px','11px','12px','13px','14px','15px','16px','17px','18px','20px','22px','25px','30px','35px','40px'] as $font){?>
                                    <option value="<?php echo $font;?>" <?php if($font_size==$font) echo 'selected';?>><?php echo $font;?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Wrap Text</th>
                            <td>
                                <select name="wrap_text" id="wrap_text">
                                    <option value="true" <?php if($wrap_text==true) echo 'selected';?>>Yes</option>
                                    <option value="false" <?php if($wrap_text==false) echo 'selected';?>>No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Soft Tab</th>
                            <td>
                                <input type="number" name="soft_tab_size" id="soft_tab_size" value="<?php echo $soft_tab_size;?>" style="width: 50px">
                                <select name="soft_tab" id="soft_tab">
                                    <option value="true" <?php if($soft_tab==true) echo 'selected';?>>Yes</option>
                                    <option value="false" <?php if($soft_tab==false) echo 'selected';?>>No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Show Invisible</th>
                            <td>
                                <select name="show_invisible" id="show_invisible">
                                    <option value="true" <?php if($show_invisible==true) echo 'selected';?>>Yes</option>
                                    <option value="false" <?php if($show_invisible==false) echo 'selected';?>>No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Show Line Number</th>
                            <td>
                                <select name="show_gutter" id="show_gutter">
                                    <option value="true" <?php if($show_gutter==true) echo 'selected';?>>Yes</option>
                                    <option value="false" <?php if($show_gutter==false) echo 'selected';?>>No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Show Indent Guides</th>
                            <td>
                                <select name="show_indent" id="show_indent">
                                    <option value="true" <?php if($show_indent==true) echo 'selected';?>>Yes</option>
                                    <option value="false" <?php if($show_indent==false) echo 'selected';?>>No</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <a class="btn btn-danger btncloseDiv">Close</a>
                </div>
                <div id="uploader" style="display: none;">
                    <form id="fileupload" action="./uploadHandler.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="directory" value="" id="directory">
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-7">
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>Add files...</span>
                                    <input type="file" name="files[]" multiple>
                                </span>
                                
                                <button type="button" class="btn btn-danger delete">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Delete</span>
                                </button>
                                <input type="checkbox" class="toggle">
                                <span class="fileupload-process"></span>
                            </div>
                            <div class="col-lg-5 fileupload-progress fade">
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                </div>
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <table role="presentation" class="table table-striped"><tbody class="files" id="fileLists"></tbody></table>
                    </form>

                    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                        <div class="slides"></div>
                        <h3 class="title"></h3>
                        <a class="prev">‹</a>
                        <a class="next">›</a>
                        <a class="close">×</a>
                        <a class="play-pause"></a>
                        <ol class="indicator"></ol>
                    </div>

                    <script id="template-upload" type="text/x-tmpl">
                    {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-upload">
                            <td>
                                <span class="preview"></span>
                            </td>
                            <td>
                                <p class="name">{%=file.name%}</p>
                                <strong class="error text-danger"></strong>
                            </td>
                            <td>
                                <p class="size">Processing...</p>
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                            </td>
                            <td>
                                {% if (!i && !o.options.autoUpload) { %}
-                                    <button class="btn btn-primary start" disabled>
-                                        <i class="glyphicon glyphicon-upload"></i>
-                                        <span>Start</span>
-                                    </button>
-                                {% } %}
                                {% if (!i) { %}
                                    <button class="btn btn-warning cancel">
                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                        <span>Cancel</span>
                                    </button>
                                {% } %}
                            </td>
                        </tr>
                    {% } %}
                    </script>

                    <script id="template-download" type="text/x-tmpl">
                    {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-download">
                            <td>
                                <span class="preview">
                                    {% if (file.thumbnailUrl) { %}
                                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                                    {% } %}
                                </span>
                            </td>
                            <td>
                                <p class="name">
                                    {% if (file.url) { %}
                                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                    {% } else { %}
                                        <span>{%=file.name%}</span>
                                    {% } %}
                                </p>
                            </td>
                            <td>
                                <span class="size">{%=formatSizeUnits(file.size)%}</span>
                            </td>
                            <td>
                                {% if (file.deleteUrl) { %}
                                    <button class="btn btn-danger btn-outline-danger deleteFile" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <input type="checkbox" name="delete" value="1" class="toggle">
                                {% } %}
                            </td>
                        </tr>
                    {% } %}
                    </script>

                </div>
                <div id="imageEditor" style="display: none;"><img /></div>
                <div id="docViewer" style="display: none;"></div>
          </main>
        </div>
    </div>

    <link rel="stylesheet" href="vendors/jstree/themes/default/style.min.css" charset="utf-8"/>
    <script type="text/javascript" src="vendors/jquery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/jstree/jstree.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/ace/ace.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/ace/ext-beautify.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/ace/ext-language_tools.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/ace/keybinding-vim.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/ace/keybinding-emacs.js" charset="utf-8"></script>
    
    <link rel="stylesheet" href="vendors/blueimp/css/gallery.min.css" charset="utf-8">
    <link rel="stylesheet" href="vendors/blueimp/css/jquery.fileupload.css" charset="utf-8">
    <link rel="stylesheet" href="vendors/blueimp/css/jquery.fileupload-ui.css" charset="utf-8">

    <script type="text/javascript" src="vendors/blueimp/js/vendor/jquery.ui.widget.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/tmpl.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/load-image.all.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/canvas-to-blob.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.blueimp-gallery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.iframe-transport.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload-process.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload-image.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload-audio.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload-video.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload-validate.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendors/blueimp/js/jquery.fileupload-ui.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php loadStaticResource('vendors/cruzersoftwares/js/main.js');?>" charset="utf-8"></script>    
</body>
</html>
