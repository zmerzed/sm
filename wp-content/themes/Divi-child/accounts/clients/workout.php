<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>

	var workout = <?php echo json_encode(workOutGet($_GET['workout'])) ?>;
	var app = angular.module('app', []);

	app.controller('Controller', function($scope)
	{
		init();

		function init()
		{
			console.log('||-------------------------------------------------||');
			console.log(workout);
		}
	});

</script>
<div class="main-content matchHeight start-workout" ng-app="app" ng-controller="Controller">
	<div class="container-title">
		<h3>Start Workout</h3>
	</div>

	<ul class="workout-lists">
		<li>
			<div class="workout-wrapper">
				<div class="col-lg-12 col-md-12">
					<h4>Workout Name 1 - Day 1</h4>
					<div class="text-center">
						<span><img src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/images/workout.png"></span>
						<label>Exercise Name #1</label>
						<div class="exercise-details">
							<div class="ed-item">Sets: <span>3</span></div>
							<div class="ed-item">Reps: <span>10</span></div>
							<div class="ed-item">Tempo: <span>Level 2</span></div>
						</div>
					</div>
					<div class="exercise-set-item">
						<div class="exercise-set-goal">
							<h5>Set 1</h5>
							<div class="col-lg-12 col-md-12 col-sm-12 goal-set">
								<label><span>Goal:</span> 10 Reps</label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 rep-radio">
								<div>
									<label>Not able to complete all reps?</label>
									<br>
									<label class="jradio">
										<input type="radio" value="1" name="goal-status" />
										<span class="checkmark"></span>
										<span>Enter the number <br>of reps completed</span>
									</label>
									<input type="text" />
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 rep-radio">
								<div>
									<label>Completed all reps? Click next set!</label>
									<br>
									<label class="jradio goal-met">
										<input checked="checked"  type="radio" value="2" name="goal-status" />
										<span class="checkmark"></span>
										<span>Goal <br>Met!</span>
									</label>
									<button>Next Set</button>
								</div>
							</div>
						</div>
						<div class="exercise-set-rest">
							<h5>Rest Period</h5>
							<div class="rest-timer">0:02</div>
						</div>
					</div>
					<div class="exercise-set-item">
						<div class="exercise-set-goal">
							<h5>Set 2 Up Next</h5>
							<div class="col-lg-12 col-md-12 col-sm-12 goal-set">
								<label><span>Goal:</span> 10 Reps</label>
							</div>
						</div>
					</div>
					<div class="workout-control">
						<a href="#"><span><a href="<?php echo home_url(); ?>/client/?data=workout"><img src="<?php echo get_stylesheet_directory_uri() .'/accounts/images/workout-play.png'; ?>"></a></span></a> <label>Workout Started</label>
						<label class="workout-timer">0:14</label>
					</div>
				</div>
			</div>
		</li>
	</ul>
</div>