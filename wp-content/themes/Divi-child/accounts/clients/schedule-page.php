<div class="main-content matchHeight schedule-page">
	
	<div class="container-title">
        <h3>Weekly Schedule</h3>
    </div>

	<ul class="workout-lists">
		<?php 
			$userdata = wp_get_current_user();
			$daysOfWeek = getDaysOfWeek();
			$weekSched = getWeeklySchedule($userdata);
			$today = date('Y-m-d');
			
			foreach($daysOfWeek as $dow):
				if(in_array_r($dow,$weekSched)):
		?>
			<li>
				<h4 class="workout-day"><?php echo date_format(date_create($dow), 'l'); ?></h4>
				<?php
					foreach($weekSched as $ws):
						if($ws['wsched'] == $dow):
				?>
					<div class="workout-wrapper">
						<span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout.png'; ?>"></span>
						<label><?php echo $ws['wdname']; ?></label>
						<div class="workout-controls">
							<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-note.png'; ?>"></a></span>
							<?php if($ws['wsched'] == $today): ?>
								<span><a href="<?php echo home_url() . '/client/?data=workout&dayId='.$ws['dayid'].'&workoutId='. $ws['wid']; ?>"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-play.png'; ?>"></a></span>
							<?php endif; ?>
						</div>
					</div>
				<?php
						endif;
					endforeach;
				?>
			</li>
		<?php endif; endforeach; ?>
		<!-- <li>
			<h4 class="workout-day">Monday</h4>
			<div class="workout-wrapper">
				<span><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout.png'; ?>"></span>
				<label>Workout NAME #1</label>
				<div class="workout-controls">
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-note.png'; ?>"></a></span>
					<span><a href="#"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-play.png'; ?>"></a></span>
				</div>
			</div>
		</li> -->
	</ul>
	
</div>