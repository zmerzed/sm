<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<style>
    .app{
        width: 100%;
        position: relative;
    }

    .app #start-camera{
        display: none;
        border-radius: 3px;
        max-width: 400px;
        color: #fff;
        background-color: #448AFF;
        text-decoration: none;
        padding: 15px;
        opacity: 0.8;
        margin: 50px auto;
        text-align: center;
    }

    .app video#camera-stream{
        display: none;
        width: 100%;
    }

    .app img#snap{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        display: none;
    }

    .app #error-message{
        width: 100%;
        background-color: #ccc;
        color: #9b9b9b;
        font-size: 28px;
        padding: 200px 100px;
        text-align: center;
        display: none;
    }

    .app .controls{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 20;

        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding: 30px;
        display: none;
    }

    .app .controls a{
        border-radius: 50%;
        color: #fff;
        background-color: #111;
        text-decoration: none;
        padding: 15px;
        line-height: 0;
        opacity: 0.7;
        outline: none;
        -webkit-tap-highlight-color: transparent;
    }

    .app .controls a:hover{
        opacity: 1;
    }

    .app .controls a.disabled{
        background-color: #555;
        opacity: 0.5;
        cursor: default;
        pointer-events: none;
    }

    .app .controls a.disabled:hover{
        opacity: 0.5;
    }

    .app .controls a i{
        font-size: 18px;
    }

    .app .controls #take-photo i{
        font-size: 32px;
    }

    .app canvas{
        display: none;
    }



    .app video#camera-stream.visible,
    .app img#snap.visible,
    .app #error-message.visible
    {
        display: block;
    }

    .app .controls.visible{
        display: flex;
    }


    @media(max-width: 1000px){
        .container{
            margin: 40px;
        }

        .app #start-camera.visible{
            display: block;
        }

        .app .controls a i{
            font-size: 16px;
        }

        .app .controls #take-photo i{
            font-size: 24px;
        }
    }

    @media(max-width: 600px){
        .container{
            margin: 10px;
        }

        .app #error-message{
            padding: 80px 50px;
            font-size: 18px;
        }

        .app .controls a i{
            font-size: 12px;
        }

        .app .controls #take-photo i{
            font-size: 18px;
        }
    }

</style>

