<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>

	var clientWorkout = <?php echo json_encode(workoutClientWorkoutWithDay($_GET['dayId'], $_GET['workoutId'])) ?>;
	var app = angular.module('app', []);

	app.controller('Controller', function($scope)
	{
		$scope.clientWorkout = {};

		init();

		function init()
		{
			console.log('||-------------------------------------------------||');
			console.log(clientWorkout);
			$scope.clientWorkout = clientWorkout;
			sequenceExercises();
		}


		function sequenceExercises()
		{
			for (var i in $scope.clientWorkout.exercises)
			{
				var exercise = $scope.clientWorkout.exercises[i];

				if (!exercise.isDone)
				{
					$scope.currentExercise = exercise;
					break;
				}
			}
		}

		function sequenceSets()
		{
			for (var i in $scope.currentExercise.sets)
			{
				var set = $scope.currentExercise.sets[i];

				if (!set.isDone)
				{
					$scope.currentExercise.currentSet = set;
					break;
				}
			}
		}


		$scope.checkNotCurrent = function(set)
		{
			if (set.order != $scope.currentExercise.currentSet.order)
			{
				return true;
			}

			return false;
		};

		$scope.onNextSet = function()
		{
			console.log('---- the current set is-----');
			console.log($scope.currentExercise);

			var hasFoundDone = false;

			if ($scope.currentExercise.currentSet)
			{
				var currentOrder = $scope.currentExercise.currentSet.order;
				var nextOrder = currentOrder + 1;

				if (nextOrder >= 2) {
					$scope.currentExercise.isShowTime = true;
				}

				$scope.currentExercise.currentSet.isDone = true;
				for (var i in $scope.currentExercise.sets)
				{
					var set = $scope.currentExercise.sets[i];

					if (nextOrder == set.order)
					{
						hasFoundDone = true;
						$scope.currentExercise.currentSet = set;
						break;
					}
				}
			}

			if (!hasFoundDone) {
				$scope.currentExercise.isDone = true;
				console.log('THE END');
				console.log($scope.clientWorkout);
				sequenceExercises();
			}
		};

		$scope.$watch('currentExercise', function(val)
		{
			console.log('watching exercise');
			console.log(val);
			$scope.currentExercise.sets = [];

			for (var i=1; i<=$scope.currentExercise.exer_sets; i++)
			{
				var nSet = {order:i, isMet:1, isDone:false, reps:''};
				$scope.currentExercise.sets.push(nSet);
			}

			console.log($scope.clientWorkout.sets);
			sequenceSets();
		});
	});

</script>
<div class="main-content matchHeight start-workout" ng-app="app" ng-controller="Controller" ng-cloak>
	<div class="container-title">
		<h3>Start Workout</h3>
	</div>

	<ul class="workout-lists">
		<li>
			<div class="workout-wrapper">
				<div class="col-lg-12 col-md-12">
					<h4>{{ clientWorkout.workout.workout_name }} - {{ clientWorkout.day.wday_name }}</h4>
					<div class="text-center">
						<span><img src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/images/workout.png"></span>
						<label>{{ currentExercise.exer_body_part }}</label>
						<div class="exercise-details">
							<div class="ed-item">Sets: <span>{{ currentExercise.exer_sets }}</span></div>
							<div class="ed-item">Reps: <span>{{ currentExercise.exer_rep }}</span></div>
							<div class="ed-item">Tempo: <span>{{ currentExercise.exer_tempo }}</span></div>
					</div>
					<div class="exercise-set-item">
						<div class="exercise-set-goal">
							<h5>Set {{ currentExercise.currentSet.order }} </h5>
							<div class="col-lg-12 col-md-12 col-sm-12 goal-set">
								<label><span>Goal:</span> {{ currentExercise.exer_rep }} Reps</label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 rep-radio">
								<div>
									<label>Not able to complete all reps?</label>
									<br>
									<label class="jradio">
										<input ng-checked="currentExercise.currentSet.isMet == 0" type="radio" value="0" ng-model="currentExercise.currentSet.isMet"/>
										<span class="checkmark"></span>
										<span>Enter the number <br>of reps completed</span>
									</label>
									<input type="text" ng-model="currentExercise.currentSet.reps"/>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 rep-radio">
								<div>
									<label>Completed all reps? Click next set!</label>
									<br>
									<label class="jradio goal-met">
										<input ng-checked="currentExercise.currentSet.isMet == 1" type="radio" value="1" ng-model="currentExercise.currentSet.isMet" />
										<span class="checkmark"></span>
										<span>Goal <br>Met!</span>
									</label>
									<button ng-click="onNextSet();">Next Set</button>
								</div>
							</div>
						</div>
						<div class="exercise-set-rest" ng-show="currentExercise.isShowTime">
							<h5>Rest Period</h5>
							<div class="rest-timer">{{ currentExercise.exer_rest }}</div>
						</div>
					</div>
					<div class="exercise-set-item" ng-repeat="set in currentExercise.sets" ng-if="checkNotCurrent(set)">
						<div class="exercise-set-goal">
							<h5>Set {{ set.order }} {{set.isDone ? "previous set" : "up next"}}</h5>
							<div class="col-lg-12 col-md-12 col-sm-12 goal-set">
								<label><span>Goal:</span> {{ currentExercise.exer_rep }} Reps</label>
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