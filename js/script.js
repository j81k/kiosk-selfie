/*
 * script.js
 */

var timer = null 
,   streamObj = null
,   ctx = null
,   $video = null
,   $canvas = null
,   $iframe = null; 

timerOut = typeof timerOut == 'undefined' ? 3 : timerOut;
keybrdClr = typeof keybrdClr == 'undefined' || keybrdClr == '' ? '#333' : keybrdClr; 

navigator.getUserMedia = (
    navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia
);

function showPopup($dialog, toShow)
{
    $dialog = typeof $dialog == 'undefined' ? $('#popup') : $dialog;    
    if (toShow == false) {
        // Hide
        $dialog.slideUp('slow');
        show('home');
    } else {
        $('#virtual-keyboard').removeClass('slide-up');
        $dialog.slideDown('slow');
    }
}

function getCaret(el) 
{
    if (el.selectionStart) {
        return el.selectionStart;
    } else if (document.selection) {
        el.focus();

        var r = document.selection.createRange();
        if (r == null) {
            return 0;
        }

        var re = el.createTextRange(),
            rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint('EndToStart', re);

        return rc.text.length;
    }
    return 0;
}

function resetCursor(txtElement, currentPos) 
{ 
    if (txtElement.setSelectionRange) { 
        txtElement.focus(); 
        txtElement.setSelectionRange(currentPos, currentPos); 
    } else if (txtElement.createTextRange) { 
        var range = txtElement.createTextRange();  
        range.moveStart('character', currentPos); 
        range.select(); 
    } 
}

function hasClass(ele, cls) 
{
    return (' ' + ele.className + ' ' ).indexOf(' ' + cls + ' ') > -1;
}

function virtualKeyboard(toShow)
{
    
    if ($('#virtual-keyboard').length == 0) {
      
        var html = '' 
        ,   keys = [
            // Row 1
            ['@', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.'],

            // Row 2
            ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],

            // Row 3
            ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],

            // Row 4
            ['.com', '.in', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '.co.in'], // '&nbsp;',

            // Row 5
            ['@gmail.com', '@yahoo.in', '@yahoo.co.in', '@yahoo.com', '@outlook.com', '@zoho.com', '@aim.com', '@gmx.com'],

        ];

        html += '<div id="virtual-keyboard" class="slide-up easy-up">'
            +      '<div class="header">'
            +          '<i class="fa fa-angle-double-left backspace-btn" style="background:'+ keybrdClr +';"></i>'
            +          '<i class="fa fa-times-circle close-btn" ></i>'
            +      '</div>'
            +      '<div class="content">';

        for (var i in keys) {
            var row = keys[i];
            html += '<div class="row">';

            for (var k in row) {

                html += '<div class="key '+ (isNaN(row[k]) ? (row[k][0] == '@' ? 'mail-char' : (row[k] == '.' ? 'dot' : 'char')) : 'digit') +'" style="background: '+ keybrdClr +'" id="key-'+ (row[k] == '&nbsp;' ? 'space' : row[k]) +'">'+ row[k] +'</div>';
            }

            html += '</div>';
        }

        html += '</div></div>';

        $('body').append(html);

        $('#virtual-keyboard .key').on('click', function()
        {
            var id = $(this).attr('id')
            ,   s  = id.split('-')
            ,   char = s[1] == 'space' ? ' ' : s[1];

            var inps = document.getElementsByTagName('input')
            ,   _inp = null;

            for (i = 0; i < inps.length; i++) {
                if (hasClass(inps[i], 'active')) {
                    _inp = inps[i];
                }
            }

            if (_inp != null) {
                
                var currentPos = getCaret(_inp)
                ,   value = $(_inp).val();

                value = value.substr(0, currentPos) + char + value.substr(currentPos, value.length);
                if ($(_inp).hasClass('contact-no')) {
                    value = value.substring(value.length-10, 11);

                    if (parseInt(value) == 0) {
                        value = '';
                    }
                }

                $(_inp).val(value);
                resetCursor(_inp, currentPos+1); 
            }    

        });

        $('#virtual-keyboard .close-btn').on('click', function()
        {
            virtualKeyboard(false);                    
            //$('input.active').removeClass('active');
        });

        $('#virtual-keyboard .backspace-btn').on('click', function()
        {
            
            var inps = document.getElementsByTagName('input')
            ,   _inp = null;

            for (i = 0; i < inps.length; i++) {
                if (hasClass(inps[i], 'active')) {
                    _inp = inps[i];
                }
            }

            if (_inp != null) {
                
                var currentPos = getCaret(_inp)
                ,   text = $(_inp).val();

                text = text.substr(0, currentPos-1) + text.substr(currentPos, text.length);
                $(_inp).val(text);
                
                resetCursor(_inp, currentPos-1);

            }
        
        });

    }

    if (toShow) { 
        if ($('input.active').hasClass('contact-no')) {
            $('#virtual-keyboard').addClass('minimal');
            $('#virtual-keyboard .key').not('.key.digit').hide();
        }else {
            $('#virtual-keyboard').removeClass('minimal');
            $('#virtual-keyboard .key').show();
        }

        $('#virtual-keyboard').addClass('slide-up');

    }else {
        $('#virtual-keyboard').removeClass('slide-up');
    }

}


