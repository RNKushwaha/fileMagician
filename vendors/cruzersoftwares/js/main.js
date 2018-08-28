function number_format_js (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function formatSizeUnits($bytes){
    if ($bytes >= 1073741824) {
        $bytes = number_format_js($bytes / 1073741824, 2) + ' GB';
    } else if ($bytes >= 1048576) {
        $bytes = number_format_js($bytes / 1048576, 2) + ' MB';
    } else if ($bytes >= 1024) {
        $bytes = number_format_js($bytes / 1024, 2) + ' KB';
    } else if ($bytes > 1) {
        $bytes = $bytes + ' bytes';
    } else if ($bytes == 1) {
        $bytes = $bytes + ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

;$(function () {
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'uploadHandler.php',
        autoLoad: true,
        dataType: 'json',
        disableImageResize: false,
        imageMaxWidth: 100,
        imageMaxHeight: 100,
        imageCrop: true // Force cropped images
    }).bind('fileuploaddone', function(e, data) {
        $.blueimp.fileupload.prototype.options.add.call(this, e, data);
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    $('#fileupload').addClass('fileupload-processing');

    var showFolderContents = function(){
        var directoryTxt = $(document).find('ul.jstree-container-ul').find('a.jstree-clicked').text();
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            data: { directory: $('#directory').val() || directoryTxt },
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $("#fileLists").html(tmpl("template-download", result));
            $("#fileLists tr").removeClass('fade');
            $('#metadata label[for=size] span').text(result.info.size);
            $('#metadata label[for=permission] span').text(result.info.permissionFull);
            $('#metadata #updatePermission').val(result.info.permission);
            $('#metadata label[for=created] span').text(result.info.created);
            $('#metadata label[for=lastmodified] span').text(result.info.modified);
            $('#metadata label[for=lastaccessed] span').text(result.info.accessed);
            $('#metadata label[for=totallines],#metadata label[for=line],#metadata label[for=character],#metadata label[for=height],#metadata label[for=width]').hide();
            $('#metadata label[for=contains] span').text(result.info.folderCount+' folders & '+result.info.totalFiles+' files');
            $('#metadata label[for=contains]').show();
            $('#metadata label[for=path],#editImage').hide();
            // $(this).fileupload('option', 'done') .call(this, $.Event('done'), {result: result});
        });
    }

    $('#settingsHandler').on('click',function(){
        $('#settings').toggle();
    });
    $('#refreshHandler').on('click',function(){
        $("#fileTree").jstree("refresh");
    });

    $('#infoHandler').on('click',function(){
        $('#info').toggle();
    });

    $('#theme').on('change',function(){
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/"+$(this).val());
        setCookie('theme',$(this).val(),30);
    });

    $('#font_size').on('change',function(){
        var editor = ace.edit("editor");
        editor.setFontSize($(this).val());
        setCookie('font_size',$(this).val(),30);
    });

    $('#wrap_text').on('change',function(){
        var editor = ace.edit("editor");
        var wrapOption = ($(this).val() == 'true' ? true : false);
        editor.getSession().setUseWrapMode(wrapOption);
        setCookie('wrap_text',wrapOption,30);
    });

    $('#soft_tab').on('change',function(){
        var editor = ace.edit("editor");
        var softtabOption = ($(this).val() == 'false' ? false : true);
        var softsizeOption = ($('#soft_tab_size').val() >0 ? $('#soft_tab_size').val() : 4);
        editor.getSession().setUseSoftTabs(softtabOption);
        editor.getSession().setTabSize(softsizeOption);
        setCookie('soft_tab',softtabOption,30);
        setCookie('soft_tab_size',softsizeOption,30);
    });

    $('#soft_tab_size').on('change',function(){
        var editor = ace.edit("editor");
        var softtabOption = ($('#soft_tab').val() == 'false' ? false : true);
        var softsizeOption = ($(this).val() >0 ? $(this).val() : 4);
        editor.getSession().setUseSoftTabs(softtabOption);
        editor.getSession().setTabSize(softsizeOption);
        setCookie('soft_tab',softtabOption,30);
        setCookie('soft_tab_size',softsizeOption,30);
    });

    $('#show_invisible').on('change',function(){
        var editor = ace.edit("editor");
        var show_invisible = ($(this).val() == 'true' ? true : false);
        editor.setShowInvisibles(show_invisible);
        setCookie('show_invisible',show_invisible,30);
    });

    $('#show_gutter').on('change',function(){
        var editor = ace.edit("editor");
        var show_gutter = ($(this).val() == 'false' ? false : true);
        editor.renderer.setShowGutter(show_gutter);
        setCookie('show_gutter',show_gutter,30);
    });

    $('#show_indent').on('change',function(){
        var editor = ace.edit("editor");
        var show_indent = ($(this).val() == 'false' ? false : true);
        editor.setDisplayIndentGuides(show_indent);
        setCookie('show_indent',show_indent,30);
    });

    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function eraseCookie(name) {   
        document.cookie = name+'=; Max-Age=-99999999;';  
    }

    $configureEditor = function(fileID, fileExt, fileContent ){
        var editor = (function() {
            var editor = ace.edit("editor");
            var theme = getCookie('theme');
            
            if (!theme) {
                theme = 'ambiance';
            }

            var font_size = getCookie('font_size');
            if (!font_size) {
                font_size = '14px';
            }

            var wrap_text = getCookie('wrap_text');
            if (wrap_text===true || wrap_text=='true') {
                wrap_text = true;
            } else{
                wrap_text = false;
            }

            var soft_tab = getCookie('soft_tab');
            if (soft_tab===false || soft_tab=='false') {
                soft_tab = false;
            } else{
                soft_tab = true;
            }

            var soft_tab_size = getCookie('soft_tab_size');
            if (!soft_tab_size) {
                soft_tab_size = 4;
            }

            var show_invisible = getCookie('show_invisible');
            if (show_invisible===true || show_invisible=='true') {
                show_invisible = true;
            } else{
                show_invisible = false;
            }

            var show_gutter = getCookie('show_gutter');
            if (show_gutter===false || show_gutter=='false') {
                show_gutter = false;
            } else{
                show_gutter = true;
            }

            var show_indent = getCookie('show_indent');
            if (show_indent===false || show_indent=='false') {
                show_indent = false;
            } else{
                show_indent = true;
            }

            editor.setTheme("ace/theme/"+theme);
            editor.setFontSize(font_size);
            editor.setShowInvisibles(show_invisible);
            editor.setShowPrintMargin(false);
            editor.renderer.setShowGutter(show_gutter);
            editor.setDisplayIndentGuides(show_indent);
            editor.getSession().setUseWrapMode(wrap_text);
            editor.getSession().setUseSoftTabs(soft_tab);
            editor.getSession().setTabSize(soft_tab_size);

            switch(fileExt){
                case 'text': editor.getSession().setMode("ace/mode/text");break;
                case 'txt':editor.getSession().setMode("ace/mode/text");break;
                case 'yml':editor.getSession().setMode("ace/mode/text");break;
                case 'md':editor.getSession().setMode("ace/mode/markdown");break;
                case 'htaccess':editor.getSession().setMode("ace/mode/text");break;
                case 'log':editor.getSession().setMode("ace/mode/text");break;
                case 'sql':editor.getSession().setMode("ace/mode/sql");break;
                case 'php': editor.getSession().setMode("ace/mode/php");break;
                case 'js': editor.getSession().setMode("ace/mode/javascript");break;
                case 'json':editor.getSession().setMode("ace/mode/json");break;
                case 'less':editor.getSession().setMode("ace/mode/less");break;
                case 'scss':editor.getSession().setMode("ace/mode/scss");break;
                case 'sass':editor.getSession().setMode("ace/mode/sass");break;
                case 'css':editor.getSession().setMode("ace/mode/css");break;
                case 'html': editor.getSession().setMode("ace/mode/html");break;
            }

            editor.session.setValue(fileContent);
            editor.clearSelection();
            editor.focus();
            return editor;
    })();

    editor.session.selection.on('changeCursor', function(e) {
        var meta = editor.selection.getCursor();
        $('#metadata label[for=totallines] span').text(editor.session.getLength());
        $('#metadata label[for=line] span').text((meta.row+1));
        $('#metadata label[for=character] span').text(meta.column);
    });

    editor.commands.addCommand({
        name: 'save',
        bindKey: {win: "Ctrl-S", "mac": "Cmd-S"},
        exec: function(editor) {
            $.post('handler.php?action=save_content', { 'id' : fileID, 'content' : editor.session.getValue() })
            .done(function (d) {
                var res = $.parseJSON(d);
                if(res.success!== undefined){
                    $('#msg').html(`<div class="alert alert-success fade in alert-dismissible" style="margin-top:18px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <strong>Success!</strong> The file has been saved!
                                    </div>`).show().delay(2000).fadeOut(100);
                } else{
                    var error = '';
                    if(res.error!==undefined) error = '<br/>'+res.error;
                    $('#msg').html(`<div class="alert alert-warning fade in alert-dismissible" style="margin-top:18px;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <strong>Error!</strong> The file could not be saved! ${error}
                                </div>`).show().delay(2000).fadeOut(100);
                }
            })
            .fail(function () {
                $('#msg').html(`<div class="alert alert-warning fade in alert-dismissible" style="margin-top:18px;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <strong>Error!</strong> The file could not be saved!
                                </div>`).show().delay(2000).fadeOut(100);
            });
        }
    });

    // set to false to prevent using worker, which is needed to run this from local html file due to browser security restritions
    var useWebWorker = window.location.search.toLowerCase().indexOf('noworker') == -1;
     ace.config.loadModule('ace/ext/tern', function () {
        editor.setOptions({
            /**
             * Either `true` or `false` or to enable with custom options pass object that
             * has options for tern server: http://ternjs.net/doc/manual.html#server_api
             * If `true`, then default options will be used
             */
            enableTern: {
                defs: ['browser', 'ecma5'],
                /* http://ternjs.net/doc/manual.html#plugins */
                plugins: {
                    doc_comment: {
                        fullDocs: true
                    }
                },
                useWorker: useWebWorker,
                /* if your editor supports switching between different files (such as tabbed interface) then tern can do this when jump to defnition of function in another file is called, but you must tell tern what to execute in order to jump to the specified file */
                switchToDoc: function (name, start) {
                    // console.log('switchToDoc called but not defined. name=' + name + '; start=', start);
                },
                startedCb: function () {
                    //once tern is enabled, it can be accessed via editor.ternServer
                },
            },
            enableSnippets: true,
            enableBasicAutocompletion: true,
        });
    });
    }
    
    $(window).resize(function () {
        var h = Math.max($(window).height() - 0, 420);
        $('#fileTree').height(h).filter('.default').css('lineHeight', h + 'px');
    }).resize();

    $('#fileTree')
        .jstree({
            'core' : {
                'data' : {
                    'url' : 'handler.php?operation=get_node',
                    'data' : function (node) {
                        return { 'id' : node.id };
                    }
                },
                'check_callback' : function(o, n, p, i, m) {
                    if(m && m.dnd && m.pos !== 'i') { return false; }
                    if(o === "move_node" || o === "copy_node") {
                        if(this.get_node(n).parent === this.get_node(p).id) { return false; }
                    }
                    return true;
                },
                'themes' : {
                    'responsive' : true,
                    'variant' : 'small',
                    'stripes' : true
                }
            },
            'sort' : function(a, b) {
                return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
            },
            'contextmenu' : {
                'items' : function(node) {
                    var tmp = $.jstree.defaults.contextmenu.items();
                    delete tmp.create.action;
                    tmp.create.label = "New";
                    tmp.create.submenu = {
                        "create_folder" : {
                            "separator_after"   : true,
                            "label"             : "Folder",
                            "action"            : function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                inst.create_node(obj, { type : "default" }, "last", function (new_node) {
                                    setTimeout(function () { inst.edit(new_node); },0);
                                });
                            }
                        },
                        "create_file" : {
                            "label"             : "File",
                            "action"            : function (data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                inst.create_node(obj, { type : "file" }, "last", function (new_node) {
                                    setTimeout(function () { inst.edit(new_node); },0);
                                });
                            }
                        }
                    };
                    if(this.get_type(node) === "file") {
                        delete tmp.create;
                    }
                    return tmp;
                }
            },
            'types' : {
                'default' : { 'icon' : 'folder' },
                'file' : { 'valid_children' : [], 'icon' : 'file' }
            },
            'unique' : {
                'duplicate' : function (name, counter) {
                    return name + ' ' + counter;
                }
            },
            'plugins' : ['state','dnd','sort','types','contextmenu','unique']
        })
        .on('delete_node.jstree', function (e, data) {
            $.get('handler.php?operation=delete_node', { 'id' : data.node.id })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('create_node.jstree', function (e, data) {
            $.get('handler.php?operation=create_node', { 'type' : data.node.type, 'id' : data.node.parent, 'text' : data.node.text })
                .done(function (d) {
                    data.instance.set_id(data.node, d.id);
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('rename_node.jstree', function (e, data) {
            $.get('handler.php?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
                .done(function (d) {
                    data.instance.set_id(data.node, d.id);
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('move_node.jstree', function (e, data) {
            $.get('handler.php?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent })
                .done(function (d) {
                    data.instance.refresh();
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('copy_node.jstree', function (e, data) {
            $.get('handler.php?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
                .done(function (d) {
                    //data.instance.load_node(data.parent);
                    data.instance.refresh();
                })
                .fail(function () {
                    data.instance.refresh();
                });
        })
        .on('changed.jstree', function (e, data) {
            if(data && data.selected && data.selected.length) {
                if(data.node.type=='default'){
                    $('#directory').val(data.node.id);
                    $('#uploader').show();
                    showFolderContents();
                    $('#editor,#imageEditor,#info,#editImage').hide();
                    return;
                }

                var fileid = data.selected.join(':');
                $.get('handler.php?operation=get_content&id=' + data.selected.join(':'), function (d) {
                    if(d && typeof d.type !== 'undefined') {
                        switch(d.type) {
                            case 'txt':
                            case 'text':
                            case 'md':
                            case 'js':
                            case 'json':
                            case 'css':
                            case 'scss':
                            case 'sass':
                            case 'less':
                            case 'html':
                            case 'htm':
                            case 'xml':
                            case 'yml':
                            case 'c':
                            case 'cpp':
                            case 'h':
                            case 'sql':
                            case 'log':
                            case 'py':
                            case 'rb':
                            case 'asp':
                            case 'aspx':
                            case 'java':
                            case 'htaccess':
                            case 'php':
                            case 'tmpl':
                                $('#editor').show();
                                $('#uploader,#imageEditor,#info').hide();
                                $configureEditor(fileid, d.type, d.content);
                                // $('#metadata label[for=path] span').text(d.info.path);
                                $('#metadata label[for=size] span').text(d.info.size);
                                $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                $('#metadata #updatePermission').val(d.info.permission);
                                $('#metadata label[for=created] span').text(d.info.created);
                                $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                $('#metadata label[for=lastaccessed] span').text(d.info.accessed);
                                $('#metadata label[for=totallines],#metadata label[for=line],#metadata label[for=character]').show();
                                $('#metadata label[for=contains],#metadata label[for=height],#metadata label[for=width],#editImage').hide();
                                break;
                            case 'png':
                            case 'jpg':
                            case 'jpeg':
                            case 'bmp':
                            case 'gif':
                            case 'ico':
                            case 'webp':
                                $('#imageEditor img').after('<p>..loading</p>');
                                $('#imageEditor img').one('load', function () { }).attr('src',d.info.hostUrl+d.content);
                                $('#imageEditor').show();
                                $('#editImage').attr('href','imageEditor.php?image='+d.info.hostUrl+d.content).show();
                                $('#uploader,#editor,#info').hide();
                                $('#imageEditor p').remove();
                                $('#metadata label[for=height] span').text(d.info.height);
                                $('#metadata label[for=width] span').text(d.info.width);
                                // $('#metadata label[for=path] span').text(d.info.path);
                                $('#metadata label[for=size] span').text(d.info.size);
                                $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                $('#metadata #updatePermission').val(d.info.permission);
                                $('#metadata label[for=created] span').text(d.info.created);
                                $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                $('#metadata label[for=lastaccessed] span').text(d.info.accessed);
                                $('#metadata label[for=height],#metadata label[for=width]').show();
                                $('#metadata label[for=contains],#metadata label[for=totallines],#metadata label[for=line],#metadata label[for=character]').hide();
                                break;
                            case 'zip':
                            case 'ZIP':
                            case 'gz':
                            case 'GZ':
                            case 'pdf':
                            case 'PDF':
                            case 'xls':
                            case 'XLS':
                            case 'csv':
                            case 'CSV':
                            case 'doc':
                            case 'docx':
                            case 'DOC':
                            case 'DOCX':
                            case 'DOCX':
                                $('#docViewer').html(`<iframe src="${d.content}&pid=explorer&efh=false&a=v&chrome=false&embedded=true" width="100%" style="border:0" height="100%"></iframe>`);
                                $('#docViewer').show();
                                $('#uploader,#imageEditor,#info,#editor').hide();
                                $('#metadata label[for=size] span').text(d.info.size);
                                $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                $('#metadata #updatePermission').val(d.info.permission);
                                $('#metadata label[for=created] span').text(d.info.created);
                                $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                $('#metadata label[for=lastaccessed] span').text(d.info.accessed);
                                $('#metadata label[for=contains],#metadata label[for=totallines],#metadata label[for=line],#metadata label[for=character],#metadata label[for=height],#metadata label[for=width],#metadata label[for=totallines],#metadata label[for=line],#metadata label[for=character],#editImage').hide();
                                break;
                            default:
                                $('#data .default').html(d.content).show();
                                break;
                        }
                    } else{
                        console.log('folder');
                    }
                });
            }
        });
});