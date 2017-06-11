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
navigator.getUserMedia = (
    navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia
);


function initTimer(){

	window.clearInterval(timer);
    $('#timer').html('');//.fadeIn('slow');

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

        $.post( siteUrl + 'ajax/common.php', data, function(results){
            // Success
            results = JSON.parse(results);

            if (type == 'twitter') {
                var win = window.open('https://twitter.com/intent/tweet?url='+ siteUrl + results['metaPath'] +'&text='+ results['title'] +'&hashtags='+ results['tags'] +'&via='+ results['via'],  '', 'menubar=no, location=no, resizable=no, scrollbars=no, status=no');
                //var win = window.open('https://twitter.com/intent/tweet?url=http%3A%2F%2Fapp.sourceplate.com%2Fworkout%2F&text='+ results['title'] + '&hashtags='+ results['tags'] +'&via='+ results['via'],  '', 'menubar=no, location=no, resizable=no, scrollbars=no, status=no');
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
    $('#preview-page .frame-wrapper').css({'background': 'url('+ siteUrl +'images/templates/default.png) no-repeat center;'});
    $('#share-inp-block, #share-inp-block .inp').hide();
    ctx.drawImage($video, 0, 0, $canvas.width, $canvas.height);
    streamObj.getVideoTracks()[0].stop();

    $('#share-pane').addClass('pop');
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
}


function init(){
    
    $canvas = document.getElementById('preview-frame');
    ctx = $canvas.getContext('2d');
}

window.onload = init;

$(document).on('ready', function(){
        show('home'); //#
        
        $('#start-btn').on('click', function(){
    		show('prepare');
    	});
        
        $('#share-pane .icon').on('click', function(){
            var id = $(this).attr('id');
            resetShare();
            $(this).addClass('active');
            

            //$canvas.style.opacity = '0';
            
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