function initTimer(){

    window.clearInterval(timer);
    $('#timer').html('');

    var i = timerOut;
    timer = window.setInterval(function(){
        if( i <= 0 ) {
            window.clearInterval(timer);
            
            //$('#timer').fadeOut('slow', function(){
                $('#init-page').hide();
                show('preview');
            //});
        
        }else {
            $('#timer').html(i);
        }
        --i;

    }, 1000);


}

function openCamera(){
    
    if( navigator.getUserMedia ) {
    
        /*var video = document.getElementById('video-frame');
        video.width = window.innerWidth;
        video.height = window.innerHeight;*/
        
        navigator.getUserMedia({
                    video: true,
                    audio: false
                },
                
                // Success
                function(localMediaStream){
                    streamObj = localMediaStream;
                    $video = document.querySelector('video');
                    $video.src = window.URL.createObjectURL(streamObj);
                    
                    initTimer();    
                },
                
                // Error
                function(err){
                    alert('Oops: Error is occured. ' + err);
                }
                
        );
        
        
    }else {
        alert('Error: Video is not supported!');
        show('home');
    }
    
}

function showThankPopup()
{
    showPopup();
    setTimeout(function(){
        showPopup($('#popup'), false);
        show('home');
    }, 4000);
}

function share(type)
{
    var isSubmit = $('#preview-page').attr('data-submit') ? true : false
    ,   imgURL = $canvas.toDataURL('image/png');

    if( isSubmit || type == 'facebook' || type == 'twitter' ) {
        // Send Ajax
        $('#preview-page').removeAttr('data-submit');
    
        var data = {
            'action' : type,
            'data'   : {
                'img'   : imgURL
            }   
        }

        if (type == 'mail') {
            var email = $('#share-inp-block .email').val() || "";

            if (email == '') {
                alert('Please enter the email address!');
                return false;
            }

            data.data.emailTo = email;

        }else if(type == "sms") {
            var contactNo = $('#share-inp-block .contact-no').val() || "";

            if (contactNo == '') {
                alert('Please enter the mobile number!');
                return false;
            }

            data.data.contactNo = contactNo;
        }

        var appUrl = siteUrl;
        if(typeof altServer != 'undefined' && altServer != '') {
            //ajaxUrl = altServer;
            if (type == 'facebook' || type == 'twitter' || type == 'mail') {
                showPopup($('#loader-popup'));    
            }
            
            appUrl = altServer.substring(0, altServer.lastIndexOf('/')).replace('alt-server', '')+'/';
        }

        data.appUrl = appUrl;


        $.post( siteUrl + 'ajax/common.php', data, function(results){
            // Success
            showPopup($('#loader-popup'), false);
            
            results = JSON.parse(results);

            /*if (results != '' && typeof results['message'] != 'undefined') {
                alert('Message: '+ results['message']);
            }*/ 

            var newTab = '';
            if (type == 'twitter') {
                newTab = 'https://twitter.com/intent/tweet?url='+ results['tinyPath'] +'&text='+ results['title'] +'&hashtags='+ results['tags'] +'&via='+ results['via'];
            } else if (type == 'facebook') {
                newTab = 'http://www.facebook.com/sharer.php?u=' + results['tinyPath'] + '&t=' + encodeURIComponent(results['title']);
            } else {
                showThankPopup();
            }

            if (newTab != '') {
                // Open New/Popup Tab
                //var win = window.open(newTab,  '', 'menubar=no, location=no, resizable=no, scrollbars=no, status=no, Fullscreen=yes');
                //window.onunload = showThankPopup;
                loadFrame(type, newTab);
            }

        });

    }else {

        // Show Popup to get user input
        $('#preview-page').attr('data-submit', type);
        
        switch(type) {
            
            case 'mail' :
                $('#share-inp-block, #share-inp-block .email').fadeIn('slow', function(){
                    $(this).focus();
                });
            break;

            case 'sms' :
                $('#share-inp-block, #share-inp-block .contact-no').fadeIn('slow', function(){
                    $(this).focus();
                });

            break;

            case 'print':

                loadFrame(type);

            break;

            default:
                alert('Oops: Something went wrong.. Please try again!');
            break;

        }
    }
    
}

