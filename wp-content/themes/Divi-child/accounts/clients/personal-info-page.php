<?php

	global $current_user;
	$userdata = get_currentuserinfo();
	
?>

<div class="main-content matchHeight">

	<div class="container-title">
        <h3>Personal Information</h3>
    </div>

	<div class="current-status">
		<div class="row">
			<div class="col-lg-8 col-md-8">
				<div class="personal-data-information matchHeight">
					<div class="row">
						<div class="col-lg-12">
							<h2>Steven Johnson</h2>
						</div>
						<div class="col-lg-5 col-md-5">
							<div class="featured-image">
								<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/personal-featured-image.jpg'; ?>">
							</div>
						</div>
						<div class="col-lg-7 col-md-7">
							<p>
								<label>EMAIL/LOGIN</label>
								<strong><?php echo $userdata->data->user_email; ?></strong>
							<p>
							<p>
								<label>DOB</label>
								<strong>january 24, 1978</strong>
							<p>
							<p>
								<label>GENDER</label>
								<strong>MALE</strong>
							<p>
							<p>
								<label>PHONE #</label>
								<strong>555-555-5555</strong>
							<p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4">
				<div class="personal-documents matchHeight">
					<h3>HEALTH DOCUMENTS</h3>
					<ul>
						<li><a href="#"><span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/pdf.png'; ?>"></span>health-records.pdf</a></li>
						<li><a href="#"><span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/doc.png'; ?>"></span>nutrition_plan.doc</a></li>
						<li><a href="#"><span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/xls.png'; ?>"></span>past_progress.xls</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

</div>