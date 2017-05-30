/*
 * script.js
 */

var timer = null 
,   streamObj = null
,   ctx = null
,   $video = null
,   $canvas = null;    

timerOut = typeof timerOut == 'undefined' ? 3 : timerOut;
navigator.getUserMedia = (
    navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia
);


function initTimer(){

	window.clearInterval(timer);
            $('#timer').html(timerOut);//.fadeIn('slow');
        
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

function preview(){
    ctx.drawImage($video, 0, 0, $canvas.width, $canvas.height);
    
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


	page = typeof page == 'undefined' ? 'home' : page;
	$('.page.active').fadeOut('slow').removeClass('active');

	//console.log('Showing: '+page);
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
                        streamObj.getVideoTracks()[0].stop();
                        
                break;
		
	}

	$('#'+ page +'-page').fadeIn('slow', function(){
		$(this).addClass('active');
	});

}

function init(){
    
    $canvas = document.getElementById('preview-frame');
    ctx = $canvas.getContext('2d');
}

window.onload = init;

$(document).on('ready', function(){
        show('home');
        
        $('#start-btn').on('click', function(){
		show('prepare');
	});
        
        $('#share-pane .icon').on('click', function(){
            var id = $(this).attr('id');
            $('#share-pane .icon.active').removeClass('active');
            $(this).addClass('active');
            
            switch(id) {
                
                case 'share-retake-btn' :
                    
                    show('prepare');
                break;    
                
                default :
                    show('home');
                break;    
                
            }
            
        });
        
        
});