<?php
/*
* Template Name: Gym
*/

global $current_user;
/* $userdata = get_currentuserinfo(); */
$uinfo = wp_get_current_user();

/** check if the user is logged-in **/
if( is_user_logged_in() ){

	/* $member_type = bp_get_member_type($uinfo->ID); */
	
	/** check if the user trying to access the page has a "client" member type **/
	if( in_array( 'gym', $uinfo->roles ) ){

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

						case 'workouts':
							echo 'Workouts';
							break;

						case 'add-workouts':
							echo 'New/edit workout';
							break;

						case 'exercises':
							echo 'Exercises';
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
						<li><a href="<?php echo  home_url(); ?>/gym" menu-item="dashboard">Dashboard</a></li>
						<li>
							<a href="<?php echo  home_url(); ?>/gym/?data=schedule" menu-item="schedule">Schedule</a>
							<ul>
								<li><a href="<?php echo  home_url(); ?>/gym/?data=schedule&by=weekly" menu-item="weekly">Weekly</a></li>
								<li><a href="<?php echo  home_url(); ?>/gym/?data=schedule&by=monthly" menu-item="monthly">Monthly</a></li>
							</ul>
						</li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=profile" menu-item="profile">Profile</a></li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=message" menu-item="message">Messages</a></li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=notes" menu-item="notes">Notes</a></li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=logs" menu-item="logs">Logs</a></li>
					</ul>

					<div class="menu-divider"></div>

					<ul>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=workouts" menu-item="workouts">Workouts</a></li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=exercises" menu-item="exercises">Exercises</a></li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=trainers" menu-item="trainers">Trainers</a></li>
						<li><a href="<?php echo  home_url(); ?>/gym/?data=clients" menu-item="clients">Clients</a></li>
					</ul>

				</div>
			</div>

			<div class="col-lg-10 col-md-10">
				<?php
					$data_request = $_GET['data'];
					switch ($data_request) {
						case 'schedule':

							$data_request_by = $_GET['by'];

							if( $data_request_by === 'monthly' ){
								get_template_part( 'accounts/gym/schedule-monthly', 'page' );
							}else{
								get_template_part( 'accounts/gym/schedule', 'page' );
							}
							
							break;

						case 'profile':

							get_template_part( 'accounts/gym/profile', 'page' );

							break;
						
						case 'message':
							get_template_part( 'accounts/gym/message', 'page' );
							break;

						case 'notes':
							get_template_part( 'accounts/gym/notes', 'page' );
							break;
						
						case 'logs':
							get_template_part( 'accounts/gym/logs', 'page' );
							break;

						case 'workouts':
							get_template_part( 'accounts/gym/workouts', 'page' );
							break;

						case 'add-workouts':
							get_template_part( 'accounts/gym/add-edit-workouts', 'page' );
							break;

						case 'exercises':
							get_template_part( 'accounts/gym/exercises', 'page' );
							break;

						case 'clients':
							get_template_part( 'accounts/gym/clients', 'page' );
							break;
							
						case 'trainers':
							get_template_part( 'accounts/gym/trainers', 'page' );
							break;

						default:
							get_template_part( 'accounts/gym/dashboard', 'page' );
							break;
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