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
        <meta name="description" content="File manager, Online Editor, Image crop, Image Resize, Image filters and Online document viewer">
        <meta name="keywords" content="File manager, Online Editor, Image crop, Image Resize, Image filters and Online document viewer">
        <title>fileMagician: Adding Awesomeness to the web</title>
        <link rel='shortcut icon' type='image/x-icon' href='./favicon.ico' />
        <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="vendor/cruzersoftwares/css/style.css" />
    </head>
    <body>
    <div id="msg"></div>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-primary flex-md-nowrap p-0 shadow col-sm-12 col-md-12 mr-0">
            <a class="navbar-brand bg-primary col-md-2" target="_blank" href="https://cruzersoftwares.github.io/fileMagician/"> fileMagician</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <form class="form-inline my-2 my-lg-0 col-md-7" id="seaerchFrm" method="get">
                <input class="form-control mr-sm-2" type="search" name="q" id="searchString" value="<?php if( isset($_GET) && isset($_GET['q']) ) echo htmlspecialchars($_GET['q']);?>" placeholder="Search" aria-label="Search">
            </form>

            <div class="collapse navbar-collapse col-md-3" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto float-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="avascript:;" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Plugins
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="db.php" target="_blank">Adminer</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" target="_blank" >Available Plugins</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="avascript:;" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                            <a class="dropdown-item" href="https://cruzersoftwares.github.io/fileMagician/" target="_blank">About fileMagician</a>
                            <a class="dropdown-item" href="https://cruzersoftwares.github.io/fileMagician/" target="_blank">Donate Us</a>
                            <a class="dropdown-item" href="https://cruzersoftwares.github.io/fileMagician/" target="_blank">Check for Update</a>
                        </div>
                    </li>
                    <li class="nav-item"><a id="infoHandler" class="icon_custom nav-link" title="Server Information"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACO0lEQVQ4T2NkQAO9vb0J////j2dgYHBAkzrAyMi4sLi4eAGyOCOM09/fr/D379/1fHx8BlZWVgyysrIMfHx8YOlPnz4xPH78mOHYsWMg9gVmZubAwsLCByA5sAFQzee1tbUFPDw8GB6+/81w6N5Xhofvf4ENkBdkY7BT4maQF2Rl2LFjB8PVq1c/MDMzG4IMARvQ09MD0mwA0rzwzHuGHTc+gzUWO4gyPHz3i2HNpY9gvocGL0O8iSDMkAslJSWGjCA/8/Lyzk9NTWVYcOodw9brn+BezLYWYXj15Q/D6osf4GLemnwMCWZCDLNnz2b4/PlzImNPT89+Dw8PBy5JVYbiDU/Qw5RBjIcFbAgy6A2QYfj2/DbIJQdABvwH2b7q6i+GzVcQNvnpCDA4qfEyfP31j6F6y1MUA3x1BBjCtNnArgAbUFxczFCx6QnD5Wff4Ap1JTkZCp0kGF59/sNQsekxigG6UlwMHX4yDL29vQgDStc/Yrj0FGEASEd3oBxYI0gOGehJc4Hl4AaAvLDkwjeGteffoSjsC5YH84vWPkQRDzYUYogx4IJ7ARyI7GLKDMmL76AonBiqCObnr76PIj43VoXh56u7kEBEjsaJ+54xrDrzBqzYTpWPodpLFsxu3faY4dBtSPSGmYgw5DtJIaIRPSH173nKsOLUKwYjeR4UW889/MIQYSbGUOgijZqQQKrQk/Ktl98ZNl98y3DzBSRQ1SW4GHz1hRnUxDmxJ2UkQ8jLTCipjMTsDABuRSCAwFnNyAAAAABJRU5ErkJggg==" /></a></li>
                    <li class="nav-item"><a id="settingsHandler" class="icon_custom nav-link" title="Settings"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC90lEQVQ4T5WTT0giURzHfzOjo25qBBtLdIjQmHNREFFUFz3MeGjJKDpuLCwIgv0xc5UhUUMrDy3txT12UYpMMooF2UOHyFuX8jBdpFOw2LTruM7MW96wiht72Xd6b378vu8z39/3EdC2MpkMNTc3pyQSiV0AWGorpdfW1nw8z9M8z/9q7yGah0QiMYkQygPAAUJocWVlxdKsbW9viwCQpihqSZblt4FA4GuzpgnEYrFJnU6X5zjO1Gg0UKFQkF4KOBwOo9lsJnK5XE1RlJYIgbGMRuOjy+UyDQwM6FRVhVKppIqiSIqiCFarFcxmszoyMkISBAGCIMjHx8c1SZJe49/RCDY3Nz9zHPeOYRj95eUlmEwmYBgGOjs7oVqtwt3dHdTrdRgbG4NyudzI5/MSwzBd2C9M0DJsamqqw2AwkP39/erJyYn08PCg9PT0UBzHGQVBIBFCarFY/IEQwvemeZ73EaFQ6CkQCGiGXVxcwPj4OBweHv6sVCr+SCTyKRQKve/t7U3Nzs6+urq6gunpac2/eDwuRiIRKxEMBp82NjYsWLVQKADLspBMJsVqtfomlUrV3G43xTDMd3zJ+fk5OJ1OTSAWi4nRaNRKrK+vPwWDQY2gWCzC6OgoYKcFQfgYj8d3/X6/t6+vL4YJSqUSTExMaALRaFTc2tqyEqurqy0PHA5HB03TpM1mU8/Ozurlclm22Ww6lmUN9/f3pKqq6unp6Y8/GUgnk0mfNoXl5eVdlmU9g4OD+uvra9Dr9WC328FisQAepSAIIMsyDA0Nwc3NTSOXy33Z2dn5gHu1HIii+Dg/P2+y2+067AXOgaqq5PPzsyZCkqQyPDxM4Ybb21s5k8nUKpVKVzabVTQCr9frJEkyu7CwYJIkCR0dHUnhcLgVZZ7nxZmZGSNN00Q2m8VJdO3t7X3TCJqZ9ng8ToqisphAURQcrpZAOBwWCYI4IElyESHUav5LAB/cbjcNAEp3d3fy5Wvc39/34ZFi7H++xvaP/7P/DYk4ZCC622bVAAAAAElFTkSuQmCC"/></i></a></li>
                    <li class="nav-item"><a id="refreshHandler" class="icon_custom nav-link" title="Refresh List"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACaElEQVQ4T6VSQUgiYRR+/8yupmYdWliwxWPOacFzICxI0CothO7FCGFLNpBFwkHcdJyU0IgNNohhwd1T0CHxEJQnJYWCbt7CDuLFJUIQZlpdTedf/slKW7YO+98e33vf+973fwj+86GH88FgkKFpegFj/A4AXvbwnwihdKfTSSYSifL5+TmuVCowNTWFBggCgcBnnU4XtFgsKoZhnut0OgWXJAmfnZ1dFwqF9ujo6HC9XpfUarU+FArdE7Asu8kwzMfp6ekhrVb7l7JbpfF4HDiOA57npY2NjRGl0efzrZvN5k82m22I1OVyuXN8fNyuVqsyqcfHxymXy6XtPzcSiUiCIIwgr9dr0Ov1Ja/XO0zTNOTz+VahUDgEgKRGo8mTocvLyx9Go/H9/Pz8HUc0GpV2dnZuFHg8nnUA+AAAKjJI03RMEIQ6wYhhfr9fCofD+n4FsVhM2t/fvyF47NntdtyPNxoNqVcnc7ncskLAcdwmxnihv5G4TGri9GMLFJBlWZHn+QGJxOXFxUX9xMQEmp2dfYUxXgYAsqSNEPqeTqcDZFYhWFpaEldXV+8Itra2iHHfLi4uorIsv6EoSrBarerJyUlVt9uF7e3tX7Va7fXBwUFZIZibmxM5jhtQsLKycoUQIl9Ik2AZDAaa9GYymdbp6enXTCZzr2BmZkYkLqdSKXA4HP88eXd393exWBTGxsbYvb297t0JNpsNN5vNK7vdTrJwbTKZnt2msdFo4FKp1Mlmsy1RFL8cHR3x/RuUE5xOJ15bWwO3222mKIoY9RYAXvQaawBwKMty8uTkpPhQ3pM5eConfwCJtvMRCghiWwAAAABJRU5ErkJggg=="/></a></li>
                    <li class="nav-item"><a href="logout.php" id="refreshHandler" class="icon_custom nav-link" title="Log Out"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACOklEQVQ4T32TXUiTURjHf+ddm24iadEHEaSBEAx2YWSZ5UV0lVTgTUEQEWYXyT6sBV0Uo0sZ0/WBYNGFEFRQYWAXIRSRzQrKhCAS+oA+LTHc5jbX3hPn7IN3w3pu3ve853l/53n+5/8IKkJ6aULQiuAQ0FrYjgG3kUyIKFPWX4R1If1cAg5XQivWw2KAnuK3EkD6uQHsweaAliN2YkNZ+mWtTgyIOO0+B+ODi+QW1Zf7YoCD6kUDSifXrRccG3WxokFwZnmiDKBgX6dMrnQs8PuzBHQlQgZwI4npk3tf1LDOYzD3yeR8Q7IMcO5jDfUbDA2JbEnqSiQ7hPQTAnrZ2WOn82I18R+SPk+SxIwsAzjrBMFJl4bc9Wd4HFW9RBTgAbAN77iLxu02bh1P6/5VWDVQa/e+ZXSNOPnwNMeFtgVgQgFmATvhTK1u4+yahD59KYDaV3npeak1gvj/AQeuVmvQza60fhYB2RScdsWBlAI8ATwEX+cFtLZQaYhiC19e5Qg3qxYmhfQRRtDNrqCDvX1VJH/lRZz/lm+jGFYRR05meBRRIg4K6WUzBg91eade1rDWbWjIvWCGd2N/MHPQ2GZjf7hK38D3Nybh5vw1Qnu5kVZuNOgedbJ6k7GknWfemgx1pJh9b5aMVEyUPu4g2I3dibbt1qN2VjXlQT+nTZ5dy+q7VwJKxkSUzpKVtZ1DGMxxuTCF/54nyXXqOSFCqCrys2ANGaAFqSdSjXJTYW8aiCEYFv08t+b/BbJM7zmKOr0oAAAAAElFTkSuQmCC" ></a></li>
                </ul>
            </div>
            </nav>

       <div class="row" id="container_wrap">
          <nav class="bg-light sidebar1" id="left_panel">
            <div class="sidebar-sticky">
                <div id="fileMleftsidebar">
                    <div id="fileTree"></div>
                </div>
            </div>
          </nav>

          <main role="main" id="right_panel">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center" id="data">
                    <div id="metadata">
                        <label for="size">Size: <span></span></label>
                        <label for="dimension">Dimension: <span></span></label>
                        <label for="contains">Contains: <span></span></label>
                        <label for="created">Created: <span></span></label>
                        <label for="lastmodified">Modified: <span></span></label>
                        <label for="line">Line: <span></span></label>
                        <label for="permission">Permission: <span></span>
                            <input type="text" class="input input-sm" value="" required="required" id="updatePermission">
                            <a id="updatePermissionBtn"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACqUlEQVQ4T6WTW0iTYRjH/99pc9MN576yltoBYenMXJgijZT0opslZCDqnRddhBRosItCRlAwSCGJIKPu1IIKrCspOuBEDI2gVMwzzXma25rbt9O374tvh3INuumF5+Z9nv/vfZ8Tgf88RIZ+iNWBIi5CxBkQKIz7RfwAgTHExJdocTn3atIBz1hzFiPv1Ov0xYfZwrwceU6WCCAQ9odWtlfdc+vzC+FouBfNrtcpyB/AIGtWK9TWOsPZEhqUguf5tM/RNI2wEAl+nBmd9Yf8VrQmIAnAEKuTgxloqKivjoVjCkEQMjIbMT7F+S8tECgh+OHb6ESUiLZJ6SQAT7QdZcdKLfnZ+QWRSCRDfK/sNozqsvj9uU9NcHgcjnnnog3tO/cTgH7NYK2xrjG4yylT6mg0Cj/nh7XoOmo0lZBSuPH1Dka23kOlUXGLW4vDuOxpTQAe5NrrTzfUeL1ecrLhDYqen0QgGMApdTn6y3vi4scrA+hbfhQPVyqUwprLMY4rXlMC0Jdrr62qq2lmL5AmrhIUReHa6k08PH4XDM3gncuOru/dEMhEbWS0TNh0bIzjagrQox40VJxoXP+5oRzVvwLDMPFXJZvnlnBpph2Q7SlNVOQ8SzvD6PIlU7CpO7RHWAuZTxfwOzymjG9/AwxTJiA7va4hh98RXA/ZYPEli2hT6iiCGWCrDlRHs3iFuCViutIOw6QJ2J8uFvyxoO+ze0IUY22wcMk2SjHdSjOtpK0qk7aEUJEKcU0EDv0l9vHB3TH3rMAJVtzi9gxSKq5JbiaOEp3ykuximV6ZR2noLMkV8/ChyBznDs8GFsRlsRcvwhmjTAPQAmChow+iVDBhn2gATeribF5wYpuYxgxph5PfBLANwCV5UrtASu1NmgT714lJ+wWAAyD8AhbJDCA03+U6AAAAAElFTkSuQmCC"/></a>
                        </label>
                        <a href="imageEditor.php?image=" target="_blank" class="btn btn-primary btn-sm" id="editImage">Edit Image</a>
                    </div>
                </div>
                <div id="tabs">
                    <div class="menu-wrapper">
                        <ul class="nav1 nav-tabs1 menu" id="filesTab" role="tablist">
                            <li class="nav-item" id="newTab" style="">
                                <a class="nav-link" id="new-tab" data-id="0" data-toggle="tab" role="tab" aria-controls="contact" aria-selected="false">+</a>
                            </li>
                            <li class="nav-item muted"></li>
                        </ul>
                        <div class="paddles">
                            <button class="left-paddle paddle btn btn-primary"> < </button>
                            <button class="right-paddle paddle btn btn-primary"> > </button>
                        </div>
                    </div>
                    <div class="tab-content" id="myTabContent">
                    </div>
                </div>
                <div id="info">
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
                          echo '<tr><th>Server Max. Upload Size </th><td>' .ini_get('upload_max_filesize').'</td></tr>';
                    ?>
                    </table>
                    <a class="btn btn-danger btncloseDiv">Close</a>        
                </div>
                <div id="settings"> 
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
                <div id="uploader">
                    <form id="fileupload" action="./uploadHandler.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="directory" value="" id="directory">
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-7">
                                <span class="btn btn-success fileinput-button">
                                    <span>Add files...</span>
                                    <input type="file" name="files[]" multiple>
                                </span>
                                
                                <button type="button" class="btn btn-danger delete">
                                    <span>Delete</span>
                                </button>

                                <button type="button" class="btn btn-info extract">
                                    <span>Extract</span>
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
                                    <button class="btn btn-danger btn-outline-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABuklEQVQ4T32TOY7CQBBFi5gMEbBvgUXEEnCJmWNwCI6AIEHIGBuQBZeYOQghq1jEKpDIWIx69EvqHhjDWGq5uv3r1e/usof+eSqVihgMBqRpGpVKJc8r6ctFCIvFopCJEmTbtkv/tGDbtrjf7+Q4Do/b7cbv6/XKsZxjrVarca4C9Ho9USgUWHS5XNQ4n8+EgTX5BnAymZCu6x4F6Ha7XF06kBUhlm4eHUDXaDR+AbBTLpdFOp1WlpGMgeoyBmw+n5M8j6czaLfbIp/Psz1USyaT1O/3OTkYDNLxeOR4OByy/aczwKTVaolcLsei2WxGiUSCttstVwyHw3Q4HPjbaDR6DbAsiwGwvFgsKBaL0W63Y0AkEqH9fs/OAMD+XQ5M0xTZbJarLJdLikaj7AAwxAAAPh6PyTAMN8AwDHaA61qv11xVAuAGsbzCt4BMJsMiAEKhECfBTTwep81mowDNZtPtQNd1AQBsYu84eSQBgBsBVDowTdMNqNfrDIAI+w0EAmoLqVSKVqsVf5tOp/QW4PP5yO/3qwZ6bGsJRj9YluV20Ol0vk6n08ffH+qxhdGJXq/3u1qtfuIafwDkZqQgnPMDpQAAAABJRU5ErkJggg==" class=""/>
                                    </button>
                                    <input type="checkbox" name="delete" value="1" class="toggle">
                                {% } %}
                            </td>
                        </tr>
                    {% } %}
                    </script>

                </div>
                <div id="imageEditor"><img /></div>
                <div id="docViewer"></div>
          </main>
        </div>
    </div>

    <link rel="stylesheet" href="vendor/jstree/themes/default/style.min.css" charset="utf-8"/>
    <link rel="stylesheet" href="vendor/jquery/css/jquery-ui.1.12.1.min.css" charset="utf-8">
    <link rel="stylesheet" href="vendor/blueimp/css/gallery.min.css" charset="utf-8">
    
    <script type="text/javascript" src="vendor/jquery/js/jquery-1.12.4.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/jquery/js/jquery-ui.1.12.1.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/jstree/jstree.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/ace/ace.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/ace/ext-beautify.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/ace/ext-language_tools.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/ace/keybinding-vim.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/ace/keybinding-emacs.js" charset="utf-8"></script>

    <script type="text/javascript" src="vendor/blueimp/js/vendor/jquery.ui.widget.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/tmpl.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/load-image.all.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/canvas-to-blob.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.blueimp-gallery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.iframe-transport.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload-process.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload-image.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload-audio.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload-video.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload-validate.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/blueimp/js/jquery.fileupload-ui.js" charset="utf-8"></script>

    <script type="text/javascript" src="vendor/cruzersoftwares/js/main.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/popper.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js" charset="utf-8"></script>
</body>
</html>
