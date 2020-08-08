function number_format_js (number, decimals, dec_point, thousands_sep) {
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

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

;$(function () {
    try{
        uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
        $('#fileupload').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: 'uploadHandler.php',
            autoLoad: true,
            autoUpload: true,
            dataType: 'json',
            maxFileSize: 50000000,//50mb
            maxNumberOfFiles: 100,
            disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
            imageMaxWidth: 100,
            imageMaxHeight: 100,
            imageCrop: true,
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true
        }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Upload')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
                var link = $('<a>')
                    .attr('target', '_blank')
                    .prop('href', file.url);
                $(data.context.children()[index])
                    .wrap(link);
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

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
                $('#metadata label[for=size] span').text(result.info.size+' ('+result.info.sizeByte+' bytes)');
                $('#metadata label[for=permission] span').text(result.info.permissionFull);
                $('#metadata #updatePermission').val(result.info.permission);
                $('#metadata label[for=created] span').text(result.info.created);
                $('#metadata label[for=lastmodified] span').text(result.info.modified);
                $('#metadata label[for=line],#metadata label[for=dimension]').hide();
                $('#metadata label[for=contains] span').text(result.info.folderCount+' folders & '+result.info.totalFiles+' files');
                $('#metadata label[for=contains]').show();
                $('#metadata label[for=path],#editImage,#docViewer').hide();
            });
        }

        $(document).on('click', '.deleteFile', function(e){
            if(!confirm('Are you sure want to delete this file?')){
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        $(document).on('click', '.extract', function(e){
            e.preventDefault();
            e.stopPropagation();

            $('#fileLists input[type=checkbox]:checked').each(function(e){
                var file = $(this).parents('tr').find('.name a').attr('download');
                $.post('handler.php?operation=extract', { directory: $('#directory').val() || directoryTxt, id: file })
                .done(function (d) {
                    console.log(typeof d );
                    if(typeof d == 'object'){
                        var res = JSON.parse(JSON.stringify(d));
                    } else if(typeof d == 'string'){
                        var res = JSON.parse(d.toString());
                    }
                    
                    if(res.success!== undefined){
                        $('#msg').html(`<div class="alert alert-success fade in show alert-dismissible" style="margin-top:18px;">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                            <strong>Success!</strong> Files extracted sucessfully!
                                        </div>`).show().delay(2000).fadeOut(100);
                    } else{
                        var error = '';
                        if(res.error!==undefined) error = '<br/>'+res.error;
                        $('#msg').html(`<div class="alert alert-warning fade in show alert-dismissible" style="margin-top:18px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <strong>Error!</strong> Cannot extracted file! ${error}
                                    </div>`).show().delay(2000).fadeOut(100);
                    }
                })
                .fail(function () {
                    $('#msg').html(`<div class="alert alert-warning fade in show alert-dismissible" style="margin-top:18px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <strong>Error!</strong> Cannot extracted file!
                                    </div>`).show().delay(2000).fadeOut(100);
                });
            })
            return false;
        });

        $("#left_panel").resizable({
            handles: 'e',
            maxWidth: 900,
            minWidth: 20,
            resize: function (event, ui) {
                var currentWidth = ui.size.width;
                // this accounts for padding in the panels + 
                // borders, you could calculate this using jQuery
                var padding = 12;
                // this accounts for some lag in the ui.size value, if you take this away 
                // you'll get some instable behaviour
                $(this).width(currentWidth);
                // set the content panel width
                $("#right_panel").width($("#container_wrap").width() - currentWidth - padding);
                $("#right_panel #myTabContent div.editor").css('left', currentWidth);
                $("#uploader,#imageEditor").css('left', currentWidth);
                
                var editors = getAllEditorIDs();
                for (var ed of editors) {
                    var editor = ace.edit(ed);
                    editor.resize();
                }
            }
        });

        var getAllEditorIDs = function(){
            var ids = [];
            $(document).find('#filesTab a.nav-link:not(#new-tab)').each(function(){
                ids.push( $(this).attr('href').replace('#', '') );
            })
            return ids;
        }

        $('#settingsHandler').on('click',function(){
            $('#info').hide();
            $('#settings').toggle();
        });

        $('#refreshHandler').on('click',function(){
            $("#fileTree").jstree("refresh");
        });

        $('#infoHandler').on('click',function(){
            $('#settings').hide();
            $('#info').toggle();
        });
        $('.btncloseDiv').on('click',function(){
            $(this).parent('div').hide();
        });

        $('#theme').on('change',function(){
            var editors = getAllEditorIDs();
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.setTheme("ace/theme/"+$(this).val());
            }
            setCookie('theme',$(this).val(),30);
        });

        $('#font_size').on('change',function(){
            var editors = getAllEditorIDs();
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.setFontSize($(this).val());
            }
            setCookie('font_size',$(this).val(),30);
        });

        $('#wrap_text').on('change',function(){
            var editors = getAllEditorIDs();
            var wrapOption = ($(this).val() == 'true' ? true : false);
            
            for (var ed of editors){
                var editor = ace.edit(ed);
                editor.getSession().setUseWrapMode(wrapOption);
            }
            setCookie('wrap_text',wrapOption,30);
        });

        $('#soft_tab').on('change',function(){
            var editors = getAllEditorIDs();
            var softtabOption = ($(this).val() == 'false' ? false : true);
            var softsizeOption = ($('#soft_tab_size').val() >0 ? $('#soft_tab_size').val() : 4);
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.getSession().setUseSoftTabs(softtabOption);
                editor.getSession().setTabSize(softsizeOption);
            }
            setCookie('soft_tab',softtabOption,30);
            setCookie('soft_tab_size',softsizeOption,30);
        });

        $('#soft_tab_size').on('change',function(){
            var editors = getAllEditorIDs();
            var softtabOption = ($('#soft_tab').val() == 'false' ? false : true);
            var softsizeOption = ($(this).val() >0 ? $(this).val() : 4);
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.getSession().setUseSoftTabs(softtabOption);
                editor.getSession().setTabSize(softsizeOption);
            }
            setCookie('soft_tab',softtabOption,30);
            setCookie('soft_tab_size',softsizeOption,30);
        });

        $('#show_invisible').on('change',function(){
            var editors = getAllEditorIDs();
            var show_invisible = ($(this).val() == 'true' ? true : false);
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.setShowInvisibles(show_invisible);
            }
            setCookie('show_invisible',show_invisible,30);
        });

        $('#show_gutter').on('change',function(){
            var editors = getAllEditorIDs();
            var show_gutter = ($(this).val() == 'false' ? false : true);
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.renderer.setShowGutter(show_gutter);
            }
            setCookie('show_gutter',show_gutter,30);
        });

        $('#show_indent').on('change',function(){
            var editors = getAllEditorIDs();
            var show_indent = ($(this).val() == 'false' ? false : true);
            for (var ed of editors) {
                var editor = ace.edit(ed);
                editor.setDisplayIndentGuides(show_indent);
            }
            setCookie('show_indent',show_indent,30);
        });

        $(document).on('click', '#filesTab a:not(#new-tab)', function (e) {
            e.preventDefault();
            var tabId = $(this).attr('href').replace('#','');
            $(document).find('.editor').removeClass('active').removeClass('show');
            $(document).find('#filesTab a.nav-link').removeClass('active');
            $(document).find('#' + tabId).addClass('active').addClass('show');
            $(this).addClass('active');
            //move cursor to the previous position
            var editor = ace.edit(tabId);
            editor.focus();
            //todo: update the meta info
        })
        //open new file editor
        $(document).on('click', '#filesTab li a#new-tab', function (e) {
            e.preventDefault();
            var fileCounter = 1;

            if ($(this).data('id') == 'undefined' && $(this).data('id')== ''){
                $(this).data('id', fileCounter);
            } else{
                fileCounter = parseInt($(this).data('id'));
                fileCounter++;
                $(this).data('id', fileCounter);
            }

            var directoryStr = $('#directory').val();

            tabId = 'new_file_'+fileCounter+'.txt';
            tabId2 = 'new_file_'+fileCounter+'__txt';
            
            if(directoryStr!=''){
                tabId = directoryStr+'_new_file_'+fileCounter+'.txt';
                tabId2 = directoryStr+'_new_file_'+fileCounter+'__txt';
            }

            $('#myTabContent').append('<div id="editor_'+tabId2+'" class="editor tab-pane fade ace_editor ace_hidpi ace-dawn" role="tabpanel" style="font-size: 12px; left: 250px;"></div>');
            $(document).find('.editor').removeClass('active').removeClass('show');
            $(document).find('#filesTab a.nav-link').removeClass('active');
            $(document).find('#filesTab li a#new-tab').parent().before('<li class="nav-item"><a id="link_'+tabId2+'" class="nav-link active" data-new="1" title="'+tabId+'" data-toggle="tab" role="tab" aria-controls="'+tabId+'" aria-selected="true" href="#editor_'+tabId2+'">'+tabId+'&nbsp;&nbsp; <img class="svg-inline--fa" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACmklEQVQ4T6WTX0hTcRTHv3f37r+Mac5qZmDT5grmg46h6ZOCimxoBWG+FfQQ0oNEvsXobRA+RPQQ1JtJD4kYqDAMKdFUCENyDSfS/jnmGG7Wvft37y/ubXc4qacOHPjxO7/v53fO+Z0fhf806rR+GjDTwHUK6CTARTFOASECrPLAzAgQO6mpALwFXEqtdvyS3d5kbm6u0RoMGkIIuEwmG9vdTe1vbwcLHDd5C3gvQ8qAN4BLbzR6nIODNsIw2mKxWJEcwzDgczluc2HBz6XTntsliAR4BZj1Gs1Up9vt5AoF7eWZGXwfGqoAtMzOIjA8DIYQbnNxcZ3N5UbvAjEJ8BoYa2lrm9DX1V2wz8+XhZs9PdLasbRU3tvq70cqHI782Nnx3gGeS4CXwHRHX587eXSkSyaTuBkMIpvNIp1Og2VZ8DwPhUKBD+3tUCqVqK2uZmOBwNw9YEQCvABWrg0MdCQSCQVfKOAgGkWrzweapiWBSqXCR4cDdCkPfVWVkAiH1+4DXRLgmQjo7e2Ih0IKNpUC4XlYl5fLYhHwubW1XIZSrRbC8fjaAxnwFJhut9vdPyMRHQhBwymx+AKib1itEqRAUexeKjX3UC7BC4w1mkyPzlJUg9HnK9/81eGQBN2BgAQQbctiQTibjcQ5zjshN9ELmCmannKaTM50Pq+9srGBHYcDdaWkEwC69vawYrFAJwjcl0xmnRAyOiE/o3juMeDSMYyny2CwZQBtPSEVcxAFUCUI3OrxsZ8VBM+Tk4Mkn7wBuBopatymVjdZVaoaI01rxNgRz2cD+XzKn8sF9wmZfPeXURYLPAOgth44ZwO6TcBVGjgvAnjg4BD45gc+RQGxokMASQBF+S8oAOhK/qdb/zYewC8ALADhN+QiACBv5lW6AAAAAElFTkSuQmCC"/></a></li>');
            $(document).find('#editor_' + tabId2).addClass('active').addClass('show');
            $configureEditor(tabId2, 'txt', '');
        })

        //clean the DOM and editor
        var cleandEditorDOM = function (tabId, liEl){
            var editor1 = ace.edit(tabId);
            editor1.destroy();
            var el = editor1.container;
            el.parentNode.removeChild(el);
            editor1.container = null;
            editor1.renderer = null;
            editor1 = null;

            liEl.remove();
            $(document).find('#' + tabId).remove();
            //show first editor opened
            if($('#filesTab>li a.nav-link').attr('href')){
                var tabIdFirst = $('#filesTab>li a.nav-link').attr('href').replace('#', '');
                $(document).find('.editor').removeClass('active').removeClass('show');
                $(document).find('#filesTab a.nav-link').removeClass('active');
                $(document).find('#' + tabIdFirst).addClass('active').addClass('show');
                $('#filesTab li:first-child a.nav-link').addClass('active');
                //move cursor to the previous position
                var editor2 = ace.edit(tabIdFirst);
                editor2.focus();
            }
        };

        //close the editor
        $(document).on('click', '#filesTab a .svg-inline--fa', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var tabId = $(this).parent('a').attr('href').replace('#','');
        
            if ( $(this).hasClass('unsaved') ){
                if( !confirm("You have unsaved changes on this page. \nDo you want to leave this page and discard your changes or stay on this page?")){
                    return;
                }
            }

            cleandEditorDOM(tabId,$(this).parents('li'));
        })

        var getfileName = function(filename){
            return filename.split('\\').pop().split('/').pop();
        };

        var getfileNameForId = function(filename){
            var tabId = filename.split('/').join('_');
            return tabId.split('.').join('__');
        };

        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){/*e=e.replace(/rn/g,"rn");*/var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
        
        var configEditorParams = function (tabId, fileExt, fileContent, ){
            var editor = (function () {
                var editor = ace.edit("editor_" + tabId);
                var theme = getCookie('theme');

                if (!theme) {
                    theme = 'eclipse';
                }

                var font_size = getCookie('font_size');
                if (!font_size) {
                    font_size = '14px';
                }

                var wrap_text = getCookie('wrap_text');
                if (wrap_text === true || wrap_text == 'true') {
                    wrap_text = true;
                } else {
                    wrap_text = false;
                }

                var soft_tab = getCookie('soft_tab');
                if (soft_tab === false || soft_tab == 'false') {
                    soft_tab = false;
                } else {
                    soft_tab = true;
                }

                var soft_tab_size = getCookie('soft_tab_size');
                if (!soft_tab_size) {
                    soft_tab_size = 4;
                }

                var show_invisible = getCookie('show_invisible');
                if (show_invisible === true || show_invisible == 'true') {
                    show_invisible = true;
                } else {
                    show_invisible = false;
                }

                var show_gutter = getCookie('show_gutter');
                if (show_gutter === false || show_gutter == 'false') {
                    show_gutter = false;
                } else {
                    show_gutter = true;
                }

                var show_indent = getCookie('show_indent');
                if (show_indent === false || show_indent == 'false') {
                    show_indent = false;
                } else {
                    show_indent = true;
                }

                editor.setTheme("ace/theme/" + theme);
                editor.setFontSize(font_size);
                editor.setShowInvisibles(show_invisible);
                editor.setShowPrintMargin(false);
                editor.renderer.setShowGutter(show_gutter);
                editor.setDisplayIndentGuides(show_indent);
                editor.getSession().setUseWrapMode(wrap_text);
                editor.getSession().setUseSoftTabs(soft_tab);
                editor.getSession().setTabSize(soft_tab_size);

                switch (fileExt) {
                    case 'text': editor.getSession().setMode("ace/mode/text"); break;
                    case 'txt': editor.getSession().setMode("ace/mode/text"); break;
                    case 'md': editor.getSession().setMode("ace/mode/markdown"); break;
                    case 'ts': editor.getSession().setMode("ace/mode/typescript"); break;
                    case 'js': editor.getSession().setMode("ace/mode/javascript"); break;
                    case 'json': editor.getSession().setMode("ace/mode/json"); break;
                    case 'css': editor.getSession().setMode("ace/mode/css"); break;
                    case 'scss': editor.getSession().setMode("ace/mode/scss"); break;
                    case 'sass': editor.getSession().setMode("ace/mode/sass"); break;
                    case 'less': editor.getSession().setMode("ace/mode/less"); break;
                    case 'html': editor.getSession().setMode("ace/mode/html"); break;
                    case 'htm': editor.getSession().setMode("ace/mode/html"); break;
                    case 'xml': editor.getSession().setMode("ace/mode/xml"); break;
                    case 'yml': editor.getSession().setMode("ace/mode/text"); break;
                    case 'yaml': editor.getSession().setMode("ace/mode/yaml"); break;
                    case 'c': editor.getSession().setMode("ace/mode/c_cpp"); break;
                    case 'cpp': editor.getSession().setMode("ace/mode/c_cpp"); break;
                    case 'h': editor.getSession().setMode("ace/mode/h"); break;
                    case 'sql': editor.getSession().setMode("ace/mode/sql"); break;
                    case 'pgsql': editor.getSession().setMode("ace/mode/pgsql"); break;
                    case 'log': editor.getSession().setMode("ace/mode/text"); break;
                    case 'py': editor.getSession().setMode("ace/mode/python"); break;
                    case 'rb': editor.getSession().setMode("ace/mode/ruby"); break;
                    case 'pl': editor.getSession().setMode("ace/mode/perl"); break;
                    case 'asp': editor.getSession().setMode("ace/mode/csharp"); break;
                    case 'aspx': editor.getSession().setMode("ace/mode/csharp"); break;
                    case 'java': editor.getSession().setMode("ace/mode/java"); break;
                    case 'htaccess': editor.getSession().setMode("ace/mode/apache_conf"); break;
                    case 'sh': editor.getSession().setMode("ace/mode/batchfile"); break;
                    case 'php': editor.getSession().setMode("ace/mode/php"); break;
                    case 'blade': editor.getSession().setMode("ace/mode/php_laravel_blade"); break;
                    case 'tmpl': editor.getSession().setMode("ace/mode/text"); break;
                    case 'twig': editor.getSession().setMode("ace/mode/twig"); break;
                    case 'env': editor.getSession().setMode("ace/mode/text"); break;
                    default: //editor.getSession().setMode("ace/mode/text"); break;
                }

                editor.session.setValue( Base64.decode(fileContent));
                editor.clearSelection();
                editor.focus();
                $("#editor_" + tabId).css('left', $('#left_panel').css('width'));
                return editor;
            })();

            return editor;
        };

        $configureEditor = function(fileID, fileExt, fileContent ){
            var fileName = getfileName(fileID);
            var tabId = getfileNameForId(fileID);
            $(document).find('.editor').removeClass('active').removeClass('show');
            $(document).find('#filesTab a.nav-link').removeClass('active');

            if ($(document).find('#editor_' + tabId).length) {
                $(document).find('#editor_' + tabId).addClass('active').addClass('show');
                $(document).find('#filesTab a#link_' + tabId).addClass('active');
            } else {
                $('#newTab').before('<li class="nav-item"><a id="link_' + tabId + '" class="nav-link active" title="' + fileID + '" data-toggle="tab" role="tab" aria-controls="' + fileID+'" aria-selected="true" href="#editor_' + tabId + '">' + fileName + '&nbsp;&nbsp; <img class="svg-inline--fa" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACmklEQVQ4T6WTX0hTcRTHv3f37r+Mac5qZmDT5grmg46h6ZOCimxoBWG+FfQQ0oNEvsXobRA+RPQQ1JtJD4kYqDAMKdFUCENyDSfS/jnmGG7Wvft37y/ubXc4qacOHPjxO7/v53fO+Z0fhf806rR+GjDTwHUK6CTARTFOASECrPLAzAgQO6mpALwFXEqtdvyS3d5kbm6u0RoMGkIIuEwmG9vdTe1vbwcLHDd5C3gvQ8qAN4BLbzR6nIODNsIw2mKxWJEcwzDgczluc2HBz6XTntsliAR4BZj1Gs1Up9vt5AoF7eWZGXwfGqoAtMzOIjA8DIYQbnNxcZ3N5UbvAjEJ8BoYa2lrm9DX1V2wz8+XhZs9PdLasbRU3tvq70cqHI782Nnx3gGeS4CXwHRHX587eXSkSyaTuBkMIpvNIp1Og2VZ8DwPhUKBD+3tUCqVqK2uZmOBwNw9YEQCvABWrg0MdCQSCQVfKOAgGkWrzweapiWBSqXCR4cDdCkPfVWVkAiH1+4DXRLgmQjo7e2Ih0IKNpUC4XlYl5fLYhHwubW1XIZSrRbC8fjaAxnwFJhut9vdPyMRHQhBwymx+AKib1itEqRAUexeKjX3UC7BC4w1mkyPzlJUg9HnK9/81eGQBN2BgAQQbctiQTibjcQ5zjshN9ELmCmannKaTM50Pq+9srGBHYcDdaWkEwC69vawYrFAJwjcl0xmnRAyOiE/o3juMeDSMYyny2CwZQBtPSEVcxAFUCUI3OrxsZ8VBM+Tk4Mkn7wBuBopatymVjdZVaoaI01rxNgRz2cD+XzKn8sF9wmZfPeXURYLPAOgth44ZwO6TcBVGjgvAnjg4BD45gc+RQGxokMASQBF+S8oAOhK/qdb/zYewC8ALADhN+QiACBv5lW6AAAAAElFTkSuQmCC"/> </a></li>');
                $('#myTabContent').append('<div id="editor_' + tabId + '" class="editor tab-pane fade show active" role="tabpanel"></div>');
            }

            var editor = configEditorParams(tabId, fileExt, fileContent);

            editor.session.selection.on('changeCursor', function(e) {
                var meta = editor.selection.getCursor();
                $('#metadata label[for=line] span').text((meta.row+1)+':'+meta.column);
            });
            
            editor.commands.addCommand({
                name: 'save',
                bindKey: {win: "Ctrl-S", "mac": "Cmd-S"},
                exec: function(editor) {
                    $('#msg').html(`<div class="alert alert-info fade in show alert-dismissible" style="margin-top:18px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <strong>Info!</strong> The file is being saved!
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                                        </div>
                                    </div>`).show();
                    var newfile = ($('#link_'+fileID).attr('data-new') == 1) ? 1 : 0;
                    $.post('handler.php?action=save_content', { 'id' : fileID, newfile: newfile, 'content' :  Base64.encode(editor.session.getValue()) })
                    .done(function (d) {
                        var res = $.parseJSON(d);
                        if(res.success!== undefined){
                            $('#msg').html(`<div class="alert alert-success fade in show alert-dismissible" style="margin-top:18px;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                                <strong>Success!</strong> The file has been saved!
                                            </div>`).show().delay(2000).fadeOut(100);
                            var tabId = getfileNameForId(fileID);
                            $('#filesTab').find('a#link_' + tabId).find('.svg-inline--fa').removeClass('unsaved');
                        } else{
                            var error = '';
                            if(res.error!==undefined) error = '<br/>'+res.error;
                            $('#msg').html(`<div class="alert alert-warning fade in show alert-dismissible" style="margin-top:18px;">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                            <strong>Error!</strong> The file could not be saved! ${error}
                                        </div>`).show().delay(2000).fadeOut(100);
                        }
                    })
                    .fail(function () {
                        $('#msg').html(`<div class="alert alert-warning fade in show alert-dismissible" style="margin-top:18px;">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                            <strong>Error!</strong> The file could not be saved!
                                        </div>`).show().delay(2000).fadeOut(100);
                    });
                }
            });

            editor.session.on('change', function (delta) {
                $('#filesTab').find('li a.active').find('.svg-inline--fa').addClass('unsaved');
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
        
        function unsavedChanges() {
            var unsaved = 0;
            $('#filesTab li').each(function(){
                if ( $(this).find('a.nav-link').find('.unsaved').length ) unsaved++;
            })

            if (unsaved) {
                return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
            }
        }

        window.onbeforeunload = unsavedChanges;

        $(window).resize(function () {
            var h = Math.max($(window).height() - 0, 420);
            $('#fileTree').height(h).filter('.default').css('lineHeight', h + 'px');
        }).resize();

        var to = false;
          $('#searchString').keyup(function () {
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
              var v = $('#searchString').val();
              $('#fileTree').jstree(true).search(v);
            }, 250);
          });

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
                'plugins' : ['state','dnd','sort','types','contextmenu','unique', 'search', 'wholerow']
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
                    $('#msg').html(`<div class="alert alert-info fade in show alert-dismissible" style="margin-top:18px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        Loading..
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                                        </div>
                                    </div>`).show();
                                    
                    if(data.node.type=='default'){
                        $('#directory').val(data.node.id);
                        $('#uploader').show();
                        showFolderContents();
                        $('#tabs,#imageEditor,#info,#editImage,#docViewer').hide();
                        $('#msg').hide();
                        return;
                    }

                    var fileid = data.selected.join(':');

                    $.get('handler.php?operation=get_content&id=' + data.selected.join(':'), function (d) {
                        if(d && typeof d.type !== 'undefined') {
                            switch(d.type) {
                                case 'text':
                                case 'txt':
                                case 'md':
                                case 'ts':
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
                                case 'yaml':
                                case 'c':
                                case 'cpp':
                                case 'h':
                                case 'sql':
                                case 'pgsql':
                                case 'log':
                                case 'py':
                                case 'rb':
                                case 'pl':
                                case 'asp':
                                case 'aspx':
                                case 'java':
                                case 'htaccess':
                                case 'sh':
                                case 'php':
                                case 'blade':
                                case 'tmpl':
                                case 'twig':
                                case 'env':
                                    $('#tabs').show();
                                    $('#uploader,#imageEditor,#info,#docViewer').hide();
                                    $configureEditor(fileid, d.type, d.content);
                                    $('#metadata label[for=size] span').text(d.info.size);
                                    $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                    $('#metadata #updatePermission').val(d.info.permission);
                                    $('#metadata label[for=created] span').text(d.info.created);
                                    $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                    $('#metadata label[for=line]').show();
                                    $('#metadata label[for=contains],#metadata label[for=dimension],#editImage').hide();
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
                                    $('#uploader,#tabs,#info,#docViewer').hide();
                                    $('#imageEditor p').remove();
                                    $('#metadata label[for=dimension] span').text(d.info.width+' X '+d.info.height);
                                    // $('#metadata label[for=path] span').text(d.info.path);
                                    $('#metadata label[for=size] span').text(d.info.size);
                                    $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                    $('#metadata #updatePermission').val(d.info.permission);
                                    $('#metadata label[for=created] span').text(d.info.created);
                                    $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                    $('#metadata label[for=dimension]').show();
                                    $('#metadata label[for=contains],#metadata label[for=line]').hide();
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
                                    $('#uploader,#imageEditor,#info,#tabs').hide();
                                    $('#metadata label[for=size] span').text(d.info.size);
                                    $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                    $('#metadata #updatePermission').val(d.info.permission);
                                    $('#metadata label[for=created] span').text(d.info.created);
                                    $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                    $('#metadata label[for=contains],#metadata label[for=line],#metadata label[for=dimension],#metadata label[for=line],#editImage').hide();
                                    break;
                                default:
                                    /*$('#tabs').show();
                                    $('#uploader,#imageEditor,#info,#docViewer').hide();
                                    $configureEditor(fileid, d.type, d.content);
                                    $('#metadata label[for=size] span').text(d.info.size);
                                    $('#metadata label[for=permission] span').text(d.info.permissionFull);
                                    $('#metadata #updatePermission').val(d.info.permission);
                                    $('#metadata label[for=created] span').text(d.info.created);
                                    $('#metadata label[for=lastmodified] span').text(d.info.modified);
                                    $('#metadata label[for=line]').show();
                                    $('#metadata label[for=contains],#metadata label[for=dimension],#editImage').hide();
                                    break;*/
                                    
                                    $('#msg').html(`<div class="alert alert-warning fade in show alert-dismissible" style="margin-top:18px;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                                <strong>Warning!</strong> The file format is not supported yet!
                                            </div>`).show();
                                    break;
                            }
                            $('#msg').delay(2000).fadeOut(100);
                        }
                    });
                }
            });
        } catch(e){
            console.log(e);
        }
});