<div ng-app="smApp" ng-controller="profileController">
	<div class="main-content matchHeight">
		<div class="container-title">
			<h3>Progress / Goals</h3>
		</div>
		<div class="current-status">
			<div class="row">
				<div class="col-lg-8 col-md-8">
					<div class="current-goal">
						<h3>Goal</h3>
						<table style="width: 100%;">
							<tr>
								<th></th>
								<th>Start #s</th>
								<th>Goal #s</th>
							</tr>
							<tr>
								<td><label>Weight</label></td>
								<td><input type="text" value="167 lbs" /></td>
								<td><input type="text" value="180 lbs" /></td>
							</tr>
							<tr>
								<td><label>Body Fat (%)</label></td>
								<td><input type="text" value="26%" /></td>
								<td><input type="text" value="26%" /></td>
							</tr>
							<tr>
								<td><label>Waist</label></td>
								<td><input type="text" value="34 in" /></td>
								<td><input type="text" value="32 in" /></td>
							</tr>
							<tr>
								<td><label>chest</label></td>
								<td><input type="text" value="36 in" /></td>
								<td><input type="text" value="38 in" /></td>
							</tr>
							<tr>
								<td><label>arms</label></td>
								<td><input type="text" value="34 in" /></td>
								<td><input type="text" value="32 in" /></td>
							</tr>
							<tr>
								<td><label>forearms</label></td>
								<td><input type="text" value="34 in" /></td>
								<td><input type="text" value="32 in" /></td>
							</tr>
							<tr>
								<td><label>shoulders</label></td>
								<td><input type="text" value="34 in" /></td>
								<td><input type="text" value="32in" /></td>
							</tr>
							<tr>
								<td><label>hips</label></td>
								<td><input type="text" value="34 in" /></td>
								<td><input type="text" value="32 in" /></td>
							</tr>
							<tr>
								<td><label>thighs</label></td>
								<td><input type="text" value="24 in" /></td>
								<td><input type="text" value="28 in" /></td>
							</tr>
							<tr>
								<td><label>calves</label></td>
								<td><input type="text" value="16 in" /></td>
								<td><input type="text" value="18 in" /></td>
							</tr>
							<tr>
								<td><label>neck</label></td>
								<td><input type="text" value="12 in" /></td>
								<td><input type="text" value="14 in" /></td>
							</tr>
							<tr>
								<td><label>height</label></td>
								<td><input type="text" value="5 ft 10 in" /></td>
								<td><input type="text" value="5 ft 10 in" /></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-lg-4 col-md-4">
					<div class="current-goal">
						<h3>Today</h3>
						<table style="width: 100%;">
							<tr>
								<th>Current #s</th>
							</tr>
							<tr>
								<td><input type="text" value="34 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="26%" /></td>
							</tr>
							<tr>
								<td><input type="text" value="34 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="36 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="34 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="34 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="34 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="34 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="24 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="16 in%" /></td>
							</tr>
							<tr>
								<td><input type="text" value="12 in" /></td>
							</tr>
							<tr>
								<td><input type="text" value="5 ft 10 in" /></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-lg-12 col-md-12">
					<div style="text-align:center;padding:10px 0;">
						<button class="red-btn">Update</button>
					</div>
				</div>
				<div class="col-lg-12 col-md-12">
					<div class="progress-photos">
						<h3>progress photos</h3>

						<ul class="progress-slider-photos">
							<li>
								<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/progress-01.png'; ?>">
								<span>1/12/2017</span>
							</li>
							<li>
								<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/progress-02.png'; ?>">
								<span>1/12/2017</span>
							</li>
							<li>
								<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/progress-03.png'; ?>">
								<span>1/12/2017</span>
							</li>
							<li>
								<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/progress-04.png'; ?>">
								<span>1/12/2017</span>
							</li>
							<li>
								<div class="browser-upload-image" data-toggle="modal" data-target="#myModal">
									<label class="btn btn-default btn-file">
										<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/progress-btn-plus.png'; ?>">
									</label>
								</div>
								<span>Take New</span>
							</li>
						</ul>
						<div style="text-align:center;padding:10px 0;">
							<button class="red-btn">Update</button>
						</div>
					</div>

					<div class="progress-notes">
						<p class="label">In details, explain what are your trying to accomplish</p>
						<textarea class="progress-iframe" placeholder="EXAMPLE: FAT LOSS, SPORT PREPARATION, FLEXABILITY"></textarea>
					</div>
					<div style="text-align:center;padding:10px 0;">
						<button class="red-btn">Update</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<div class="container">
						<div class="app">
							<a href="#" id="start-camera" class="visible">Touch here to start the app.</a>
							<video id="camera-stream"></video>
							<img id="snap">

							<p id="error-message"></p>

							<div class="controls">
								<a href="#" id="delete-photo" title="Delete Photo" class="disabled"><i class="material-icons">delete</i></a>
								<a href="#" id="take-photo" title="Take Photo"><i class="material-icons">camera_alt</i></a>
								<a href="#" id="download-photo" download="selfie.png" title="Save Photo" class="disabled"><i class="material-icons">file_download</i></a>
							</div>
							<!-- Hidden canvas element. Used for taking snapshot of video. -->
							<canvas></canvas>
						</div>
						<button class="btn btn-default">CANCEL</button>
						<button class="btn btn-default" ng-click="upload()">UPLOAD</button>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    // References to all the element we will need.
    var video = document.querySelector('#camera-stream'),
        image = document.querySelector('#snap'),
        start_camera = document.querySelector('#start-camera'),
        controls = document.querySelector('.controls'),
        take_photo_btn = document.querySelector('#take-photo'),
        delete_photo_btn = document.querySelector('#delete-photo'),
        download_photo_btn = document.querySelector('#download-photo'),
        error_message = document.querySelector('#error-message'),
		CAMERA_DATA_URL = '',
        USER_ID = '<?php echo get_current_user_id(); ?>';
    // The getUserMedia interface is used for handling camera input.
    // Some browsers need a prefix so here we're covering all the options
    navigator.getMedia = ( navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia);


    if(!navigator.getMedia){
        displayErrorMessage("Your browser doesn't have support for the navigator.getUserMedia interface.");
    }
    else{



    }

    // Mobile browsers cannot play video without user input,
    // so here we're using a button to start it manually.
    start_camera.addEventListener("click", function(e){

        e.preventDefault();

        // Start video playback manually.
        video.play();
        showVideo();

    });

    take_photo_btn.addEventListener("click", function(e){

        e.preventDefault();

        var snap = takeSnapshot();

        // Show image.
        image.setAttribute('src', snap);
        image.classList.add("visible");

        // Enable delete and save buttons
        delete_photo_btn.classList.remove("disabled");
        download_photo_btn.classList.remove("disabled");

        // Set the href attribute of the download button to the snap url.
        download_photo_btn.href = snap;

        // Pause video playback of stream.
        video.pause();

    });

    delete_photo_btn.addEventListener("click", function(e){

        e.preventDefault();

        // Hide image.
        image.setAttribute('src', "");
        image.classList.remove("visible");

        // Disable delete and save buttons
        delete_photo_btn.classList.add("disabled");
        download_photo_btn.classList.add("disabled");

        // Resume playback of stream.
        video.play();

    });

    function showVideo(){
        // Display the video stream and the controls.

        hideUI();
        video.classList.add("visible");
        controls.classList.add("visible");
    }

    function takeSnapshot(){
        // Here we're using a trick that involves a hidden canvas element.

        var hidden_canvas = document.querySelector('canvas'),
            context = hidden_canvas.getContext('2d');

        var width = video.videoWidth,
            height = video.videoHeight;

        if (width && height) {

            // Setup a canvas with the same dimensions as the video.
            hidden_canvas.width = width;
            hidden_canvas.height = height;

            // Make a copy of the current frame in the video on the canvas.
            context.drawImage(video, 0, 0, width, height);

            // Turn the canvas image into a dataURL that can be used as a src for our photo.

			CAMERA_DATA_URL = hidden_canvas.toDataURL('image/png');
            return hidden_canvas.toDataURL('image/png');
        }
    }

    function displayErrorMessage(error_msg, error){
		error = error || "";
        if(error){
            console.log(error);
        }

        error_message.innerText = error_msg;

        hideUI();
        error_message.classList.add("visible");
    }

    function hideUI(){
        // Helper function for clearing the app UI.

        controls.classList.remove("visible");
        start_camera.classList.remove("visible");
        video.classList.remove("visible");
        snap.classList.remove("visible");
        error_message.classList.remove("visible");
    }
	var localStream;

	setTimeout(function(){
		$('#myModal').on('hidden.bs.modal', function (e) {
			console.log('close camera...');
			video.pause();
			video.src = "";
			console.log(localStream)
			localStream.getTracks()[0].stop();
			console.log("Vid off");
		});

		$('#myModal').on('show.bs.modal', function (e) {
			console.log('open camera...');
			// Request the camera.
			navigator.getMedia(
				{
					video: true
				},
				// Success Callback
				function(stream){

					// Create an object URL for the video stream and
					// set it as src of our HTLM video element.
					video.src = window.URL.createObjectURL(stream);
					localStream = stream;
					// Play the video element to start the stream.
					video.play();
					video.onplay = function() {
						showVideo();
					};

				},
				// Error Callback
				function(err){
					displayErrorMessage("There was an error with accessing the camera stream: " + err.name, err);
				}
			);
		});

	}, 500);

</script>
<script>var ROOTURL = '<?php echo get_site_url(); ?>';</script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() .'/js/angular/app.js'; ?>"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() .'/js/angular/profileController.js'; ?>"></script>