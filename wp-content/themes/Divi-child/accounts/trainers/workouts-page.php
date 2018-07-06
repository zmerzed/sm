<?php
	global $current_user;
	$userdata = get_currentuserinfo();

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['workoutForm'])) {
		workOutAdd(array_merge($_POST, ['workout_trainer_ID' => $current_user->ID]));
	}

	if (isset($_GET['delete'])) {
		workOutDelete($_GET['delete']);
		wp_redirect('/trainer/?data=workouts', 302);
	}
?>

<div class="main-content matchHeight">

	<div class="trainer-add-workout">
		<a href="<?php echo home_url(); ?>/trainer/?data=add-workouts">+ New Workout</a>
	</div>

	<ul class="workout-lists trainer-workouts-lists">
		<?php foreach(workOutUserList($current_user->ID) as $workout) {?>
		<li class="workout-list-item">
			<?php $url = "/trainer/?data=add-workouts&workout=" . $workout->workout_ID ?>
			<div class="workout-wrapper">
				<span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout.png'; ?>"></span>
				<label><?php echo $workout->workout_name ?></label>
				<div class="workout-controls">
					<span><a href="javacsript:void(0);" onclick="toggleMembers(this)"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/members-icon.png'; ?>"></a></span>
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/record-icon.png'; ?>"></a></span>
					<span><a href="<?php echo $url ?>"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/edit-icon.png'; ?>"></a></span>
					<span><a href="/trainer/?data=workouts&delete=<?php echo $workout->workout_ID; ?>"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/delete-icon.png'; ?>"></a></span>
				</div>
			</div>
			<div class="list-of-clients-in-this-workout" style="display:none;">
				<ul>
					<?php
					$workoutClientListArr = workoutClientsList($workout->workout_ID);
					if(!empty($workoutClientListArr)):
						foreach($workoutClientListArr as $workout_client):
							$client_info = get_userdata($workout_client->workout_clientID);
					?>
						<li><a href="<?php echo home_url(); ?>/trainer/?data=workout&dayId=<?php echo $workout_client->workout_client_dayID; ?>&workoutId=<?php echo $workout->workout_ID; ?>&workout_client_id=<?php echo $workout_client->workout_clientID;  ?>"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-play.png'; ?>" /></a> <span><?php echo $client_info->user_nicename; ?></span></li>

					<?php endforeach;
						else:
					?>
						<li>No Client</li>
					<?php endif; ?>
				</ul>
			</div>
		</li>
		<?php } ?>
	</ul>
</div>
<!-- <script src='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/jquery-3.3.1.min.js';?>'></script> -->
<script>
	function toggleMembers(a){
		$('.list-of-clients-in-this-workout').each(function(){
			var toggleTarg = $(a).closest('.workout-list-item').find('.list-of-clients-in-this-workout');
			if($(this).is(toggleTarg)){
				$(toggleTarg).slideToggle();
			}else{
				$(this).hide();
			}
		});
	}
</script>