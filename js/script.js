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
            ['.com', '.in', 'Z', 'X', 'C', '&nbsp;', 'V', 'B', 'N', 'M', '.co.in'],

            // Row 5
            ['@gmail.com', '@yahoo.in', '@yahoo.co.in', '@yahoo.com', '@outlook.com', '@zoho.com', '@aim.com', '@gmx.com'],

        ];

        html += '<div id="virtual-keyboard" class="slide-up">'
            +      '<div class="header">'
            +          '<i class="fa fa-times-circle close-btn" style="color: '+ keybrdClr +'"></i>'
            +      '</div>'
            +      '<div class="content">';

        for (var i in keys) {
            var row = keys[i];
            html += '<div class="row">';

            for (var k in row) {

                html += '<div class="key" style="background: '+ keybrdClr +'" id="key-'+ (row[k] == '&nbsp;' ? 'space' : row[k]) +'">'+ row[k] +'</div>';
            }

            html += '</div>';
        }

        html += '</div></div>';

        $('body').append(html);

        $('#virtual-keyboard .key').on('click', function(){
            var id = $(this).attr('id')
            ,   s  = id.split('-');

            $('input.active').val($('input.active').val()+(s[1] == 'space' ? ' ' : s[1]));

            if ($('#inp-clr-btn').length == 0){
                $('input.active').after('<i class="fa fa-times-circle" id="inp-clr-btn" style="color: '+ keybrdClr +'"></i>');
            }

        });

        $('#virtual-keyboard .close-btn').on('click', function(){
            virtualKeyboard(false);                    
            $('input.active').removeClass('active');
        });

    }

    if (toShow) {  
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

function share(type){
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

        $.post( siteUrl + 'ajax/common.php', data, function(results){
            // Success
            results = JSON.parse(results);

            if (typeof results['message'] != 'undefined') {
                alert('Message: '+ results['message']);
            } 

            if (type == 'twitter') {
                var win = window.open('https://twitter.com/intent/tweet?url='+ siteUrl + results['metaPath'] +'&text='+ results['title'] +'&hashtags='+ results['tags'] +'&via='+ results['via'],  '', 'menubar=no, location=no, resizable=no, scrollbars=no, status=no');
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

            default :

                $('#load-frame').remove();
                $('#preview-page').append('<iframe id="load-frame" class="frame" frameborder="0" allowfullscreen ></iframe>');


                $iframe = $('#load-frame').contents().find('body')[0];   
                $iframe.style.overflow = 'hidden';

                if( type == 'print' ) {
                    // Print
                    $iframe.innerHTML = '<img src="'+ imgURL +'" style="width: 100%; height: 100%" onload="window.print();"/>';

                }

            break;

        }
    }
    
}

function preview(){
    //$('#preview-page .frame-wrapper').css({'background': 'url('+ siteUrl +'images/templates/default.png) no-repeat center;'});
    $('#share-inp-block, #share-inp-block .inp').hide();

    var ratio = $video.videoWidth / $video.videoHeight; // 640 x 480 = 1.333333333
    var w = $video.videoWidth - 35;                     
    var h = parseInt(w / ratio, 10);                    
    $canvas.width = w+6;
    $canvas.height = h;  

    //$video.pause();
    ctx.drawImage($video, 0, 0, w, h);
    streamObj.getVideoTracks()[0].stop();

    $('#share-pane').addClass('pop');
    virtualKeyboard();
    setTimeout(function(){
        $('#share-pane').removeClass('pop-down');

    }, 1500);
        
    timer = window.setTimeout(function(){
        if( $('#share-pane .icon.active').length == 0 ) {
            show('home');
        }
    }, idleTimeout*1000);
}

function show(page){
    $('.page.active').fadeOut('slow').removeClass('active');

	switch(page) {

		default:
		case 'home' :

			// Home 
			page = 'home';
            $('#share-pane .icon.active').removeClass('active');
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
		
	}

	$('#'+ page +'-page').fadeIn('slow', function(){
		$(this).addClass('active');
	});

}

function resetShare(){
    $('#load-frame').remove();
    $('#share-pane .icon.active').removeClass('active');
    $('#preview-page').removeAttr('data-submit');
    $('#share-inp-block, #share-inp-block .inp').hide();
    $('.email, .contact-no').val('');
}


function init(){
    
    $canvas = document.getElementById('preview-frame');
    ctx = $canvas.getContext('2d');
}

window.onload = init;

$(document).on('ready', function(){
        show('init'); //# 

        $(document).on('click', '#inp-clr-btn', function(){
            $('input.active').val('').focus();
            $(this).remove();
        });

        $('input').on('focus', function(){
            $('input.active').removeClass('active');
            $(this).addClass('active');
            virtualKeyboard(true);                    
        });

        $('#start-btn').on('click', function(){
    		show('prepare');
    	});
        
        $('#share-pane .icon, #share-sbmt-btn').on('click', function(){
            var id = $(this).attr('id');

            if (id == 'share-sbmt-btn') {
                id = $('#share-pane .icon.active').attr('id');

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