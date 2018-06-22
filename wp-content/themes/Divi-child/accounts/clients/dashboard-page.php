<?php
	$current_user = wp_get_current_user();
	$clientWorkouts = workoutGetClientWorkouts($current_user->ID);

?>
<div class="main-content matchHeight">

	<div class="container-title">
        <h3>Today's Workout</h3>
    </div>

	<ul class="workout-lists">
		<?php foreach ($clientWorkouts['todayWorkouts'] as $workout) { ?>

		<li>
			<h4 class="workout-date"><?php echo helperGetCurrentDate()->format('l, Y-m-d'); ?> </h4>
			<div class="workout-wrapper">
				<span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout.png'; ?>"></span>
				<?php if ((int) $workout->workout_isDone) { ?>
					<span style="color:white">Completed</span>
				<?php } ?>
				<label><?php echo $workout->workout->workout_name . "-" . $workout->day->wday_name ?></label>
				<div class="workout-controls">
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-note.png'; ?>"></a></span>
					<span><a href="<?php echo home_url(); ?>/client/?data=workout&dayId=<?php echo $workout->workout_client_dayID?>&workoutId=<?php echo $workout->	workout_client_workout_ID?>"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-play.png'; ?>"></a></span>
				</div>
			</div>
		</li>
		<?php } ?>
	</ul>

	<div class="container-title">
		<h3>Next Activities</h3>
	</div>

	<ul class="workout-lists">
		<?php foreach ($clientWorkouts['upcomingWorkouts'] as $workout) {
			if($workout->workout):
		?>
			<li>
				<div class="workout-wrapper">
					<span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout.png'; ?>"></span>					
					<label><?php echo $workout->workout->workout_name . "-" . $workout->day->wday_name ?></label>
					<div class="workout-controls">
						<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-note.png'; ?>"></a></span>
						<!--span><a href="<?php echo home_url(); ?>/client/?data=workout"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-play.png'; ?>"></a></span-->
					</div>
				</div>
			</li>
		<?php
			endif;
		}
		?>
	</ul>
	<div class="recent-activity">
		<div class="row">
			<div class="col-lg-6 col-md-6">
				<h3>new message(s)</h3>
				<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/message-notification.png'; ?>">
			</div>
			<div class="col-lg-6 col-md-6">
				<h3>Week 5 complete</h3>
				<img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-graph.png'; ?>">
			</div>
		</div>
	</div>
</div>