<?php

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateWorkoutForm'])) {
		$id_ = workOutUpdate($_POST);
		wp_redirect( get_site_url() . '/trainer/?data=add-workouts&workout='. $id_);
	}
?>

<?php $workout = workOutGet($_GET['workout']); ?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
	var clients = <?php echo json_encode(workOutGetClients()) ?>;
	var workout = <?php echo json_encode(workOutGet($_GET['workout'])) ?>;
	var exerciseOptions = <?php echo json_encode(workOutExerciseOptions()) ?>;
	var exerciseSQoptions = <?php echo json_encode(workOutExerciseStrengthQualitiesOptions()) ?>;
	var app = angular.module('app', []);
	var rootUrl = '<?php echo get_site_url(); ?>';
	var urlApiClient = rootUrl + '/wp-json/v1';

	app.controller('Controller', function($scope, $http) {

		$scope.clients = clients;
		$scope.clientsBackup = angular.copy(clients);
		$scope.workout = workout;
		$scope.workoutMaxSet = 0;
		$scope.exerciseOptions = exerciseOptions;
		$scope.exerciseSQoptions = exerciseSQoptions;
		$scope.clientExerciseSets = [];

		init();

		function init()
		{

			for(var i in $scope.workout.days)
			{
				var day = $scope.workout.days[i];

				for(var x in day.exercises)
				{
					var exercise = day.exercises[x];
					exercise.exerciseOptions = angular.copy($scope.exerciseOptions);
					exercise.exerciseSQoptions = angular.copy($scope.exerciseSQoptions);

					for(var y in exercise.exerciseOptions)
					{
						var part = exercise.exerciseOptions[y];

						if (part.part == exercise.exer_body_part)
						{
							exercise.selectedPart = part;

							for(var t in part.options)
							{
								var option = part.options[t];

								if (option['type'] == exercise.exer_type)
								{
									exercise.selectedPart.selectedType = option;

									for (var o in option['exercise_1'])
									{
										var ex1 = option['exercise_1'][o];

										if(exercise.exer_exercise_1 == ex1)
										{
											exercise.selectedPart.selectedType.selectedExercise1 = ex1;
											break;
										}
									}

									for (var o in option['exercise_2'])
									{
										var ex2 = option['exercise_2'][o];

										if(exercise.exer_exercise_2 == ex2)
										{
											exercise.selectedPart.selectedType.selectedExercise2 = ex2;
											break;
										}
									}

									for (var o in option['implementation_options'])
									{
										var imp1 = option['implementation_options'][o];

										if(exercise.exer_impl1 == imp1)
										{
											exercise.selectedPart.selectedType.selectedImplementation1 = imp1;
											break;
										}
									}

									break;
								}
							}
							break;
						}
					}

					for(var z in exercise.exerciseSQoptions)
					{
						var sq = exercise.exerciseSQoptions[z];

						if(exercise.exer_sq == sq.name)
						{
							exercise.selectedSQ = sq;

							for(var o in sq.options.set_options)
							{
								var set = sq.options.set_options[o];

								if(exercise.exer_sets == set)
								{
									exercise.selectedSQ.selectedSet = set;
									break;
								}
							}

							for(var o in sq.options.rep_options)
							{
								var rep = sq.options.rep_options[o];

								if(exercise.exer_rep == rep)
								{
									exercise.selectedSQ.selectedRep = rep;
									break;
								}
							}

							for(var o in sq.options.tempo)
							{
								var tempo = sq.options.tempo[o];

								if(exercise.exer_tempo == tempo)
								{
									exercise.selectedSQ.selectedTempo = tempo;
									break;
								}
							}

							for(var o in sq.options.rest)
							{
								var rest = sq.options.rest[o];

								if(exercise.exer_rest == rest)
								{
									exercise.selectedSQ.selectedRest = rest;
									break;
								}
							}

							break;
						}
					}


				}
			}

			console.log($scope.workout);

			if ($scope.workout.days.length > 0)
			{
				optimizeDays();
				selectDay($scope.workout.days[0]);

				/* get the largest set in a selected day */
				findTheLargestSet();
			}

		}

		$scope.newWorkOutDay = function ()
		{

			$http.get(urlApiClient + '/hash').then(function(res)
			{

				var newEx = generateNewExercise(res.data.hash);

				$scope.workout.days.push({exercises:[newEx], clients:[]});

				optimizeDays();
				var countDays = $scope.workout.days.length;

				$scope.selectedClient = "Add Client";
				$scope.clients = angular.copy($scope.clientsBackup);

				selectDay($scope.workout.days[countDays - 1])
			});
		};

		$scope.onChangeDayName = function()
		{
			$scope.onLeaveDay();
		};

		$scope.onSelectDay = function(day)
		{
			optimizeSelectedDay();
			selectDay(day);
		};

		$scope.onLeaveDay = function()
		{
			for (var i in $scope.workout.days)
			{
				var day = $scope.workout.days[i];

				if (day.wday_order == $scope.workout.selectedDay.wday_order)
				{
					$scope.workout.days[i] = angular.copy($scope.workout.selectedDay);

					for (var x in $scope.workout.days[i].clients)
					{
						var client = $scope.workout.days[i].clients[x];

						if ($scope.workout.selectedDay.selectedClient && client.ID == $scope.workout.selectedDay.selectedClient.id)
						{
							$scope.workout.days[i].clients[x] = $scope.workout.selectedDay.selectedClient;
							break;
						}
					}

					break;
				}
			}
		};

		$scope.isActive = function(day)
		{
			if ($scope.workout.selectedDay.wday_order == day.wday_order) {
				return true;
			}

			return false;
		};

		$scope.isClientActive = function(client)
		{

			if ($scope.workout.selectedDay.selectedClient && $scope.workout.selectedDay.selectedClient.ID == client.ID) {
				return true;
			}

			return false;
		};

		$scope.newExercise = function()
		{

			$http.get(urlApiClient + '/hash').then(function(res) {

				var newEx = generateNewExercise(res.data.hash);

				$scope.workout.selectedDay.exercises.push(newEx);
				optimizeSelectedDay();
				optimizeDays();
			});

		};

		$scope.selectClient = function(client)
		{
			$scope.workout.selectedDay.selectedClient = client;
			console.log($scope.workout.selectedDay.selectedClient);
			optimizeClientExercises();
		};

		$scope.$watch('selectedClient', function(val)
		{
			var found = false;
			for(var i in $scope.clients)
			{
				var client = $scope.clients[i];

				if(client.ID == val)
				{
					for(var x in $scope.workout.selectedDay.clients)
					{
						var xClient = $scope.workout.selectedDay.clients[x];

						if(xClient.ID == val)
						{
							found = true;
						}
					}

					if(!found) {
						$scope.workout.selectedDay.clients.push(client);
						$scope.selectClient(client);
					}

					break;
				}
			}


			/* console.log('===========xxxxxxxxxxBEFORExxxxxxxxxxx=========');
			console.log($scope.workout.selectedDay.clients);
			console.log($scope.clients);
			console.log('===========xxxxxxxxxxBEFORExxxxxxxxxxx========='); */

			// match $scope.workout.selectedDay.clients to $scope.clients;
			optimizeSelectedClients();
			optimizeClientExercises();
		});

		$scope.$watch(function() {
			console.log('/* get the largest set in a selected day */');
			return $scope.workout.selectedDay.exercises;
		}, function(newValue, oldValue){

			if (!angular.equals(oldValue, newValue)) {
				console.log('--------------------------');
				optimizeClientExercises();
				findTheLargestSet();
			}
		},true);

		$scope.removeDay = function(day)
		{
			day.isDelete = true;

			console.log('uuuuuuuuuuuuuuuuu');
			console.log($scope.workout.days);

			delete day.selectedClient;
			delete day.exercises;
			delete day.clients;

			optimizeDays();
			$scope.onLeaveDay();

			if($scope.$root.$$phase != '$apply' &&
				$scope.$root.$$phase != '$digest'
			) {
				$scope.$apply();
			}

			setTimeout(function() {

				console.log('xxxxxxxxxxxxxxxxxxxxxxxx');

				for (var i=$scope.workout.days.length - 1; i>=0; --i)
				{
					var day = $scope.workout.days[i];

					if (!day.isDelete) {
						selectDay(day);
						break;
					}
				}

			}, 200)
		};

		$scope.removeExercise = function(exercise)
		{
			exercise.isDelete = true;
			optimizeSelectedDay();
			optimizeClientExercises();
			findTheLargestSet();
		};

		$scope.onCopy = function()
		{
			var newCopy = angular.copy($scope.workout.selectedDay);

			delete newCopy.wday_ID;

			$http.get(urlApiClient + '/hash').then(function(res)
			{
				console.log(newCopy);
				newCopy.exercises.forEach(function(ex) {
					ex.hash = res.data.hash;
				});

				newCopy.wday_name = '';
				$scope.workout.days.push(newCopy);
				var countDays = $scope.workout.days.length;
				optimizeDays();
				selectDay($scope.workout.days[countDays - 1])

			});

		};

		$scope.onCopyExercise = function(exercise)
		{
			console.log(exercise);
			var newExercise = angular.copy(exercise);

			delete newExercise.exer_ID;
			$http.get(urlApiClient + '/hash').then(function(res)
			{
				newExercise.hash = res.data.hash;
				$scope.workout.selectedDay.exercises.push(newExercise);
				optimizeClientExercises();
			});

		};

		$("#idForm").submit(function (e) {
			//e.preventDefault();

//			delete $scope.workout.selectedDay;
//			for(var i in $scope.workout.days) {
//				var day = $scope.workout.days[i];
//				for(var e in day.exercises)
//				{
//					var ex = day.exercises[e];
//					delete ex.exerciseOptions;
//					delete ex.exerciseSQoptions;
//				}
//			}

			delete $scope.workout.selectedDay;

			for(var i in $scope.workout.days)
			{
				var day = $scope.workout.days[i];

				delete day.selectedClient;
				for(var e in day.exercises)
				{
					var ex = day.exercises[e];
					delete ex.exerciseOptions;
					delete ex.exerciseSQoptions;
				}

				for (var x in day.clients)
				{
					var client  = day.clients[x];

					for (var m in client.exercises)
					{
						var clientExercise = client.exercises[m];
						delete clientExercise.exerciseOptions;
						delete clientExercise.exerciseSQoptions;
						delete clientExercise.selectedPart;
						delete clientExercise.selectedSQ;
					}
				}
			}


			$('#idWorkoutForm').val(JSON.stringify($scope.workout));
			return true;

		});

		function optimizeSelectedClients()
		{
			$scope.clients = angular.copy($scope.clientsBackup);
			var listToDelete = [];

			for (var i = 0; i < $scope.workout.selectedDay.clients.length; i++) {
				listToDelete.push($scope.workout.selectedDay.clients[i].ID);
			}

			var lengthToDelete = listToDelete.length;

			for(var i = 0; i < $scope.clients.length; i++) {
				var obj = $scope.clients[i];

				if(listToDelete.indexOf(obj.ID) !== -1) {
					$scope.clients.splice(i, lengthToDelete);
				}
			}


			/* console.log('===========xxxxxxxxxxAFTERxxxxxxxxxxx=========');
			console.log($scope.workout.selectedDay.clients);
			console.log($scope.clients);
			console.log('===========xxxxxxxxxxAFTERxxxxxxxxxxx========='); */

		}

		function findTheLargestSet()
		{
			/* get the max set */
			$scope.workoutMaxSet = 0;

			for (var i in $scope.workout.selectedDay.exercises)
			{
				var exercise = angular.copy($scope.workout.selectedDay.exercises[i]);
				var noSet = 0;
				$scope.clientExerciseSets[i] = 0;

				if (exercise.exer_sets) {
					noSet = parseInt(angular.copy(exercise.exer_sets));
					$scope.clientExerciseSets[i] = parseInt(angular.copy(exercise.exer_sets));
				}

				if (exercise.selectedSQ && exercise.selectedSQ.selectedSet) {
					noSet = exercise.selectedSQ.selectedSet;
					$scope.clientExerciseSets[i] = exercise.selectedSQ.selectedSet;
				}

				if (typeof $scope.workoutMaxSet == 'undefined') {
					$scope.workoutMaxSet = 0;

					if(noSet >= $scope.workoutMaxSet) {
						$scope.workoutMaxSet = noSet;
					}

				} else if(noSet >= $scope.workoutMaxSet) {
					$scope.workoutMaxSet = noSet;
				}
			}
		}

		function generateNewExercise(hash)
		{
			return {hash:hash, exerciseOptions: angular.copy($scope.exerciseOptions), exerciseSQoptions: angular.copy($scope.exerciseSQoptions)};
		}

		function selectDay(day)
		{
			$scope.workout.selectedDay = angular.copy(day);

			if ($scope.workout.selectedDay.clients)
			{
				$scope.workout.selectedDay.selectedClient = $scope.workout.selectedDay.clients[0];

				optimizeSelectedClients();
				optimizeClientExercises();
				optimizeSelectedDay();

			}
			findTheLargestSet();
		}

		function optimizeSelectedDay()
		{
			var count = 1;

			for(var i in $scope.workout.selectedDay.exercises)
			{
				var exercise = $scope.workout.selectedDay.exercises[i];

				if(exercise.isDelete) {
					continue;
				}

				$scope.workout.selectedDay.exercises[i]['seq'] = count;
				count++;
			}
		}

		function optimizeClientExercises()
		{

			for (var i in $scope.workout.selectedDay.clients)
			{
				var client = $scope.workout.selectedDay.clients[i];
				var mExercises = [];

				for (var x in $scope.workout.selectedDay.exercises)
				{
					var isExFound = false;
					var ex = $scope.workout.selectedDay.exercises[x];

					if (ex.isDelete) {
						continue;
					}

					for (var z in client.exercises)
					{
						var cEx = client.exercises[z];

						if (ex.hash == cEx.hash)
						{

							var nEx = angular.copy(ex);
							isExFound = true;

							if (ex.selectedPart)
							{
								nEx.exer_body_part = ex.selectedPart.part;

								if (ex.selectedPart.selectedType)
								{
									nEx.exer_type = ex.selectedPart.selectedType.type;
								}
							}

							nEx.assignment_sets = angular.copy(cEx.assignment_sets);
							mExercises.push(nEx);
							break;
						}
					}

					if (!isExFound)
					{
						mExercises.push(ex);
					}
				}

				client.exercises = mExercises;
			}

			findTheLargestSet();

			if($scope.$root.$$phase != '$apply' &&
				$scope.$root.$$phase != '$digest'
			) {
				$scope.$apply();
			}

		}

		function optimizeDays()
		{

			var count = 1;

			for(var i in $scope.workout.days)
			{
//				var day = $scope.workout.days[i];
//
//				if (!day.isDelete)
//				{
//
//				}
				$scope.workout.days[i].wday_order = count;
				count++;
			}
		}
	});

	app.filter("range", function() {
		return function(input, total) {
			total = parseInt(total);
			for (var i = 0; i < total; i++) {
				input.push(i);
			}
			return input;
		};
	});

	app.directive("datepicker", function () {
		return {
			restrict: "A",
			link: function (scope, el, attr) {
				var dateToday = new Date();
				el.datepicker({
					minDate: dateToday,
					dateFormat: 'yy-mm-dd'
				});
			}
		};
	});

