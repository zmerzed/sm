<?php
	global $current_user;
	$userdata = get_currentuserinfo();

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['workoutForm'])) {
		workOutAdd(array_merge($_POST, ['workout_trainer_ID' => $current_user->ID]));
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateWorkoutForm'])) {
		workOutUpdate($_POST);
	}
?>

<div class="main-content matchHeight">

	<div class="trainer-add-workout">
		<a href="<?php echo home_url(); ?>/trainer/?data=add-workouts">+ New Workout</a>
	</div>

	<ul class="workout-lists trainer-workouts-lists">
		<?php foreach(workOutUserList($current_user->ID) as $workout) {?>
		<li>
			<?php $url = "/trainer/?data=add-workouts&workout=" . $workout->workout_ID ?>
			<div class="workout-wrapper">
				<span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout.png'; ?>"></span>
				<label><?php echo $workout->workout_name ?></label>
				<div class="workout-controls">
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/members-icon.png'; ?>"></a></span>
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/record-icon.png'; ?>"></a></span>
					<span><a href="<?php echo $url ?>"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/edit-icon.png'; ?>"></a></span>
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/delete-icon.png'; ?>"></a></span>
				</div>
			</div>
		</li>
		<?php } ?>
	</ul>
</div>