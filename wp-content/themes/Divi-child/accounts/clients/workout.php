<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>

	var clientWorkout = <?php echo json_encode(workoutClientWorkoutWithDay($_GET['dayId'], $_GET['workoutId'])) ?>;
	var rootUrl = '<?php echo get_site_url(); ?>';
	var currentUserId = '<?php echo wp_get_current_user()->ID ?>';
	var app = angular.module('app', []);

	app.controller('Controller', function($scope, $http)
	{
		var urlApiClient = rootUrl + '/wp-json/v1/client';
		$scope.clientWorkout = {};

		init();

		function init()
		{
			console.log('||-------------------------------------------------||');
			console.log(clientWorkout);
			$scope.clientWorkout = clientWorkout;

			sequenceExercises();

			console.log('||-------------------------------------------------||');
		}


		var Clock =
		{
			totalSeconds: 0,
			start: function ()
			{

				$("#idNextSet").attr("disabled", "disabled").button('refresh');
				$("#idBackSet").attr("disabled", "disabled").button('refresh');
				function pad(val) { return val > 9 ? val : "0" + val; }

				var self = this;
				self.totalSeconds = 5;
				try {

					console.log($scope.currentExercise.exer_rest);

					if ($scope.currentExercise.exer_rest.indexOf('sec') > 0) {
						self.totalSeconds = $scope.currentExercise.exer_rest.replace(/[^\d.-]/g, '');
					} else {
						var mins = $scope.currentExercise.exer_rest.replace(/[^\d.-]/g, '');
						self.totalSeconds = parseInt(mins) * 60;
					}
				} catch (err) {
					self.totalSeconds = 5;
				}

				//self.totalSeconds = 5;

				this.interval = setInterval(function ()
				{
					self.totalSeconds -= 1;

					if(self.totalSeconds == 0)
					{
						Clock.stop();
					}

					$("#min").text(pad(Math.floor(self.totalSeconds / 60 % 60)));
					$("#sec").text(pad(parseInt(self.totalSeconds % 60)));

				}, 1000);
			},

			reset: function () {
				Clock.totalSeconds = null;
				clearInterval(this.interval);
				$("#min").text("00");
				$("#sec").text("00");
			},
			pause: function () {
				clearInterval(this.interval);
				delete this.interval;
			},

			stop: function () {
				$("#idNextSet").removeAttr("disabled").button('refresh');
				$("#idBackSet").removeAttr("disabled").button('refresh');
				clearInterval(this.interval);
				delete this.interval;
				this.totalSeconds = 0;
				runNext();
			},

			resume: function () {
				if (!this.interval) this.start();
			},

			restart: function () {
				this.reset();
				Clock.start();
			}
		};

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

		function runNext()
		{
			$http.post(urlApiClient+'/process', $scope.currentExercise).then(function()
			{
				var hasFoundDone = false;

				if ($scope.currentExercise.currentSet)
				{
					var currentOrder = $scope.currentExercise.currentSet.seq;
					var nextOrder = currentOrder + 1;

					$scope.currentExercise.currentSet.isDone = true;
					for (var i in $scope.currentExercise.sets)
					{
						var set = $scope.currentExercise.sets[i];

						if (nextOrder == set.seq)
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
			});
		}

		$scope.checkNotCurrent = function(set)
		{
			if (set.seq != $scope.currentExercise.currentSet.seq)
			{
				return true;
			}

			return false;
		};

		$scope.onNextSet = function()
		{
			Clock.start();
		};

		$scope.onBackSet = function()
		{
			console.log('onBackSet');
			if ($scope.currentExercise.currentSet)
			{
				var currentOrder = $scope.currentExercise.currentSet.seq;
				var prevOrder = currentOrder - 1;

				if (prevOrder > 0)
				{
					for (var i in $scope.currentExercise.sets)
					{
						var set = $scope.currentExercise.sets[i];

						if (prevOrder == set.seq)
						{
							$scope.currentExercise.currentSet = set;
							break;
						}
					}
				}
			}

		};

		$scope.$watch('currentExercise', function(val)
		{

			if (val)
			{
				$scope.currentExercise.user_id = currentUserId;
				$scope.currentExercise.sets = [];

				/* checking client exercise logs */
				var params = '?exerciseId='+$scope.currentExercise.exer_ID+'&user_id='+currentUserId;

				$http.get(urlApiClient+'/get'+params).then(function(res)
				{
					console.log(res);

					for (var i=1; i<=$scope.currentExercise.exer_sets; i++)
					{
						var nSet = {seq:i, isMet:1, isDone:false, reps:''};
						$scope.currentExercise.sets.push(nSet);
					}

					sequenceSets();
				});
			}
		});

	});

</script>
<div class="main-content matchHeight start-workout" ng-app="app" ng-controller="Controller" ng-cloak>

	<ul class="workout-lists">
		<li>
			<div class="workout-wrapper">
				<div class="text-center">
					<h4>{{ clientWorkout.workout.workout_name }} - {{ clientWorkout.day.wday_name }}</h4>
				</div>
				<div class="exercise-area">
					<div class="col-lg-12 col-md-12">
						<div class="exercise-label">
							<div class="col-lg-4 col-md-4 col-sm-12">
								<label>{{ currentExercise.exer_body_part }} <br> <small>{{ currentExercise.exer_type }}</small></label>
							</div>
							<div class="col-lg-8 col-md-8 col-sm-12 exercise-details">
								<div class="ed-item">Sets: <span>{{ currentExercise.exer_sets }}</span></div>
								<div class="ed-item">Tempo: <span>{{ currentExercise.exer_tempo }}</span></div>
							</div>
						</div>
					</div>
					<div class="exercise-set-area">
						<div class="col-lg-4 col-md-4 col-sm-12">
							<a href="javascript:void(0);" onclick="modalClick('YyvSfVjQeL0');"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/images/video-sample.jpg" /></a>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12">
							<div class="exercise-set-item">
								<div class="exercise-set-goal">
									<h5>
										<div><span>Start:</span> Set {{ currentExercise.currentSet.seq }}</div>
										<div class="exercise-set-rest">
											Rest:
											<div class="rest-timer ng-binding"><span id="min">00</span>:<span id="sec">00</span></div>
										</div>
									</h5>
									<div class="col-lg-6 col-md-6 col-sm-12 goal-set">
										<label>
											Reps
											<span>{{ currentExercise.exer_rep }}</span>
										</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 goal-set">
										<label>
											Weight
											<span>50<small>lbs</small></span>
										</label>
									</div>
									<div class="rep-navigation">
										<div>
											<button ng-click="onBackSet();" id="idBackSet"><span class="btn-arrow btn-arr-back"></span>Back</button>
											<label>
												Reps Complete:
												<input type="text" ng-model="currentExercise.currentSet.reps"/>
											</label>
											<button ng-click="onNextSet();" id="idNextSet">Next Set <span class="btn-arrow btn-arr-next"></span></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</li>
	</ul>
</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<iframe width="100%" height="300" frameborder="0" allowfullscreen=""></iframe>
			</div>
		</div>
	</div>
</div>
<script>
	function modalClick(a){
		var src = a;
		$('#myModal').modal('show');
		$('#myModal iframe').attr('src', 'https://www.youtube.com/embed/'+a+'?rel=0&autoplay=1');
	}
</script>