</script>

<div class="main-content padding20 matchHeight" ng-app="app" ng-controller="Controller" ng-cloak>

	<form id="idForm" action="?data=add-workouts&workout=<?php echo $workout['workout_ID']; ?>&r=<?php echo rand(5, 15);?>" method="POST">

		<div class="container trainer-header-section">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<span class="workout-day-name">
						<label>Workout Name: </label>
						<input type="text" ng-model="workout.workout_name">
					</span>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="btn-add-workout">
						<button type="button" ng-click="newWorkOutDay()">+ new workout day</button>
					</div>
				</div>
			</div>
		</div>

		<nav>
			<div class="nav nav-tabs" id="message-nav-tab" role="tablist">
				<a class="nav-item nav-link"
				   id="nav-home-tab"
				   data-toggle="tab"
				   href="#workout-1"
				   role="tab"
				   aria-controls="nav-home"
				   aria-selected="true"
				   ng-repeat="day in workout.days"
				   ng-click="onSelectDay(day)"
				   ng-class="{active: isActive(day)}"
				   ng-show="!day.isDelete"
				>Day {{day.wday_order}} - {{day.wday_name}}</a>
			</div>
		</nav>

		<div ng-mouseleave="onLeaveDay()">
			<div class="tab-content" id="nav-tabContent" ng-if="workout.selectedDay">
				<div class="tab-pane fade show active" id="workout-1" role="tabpanel" aria-labelledby="nav-home-tab">
					<div class="workout-tab-pane-wrapper">

						<div class="container">
							<div class="row">
								<div class="col-lg-6 col-md-6">
							<span class="workout-day-name">
								<label>Day Name: </label>
								<input type="text" ng-model="workout.selectedDay.wday_name" ng-change="onChangeDayName();">
							</span>
								</div>
								<div class="col-lg-6 col-md-6">
									<ul class="workout-btn-actions">
										<li ng-click="onCopy()"><a href="#">Duplicate</a></li>
										<li ng-click="removeDay(workout.selectedDay)"><a href="javascript:void(0)">Delete</a></li>
									</ul>
								</div>
							</div>
						</div>

						<ul class="workout-exercise-lists">
							<li class="workout-exercise-item" ng-repeat="exercise in workout.selectedDay.exercises track by $index" ng-show="!exercise.isDelete">
								<table class="workout-exercise-options">
									<td><span class="exercise-number"><label>{{ exercise.seq }}</label></span></td>
									<td>
										<select ng-model="exercise.selectedPart" ng-options="opt.part for opt in exercise.exerciseOptions">
											<option value="{{exercise.exer_body_part}}">{{exercise.exer_body_part}}</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedPart.selectedType" ng-options="type.type for type in exercise.selectedPart.options">
											<option value="">Type</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedPart.selectedType.selectedExercise1" ng-options="ex as ex for ex in exercise.selectedPart.selectedType.exercise_1">
											<option value="">Exercise 1</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedPart.selectedType.selectedExercise2" ng-options="ex as ex for ex in exercise.selectedPart.selectedType.exercise_2">
											<option value="">Exercise 2</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedSQ" ng-options="sqOption.name for sqOption in exercise.exerciseSQoptions">
											<option value="">SQ</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedSQ.selectedSet" ng-options="set as set for set in exercise.selectedSQ.options.set_options">
											<option value="">Sets</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedSQ.selectedRep" ng-options="rep as rep for rep in exercise.selectedSQ.options.rep_options">
											<option value="">Reps</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedSQ.selectedTempo" ng-options="tempo as tempo for tempo in exercise.selectedSQ.options.tempo">
											<option value="">Tempo</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedSQ.selectedRest" ng-options="rest as rest for rest in exercise.selectedSQ.options.rest">
											<option value="">Rest</option>
										</select>
									</td>
									<td>
										<select ng-model="exercise.selectedPart.selectedType.selectedImplementation1" ng-options="imp1 as imp1 for imp1 in exercise.selectedPart.selectedType.implementation_options">
											<option value="">IMPL 1</option>
										</select>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#" ng-click="onCopyExercise(exercise)">Duplicate</a><span>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="javascript:void(0)" ng-click="removeExercise(exercise)">Delete</a><span>
									</td>
								</table>
							</li>
						</ul>
						<div class="col-lg-12">
							<div class="row">
								<div class="workout-btn-wrapper">
									<button type="button" class="add-workout-btn" ng-click="newExercise()">+ Add Exercise</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="workout-2" role="tabpanel" aria-labelledby="nav-profile-tab">
					<div class="workout-tab-pane-wrapper">
						<div class="container">
							<div class="row">
								<div class="col-lg-6 col-md-6">
							<span class="workout-day-name">
								<label>Day Name: </label>
								<input type="text" value="Name #1">
							</span>
								</div>
								<div class="col-lg-6 col-md-6">
									<ul class="workout-btn-actions">
										<li><a href="#">Duplicate</a></li>
										<li><a href="#">Delete</a></li>
									</ul>
								</div>
							</div>
						</div>

						<ul class="workout-exercise-lists">
							<li class="workout-exercise-item">
								<table class="workout-exercise-options">
									<td><span class="exercise-number"><label>1</label></span></td>
									<td>
										<select>
											<option>Body Part</option>
										</select>
									</td>
									<td>
										<select>
											<option>Type</option>
										</select>
									</td>
									<td>
										<select>
											<option>Exercise 1</option>
										</select>
									</td>
									<td>
										<select>
											<option>SQ</option>
										</select>
									</td>
									<td>
										<select>
											<option>Sets</option>
										</select>
									</td>
									<td>
										<select>
											<option>Reps</option>
										</select>
									</td>
									<td>
										<select>
											<option>Tempo</option>
										</select>
									</td>
									<td>
										<select>
											<option>Rest</option>
										</select>
									</td>
									<td>
										<select>
											<option>IMPL 1</option>
										</select>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Duplicate</a><span>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Delete</a><span>
									</td>
								</table>
							</li>
							<li class="workout-exercise-item">
								<table class="workout-exercise-options">
									<td><span class="exercise-number"><label>2</label></span></td>
									<td>
										<select>
											<option>Body Part</option>
										</select>
									</td>
									<td>
										<select>
											<option>Type</option>
										</select>
									</td>
									<td>
										<select>
											<option>Exercise 2</option>
										</select>
									</td>
									<td>
										<select>
											<option>SQ</option>
										</select>
									</td>
									<td>
										<select>
											<option>Sets</option>
										</select>
									</td>
									<td>
										<select>
											<option>Reps</option>
										</select>
									</td>
									<td>
										<select>
											<option>Tempo</option>
										</select>
									</td>
									<td>
										<select>
											<option>Rest</option>
										</select>
									</td>
									<td>
										<select>
											<option>IMPL 1</option>
										</select>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Duplicate</a><span>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Delete</a><span>
									</td>
								</table>
							</li>
							<li class="workout-exercise-item">
								<table class="workout-exercise-options">
									<td><span class="exercise-number"><label>3</label></span></td>
									<td>
										<select>
											<option>Body Part</option>
										</select>
									</td>
									<td>
										<select>
											<option>Type</option>
										</select>
									</td>
									<td>
										<select>
											<option>Exercise 3</option>
										</select>
									</td>
									<td>
										<select>
											<option>SQ</option>
										</select>
									</td>
									<td>
										<select>
											<option>Sets</option>
										</select>
									</td>
									<td>
										<select>
											<option>Reps</option>
										</select>
									</td>
									<td>
										<select>
											<option>Tempo</option>
										</select>
									</td>
									<td>
										<select>
											<option>Rest</option>
										</select>
									</td>
									<td>
										<select>
											<option>IMPL 1</option>
										</select>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Duplicate</a><span>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Delete</a><span>
									</td>
								</table>
							</li>
							<li class="workout-exercise-item">
								<table class="workout-exercise-options">
									<td><span class="exercise-number"><label>4</label></span></td>
									<td>
										<select>
											<option>Body Part</option>
										</select>
									</td>
									<td>
										<select>
											<option>Type</option>
										</select>
									</td>
									<td>
										<select>
											<option>Exercise 4</option>
										</select>
									</td>
									<td>
										<select>
											<option>SQ</option>
										</select>
									</td>
									<td>
										<select>
											<option>Sets</option>
										</select>
									</td>
									<td>
										<select>
											<option>Reps</option>
										</select>
									</td>
									<td>
										<select>
											<option>Tempo</option>
										</select>
									</td>
									<td>
										<select>
											<option>Rest</option>
										</select>
									</td>
									<td>
										<select>
											<option>IMPL 1</option>
										</select>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Duplicate</a><span>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="#">Delete</a><span>
									</td>
								</table>
							</li>
						</ul>

						<div class="col-lg-12">
							<div class="row">
								<div class="workout-btn-wrapper">
									<a href="#" class="add-workout-btn">+ Add Exercise</a>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="container assign-client-to-workout" ng-show="workout.selectedDay">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<h3>Assign Clients to Workout</h3>
						<div class="d-flex flex-row mt-2">

							<div class="col-lg-2 col-md-2">
								<ul class="nav nav-tabs nav-tabs--vertical nav-tabs--left assign-clients-workout" role="navigation">
									<li class="nav-item" ng-repeat="client in workout.selectedDay.clients">
										<a href="#lorem"
										   class="nav-link"
										   data-toggle="tab" role="tab"
										   ng-click="selectClient(client)"
										   ng-class="{active: isClientActive(client)}"
										>{{ client.user_nicename }}</a>
									</li>
								</ul>
								<div class="browse-client-workout">
									<select ng-model="selectedClient">
										<option disabled selected>Add Client</option>
										<option ng-repeat="client in clients" ng-value="client.ID">{{ client.user_nicename}} </option>
									</select>
								</div>
							</div>

							<div class="col-lg-10 col-md-10" ng-if="workout.selectedDay.selectedClient">

								<div class="tab-content">
									<div class="tab-pane fade show active" id="lorem" role="tabpanel">
										<div class="container">
											<div class="row">
												<div class="col-lg-12 col-md-12">
													<p>Client Focus: <span>Fat Loss</span></p>
												</div>
												<div class="col-lg-2 col-md-2 assign-workout select-date-workout">
													<input type="text" datepicker ng-model="workout.selectedDay.selectedClient.date_availability"/>
												</div>
												<div class="col-lg-4 col-md-4">
													<ul class="workout-exercise-lists">
														<li class="workout-exercise-item" ng-repeat="ex in workout.selectedDay.selectedClient.exercises track by $index">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>{{ $index + 1 }}</label></span></td>
																<td>
																	<select>
																		<option>{{ ex.exer_body_part }}</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>{{ ex.exer_type }}</option>
																	</select>
																</td>
															</table>
														</li>
													</ul>
												</div>
												<div class="col-lg-6 col-md-6 assign-workout assign-workout-sets" ng-class="{'more-sets': workoutMaxSet > 3}">
													<div class="container">
														<div class="row">
															<div class="col-lg-4 col-md-4" ng-repeat="numSet in []|range:workoutMaxSet">
																<p>SET {{ numSet + 1}}</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Weight</th>
																		</tr>
																		<tr ng-repeat="ex in workout.selectedDay.selectedClient.exercises track by $index">
																			<td><input ng-disabled="clientExerciseSets[$index] <=  numSet" class="set-val" type="text" ng-model="ex.assignment_sets[numSet].weight"></td>
																		</tr>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="ipsum" role="tabpanel">
										<div class="container">
											<div class="row">
												<div class="col-lg-4 col-md-4 assign-focus">
													<p>Client Focus: <span>Fat Loss</span></p>
													<ul class="workout-exercise-lists">
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>1</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 2</option>
																	</select>
																</td>
															</table>
														</li>
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>2</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 2</option>
																	</select>
																</td>
															</table>
														</li>
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>3</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 2</option>
																	</select>
																</td>
															</table>
														</li>
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>4</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 2</option>
																	</select>
																</td>
															</table>
														</li>
													</ul>
												</div>
												<div class="col-lg-4 col-md-4 assign-workout">
													<p>Last 2 completed sets</p>

													<div class="container">
														<div class="row">
															<div class="col-lg-6 col-md-6">
																<div class="last-completed-sets">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Sets</th>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="10"></td>
																			<td><input class="set-prev-val" type="text" value="75LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="2"></td>
																			<td><input class="set-prev-val" type="text" value="8"></td>
																			<td><input class="set-prev-val" type="text" value="115LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="4"></td>
																			<td><input class="set-prev-val" type="text" value="12"></td>
																			<td><input class="set-prev-val" type="text" value="95LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="9"></td>
																			<td><input class="set-prev-val" type="text" value="210LBS"></td>
																		</tr>

																	</table>
																</div>
															</div>
															<div class="col-lg-6 col-md-6">
																<div class="last-completed-sets">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Sets</th>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="10"></td>
																			<td><input class="set-prev-val" type="text" value="75LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="2"></td>
																			<td><input class="set-prev-val" type="text" value="8"></td>
																			<td><input class="set-prev-val" type="text" value="115LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="4"></td>
																			<td><input class="set-prev-val" type="text" value="12"></td>
																			<td><input class="set-prev-val" type="text" value="95LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="9"></td>
																			<td><input class="set-prev-val" type="text" value="210LBS"></td>
																		</tr>

																	</table>
																</div>
															</div>
														</div>
													</div>

												</div>
												<div class="col-lg-4 col-md-4 assign-workout">

													<div class="container">
														<div class="row">
															<div class="col-lg-4 col-md-4">
																<p>SET 1</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>

																	</table>
																</div>
															</div>
															<div class="col-lg-4 col-md-4">
																<p>SET 2</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>

																	</table>
																</div>
															</div>
															<div class="col-lg-4 col-md-4">
																<p>SET 3</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>

																	</table>
																</div>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="dolor" role="tabpanel">
										<div class="container">
											<div class="row">
												<div class="col-lg-4 col-md-4 assign-workout">
													<p>Client Focus: <span>Fat Loss</span></p>
													<ul class="workout-exercise-lists">
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>1</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 3</option>
																	</select>
																</td>
															</table>
														</li>
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>2</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 3</option>
																	</select>
																</td>
															</table>
														</li>
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>3</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 3</option>
																	</select>
																</td>
															</table>
														</li>
														<li class="workout-exercise-item">
															<table class="workout-exercise-options">
																<td><span class="exercise-number"><label>4</label></span></td>
																<td>
																	<select>
																		<option>Body Part</option>
																	</select>
																</td>
																<td>
																	<select>
																		<option>IMPL 3</option>
																	</select>
																</td>
															</table>
														</li>
													</ul>
												</div>
												<div class="col-lg-4 col-md-4 assign-workout">
													<p>Last 2 completed sets</p>

													<div class="container">
														<div class="row">
															<div class="col-lg-6 col-md-6">
																<div class="last-completed-sets">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Sets</th>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="10"></td>
																			<td><input class="set-prev-val" type="text" value="75LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="2"></td>
																			<td><input class="set-prev-val" type="text" value="8"></td>
																			<td><input class="set-prev-val" type="text" value="115LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="4"></td>
																			<td><input class="set-prev-val" type="text" value="12"></td>
																			<td><input class="set-prev-val" type="text" value="95LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="9"></td>
																			<td><input class="set-prev-val" type="text" value="210LBS"></td>
																		</tr>

																	</table>
																</div>
															</div>
															<div class="col-lg-6 col-md-6">
																<div class="last-completed-sets">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Sets</th>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="10"></td>
																			<td><input class="set-prev-val" type="text" value="75LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="2"></td>
																			<td><input class="set-prev-val" type="text" value="8"></td>
																			<td><input class="set-prev-val" type="text" value="115LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="4"></td>
																			<td><input class="set-prev-val" type="text" value="12"></td>
																			<td><input class="set-prev-val" type="text" value="95LBS"></td>
																		</tr>
																		<tr>
																			<td><input class="set-prev-val" type="text" value="3"></td>
																			<td><input class="set-prev-val" type="text" value="9"></td>
																			<td><input class="set-prev-val" type="text" value="210LBS"></td>
																		</tr>

																	</table>
																</div>
															</div>
														</div>
													</div>

												</div>
												<div class="col-lg-4 col-md-4 assign-workout">

													<div class="container">
														<div class="row">
															<div class="col-lg-4 col-md-4">
																<p>SET 1</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>

																	</table>
																</div>
															</div>
															<div class="col-lg-4 col-md-4">
																<p>SET 2</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>

																	</table>
																</div>
															</div>
															<div class="col-lg-4 col-md-4">
																<p>SET 3</p>
																<div class="assign-sets-wrapper">
																	<table class="last-sets" style="width: 100% !important;">
																		<tr>
																			<th>Reps</th>
																			<th>Weight</th>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" value=""></td>
																			<td><input class="set-val" type="text" value=""></td>
																		</tr>

																	</table>
																</div>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="btn-add-workout">
						<button type="submit">> UPDATE</button>
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" name="updateWorkoutForm" value="test" id="idWorkoutForm" />
	</form>
</div>