function loadFrame(type, url) 
{
    $('.load-frame').remove();
    

    if( type == 'print' ) {
        // Print

        $('#preview-page').append('<iframe id="load-frame" class="frame load-frame" frameborder="0" allowfullscreen ></iframe>');
        $iframe = $('#load-frame').contents().find('body')[0];   
        $iframe.style.overflow = 'hidden';

        $iframe.innerHTML = '<img src="'+ imgURL +'" style="width: 100%;" onload="window.print();"/>';
        
        showPopup();
        $('#load-frame').hide();
        setTimeout(function(){
            showPopup($('#popup'), false);
            show('home');
        }, 4000);
        
    } else if(type == 'website') {

        $('#website-page .content').append('<iframe class="load-frame frame" src="'+ url +'" frameborder="0" allowfullscreen style="height: '+ ($(window).height()-100) +'px;" ></iframe>');
        $iframe = $('#website-page .load-frame').contents().find('body')[0];
        //$('#website-page .load-frame').onload = showPopup($('#loading-popup'), false);

    } else {
        // Load URL
        $('body').append('<div ><iframe target="_top" class="load-frame" src="'+ url +'"></iframe></div>');

        //$('#load-frame').attr('src', url.replace("watch?v=", "v/"));
        //window.location.href = url;

    }
}

function preview(){
    $('#share-inp-block, #share-inp-block .inp').hide();

    // Template 
    $('#preview-frame').addClass('tmpl-1');

    var ratio = $video.videoWidth / $video.videoHeight; // 640 x 480 = 1.333333333
    var w = $video.videoWidth - 35;                     
    var h = parseInt(w / ratio, 10);                    
    //#$canvas.width = w+6;
    $canvas.width = w+6;
    $canvas.height = h;

    ctx.canvas.width = w;
    ctx.canvas.height = h;  

    //$video.pause();
    ctx.drawImage($video, 0, 0, w, h);
    streamObj.getVideoTracks()[0].stop();
    virtualKeyboard();

    setTimeout(function(){
        $('#dock-container').addClass('pop');
    }, 1500);
    
    timer = window.setTimeout(function(){
        if( $('#dock-container .icon.active').length == 0 ) {
            show('home');
        }
    }, idleTimeout*1000);
}

function show(page){
    $('.page.active').fadeOut('slow').removeClass('active');
    $('#dock-container').removeClass('pop');

    switch(page) {

        default:
        case 'home' :

            // Home 
            page = 'home';
            resetShare();
        break;
            
        case 'prepare' :

            // Prepare to init
            setTimeout(function(){
                show('init');
            }, 4000);   
        break;  

        case 'init' :
            
            // Init Snapshot
            openCamera();
        break;
            
        case 'preview' :
            
            preview();
        break;

        case 'website':
                //showPopup($('#loading-popup'));
                loadFrame(page, webLink);
        break;
        
    }

    $('body').attr('id', 'body-' + page);
    $('#'+ page +'-page').fadeIn('slow', function(){
        $(this).addClass('active');
    });

}

function resetShare(){
    $('#load-frame').remove();
    $('#dock-container .icon.active, input.active').removeClass('active');
    $('#preview-page').removeAttr('data-submit');
    $('#share-inp-block, #share-inp-block .inp').hide();
    $('.email, .contact-no').val('');

    $('#virtual-keyboard .close-btn').trigger('click');
}


function init(){
    
    $canvas = document.getElementById('preview-frame');
    ctx = $canvas.getContext('2d');

    $('.frame-full').css({'min-height': window.innerHeight-200});
}

window.onload = init;

$(document).on('ready', function(){
    show('home'); //#

    //loadFrame('facebook', 'http://naturals.in/');



    $('.dialog .close-btn').on('click', function(){
        showPopup($(this).parent().closest('.dialog'), false);
    });

    $(document).on('click', '#inp-clr-btn', function(){
        if ($('input.active').val() != '') {
            $('input.active').val('').focus();    
        }else {
            $('#share-inp-block, #share-inp-block .inp').hide();
            $('#virtual-keyboard .close-btn').trigger('click');    
        }
        
    });

    $('input').on('focus', function(){
        $('input.active').removeClass('active');
        $(this).addClass('active');
        virtualKeyboard(true);                    
    });

    $('#start-btn').on('click', function(){
        show('prepare');
    });

    $('#web-start-btn').on('click', function(){
        show('website');    
    });
    
    $('#dock-container .icon, #share-sbmt-btn, #preview-ctrls > div').on('click', function(){
        var id = $(this).attr('id');

        if (id == 'share-sbmt-btn') {
            id = $('#dock-container .icon.active').attr('id');

            $('#preview-page').attr('data-submit', true);
        }else {
            resetShare();
            $(this).addClass('active');
        }

        switch(id) {
            
            case 'share-retake-btn' :
                
                show('prepare');
            break;  

            case 'share-print-btn' :

                share('print');
            break;

            case 'share-mail-btn' :

                share('mail');
            break;

            case 'share-sms-btn' :

                share('sms');
            break;  

            case 'share-fb-btn' :

                share('facebook');
            break;

            case 'share-tw-btn' :

                share('twitter');
            break;
            
            default :

                show('home');
            break;    
            
        }
        
    });
        
});