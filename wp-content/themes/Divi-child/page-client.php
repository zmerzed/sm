<?php
/*
* Template Name: Client
*/

global $current_user;
/* $userdata = get_currentuserinfo(); */
$uinfo = wp_get_current_user();

/** check if the user is logged-in **/
if( is_user_logged_in() ){

	/* $member_type = bp_get_member_type($uinfo->ID); */

	/** check if the user trying to access the page has a "client" member type **/
	if( in_array( 'client', $uinfo->roles ) ){

		require_once( get_stylesheet_directory() . '/accounts/inc/header-account.php' );
		?>

		<div class="title-welcome-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6">
						<h2>

							<?php
							$data_request = $_GET['data'];
							switch ($data_request) {
								case 'schedule':
									echo 'Schedule';
									break;

								case 'profile':
									echo 'Profile';
									break;

								case 'message':
									echo 'Message';
									break;

								case 'notes':
									echo 'Notes';
									break;

								case 'logs':
									echo 'Logs';
									break;

								case 'workout':
									echo 'Start Workout';
									break;

								default:
									echo 'dashboard';
									break;
							}

							?>

						</h2>
					</div>
					<div class="col-lg-6 col-md-6">
						<h4>Welcome back, <?php echo $uinfo->user_login; ?></h4>
					</div>
				</div>
			</div>
		</div>

		<div class="main-section">
			<div class="container">
				<div class="row">

					<div class="col-lg-2 col-md-2">
						<div class="main-navigation matchHeight">

							<h3>Menu</h3>

							<ul>
								<li><a href="/client" menu-item="dashboard">Dashboard</a></li>
								<li>
									<a href="<?php echo home_url(); ?>/client/?data=schedule" menu-item="schedule">Schedule</a>
									<ul>
										<li><a href="<?php echo home_url(); ?>/client/?data=schedule&by=weekly" menu-item="weekly">Weekly</a></li>
										<li><a href="<?php echo home_url(); ?>/client/?data=schedule&by=monthly" menu-item="monthly">Monthly</a></li>
									</ul>
								</li>
								<li>
									<a href="<?php echo home_url(); ?>/client/?data=profile" menu-item="profile">Profile</a>
									<ul>
										<li><a href="<?php echo home_url(); ?>/client/?data=profile&by=personal-info" menu-item="personal-info">Personal Info</a></li>
										<li><a href="<?php echo home_url(); ?>/client/?data=profile&by=progress-goals" menu-item="progress-goals">Progress/Goals</a></li>
									</ul>
								</li>
								<li><a href="<?php echo home_url(); ?>/client/?data=message" menu-item="message">Messages</a></li>
								<li><a href="<?php echo home_url(); ?>/client/?data=notes" menu-item="notes">Notes</a></li>
								<li><a href="<?php echo home_url(); ?>/client/?data=logs" menu-item="logs">Logs</a></li>
							</ul>

						</div>
					</div>


					<div class="col-lg-10 col-md-10">

						<?php
						if(!checkUserOrParentStatus($uinfo)){
							echo "Subscription ended, please contact admin.";
						}else{
							$data_request = $_GET['data'];
							switch ($data_request) {
								case 'schedule':

									$data_request_by = $_GET['by'];

									if( $data_request_by === 'monthly' ){
										get_template_part( 'accounts/clients/schedule-monthly', 'page' );
									}else{
										get_template_part( 'accounts/clients/schedule', 'page' );
									}

									break;

								case 'profile':

									$data_request_by = $_GET['by'];

									if( $data_request_by === 'personal-info' ){
										get_template_part( 'accounts/clients/personal-info', 'page' );
									}
									elseif( $data_request_by === 'progress-goals' ){
										get_template_part( 'accounts/clients/progress-goals', 'page' );
									}else{
										get_template_part( 'accounts/clients/profile', 'page' );
									}

									break;

								case 'message':
									get_template_part( 'accounts/clients/message', 'page' );
									break;

								case 'notes':
									get_template_part( 'accounts/clients/notes', 'page' );
									break;

								case 'logs':
									get_template_part( 'accounts/clients/logs', 'page' );
									break;

								case 'workout':
									
									get_template_part( 'accounts/clients/workout', 'page' );
									break;

								default:
									get_template_part( 'accounts/clients/dashboard', 'page' );
									break;
							}
							}

						?>

					</div>
				</div>
			</div>
		</div>


		<?php

		require_once( get_stylesheet_directory() . '/accounts/inc/footer-account.php' );

	}else{

		wp_redirect( site_url( '/' ) );
		exit();

	}

}else{

	wp_redirect( site_url( '/wp-admin' ) );
	exit();

}

?>