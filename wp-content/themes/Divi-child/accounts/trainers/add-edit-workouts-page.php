<?php workOutExerciseOptions(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
	var clients = <?php echo json_encode(workOutGetClients()) ?>;
	var exerciseOptions = <?php echo json_encode(workOutExerciseOptions()) ?>;
	var exerciseSQoptions = <?php echo json_encode(workOutExerciseStrengthQualitiesOptions()) ?>;

	var app = angular.module('app', []);

	app.controller('Controller', function($scope) {

		$scope.clients = clients;
		$scope.exerciseOptions = exerciseOptions;
		$scope.exerciseSQoptions = exerciseSQoptions;

		console.log($scope.exerciseOptions);
		$scope.workout = {
			days: [{name:'', order:1, exercises:[generateNewExercise()], clients:[]}],
		};

		init();

		function init()
		{
			console.log('-----------INIT--------------');
			selectDay($scope.workout.days[0]);
			console.log('-----------END INIT--------------');
		}

		$scope.newWorkOutDay = function ()
		{
			var count = $scope.workout.days.length + 1;
			$scope.workout.days.push({order:count, exercises:[generateNewExercise()], clients:[]});

			var countDays = $scope.workout.days.length;
			$scope.workout.selectedDay = $scope.workout.days[countDays - 1];
		};

		$scope.newExercise = function() {
			$scope.workout.selectedDay.exercises.push(generateNewExercise());
		};

		$scope.onSelectDay = function(day)
		{
			selectDay(day);
		};

		$scope.onLeaveDay = function()
		{
			console.log('your leaving on the current form')
			console.log($scope.workout.selectedDay);

			for (var i in $scope.workout.days)
			{
				var day = $scope.workout.days[i];

				if (day.order == $scope.workout.selectedDay.order)
				{
					$scope.workout.days[i] = angular.copy($scope.workout.selectedDay);

					for (var x in $scope.workout.days[i].clients)
					{
						var client = $scope.workout.days[i].clients[x];

						if ($scope.workout.selectedDay.selectedClient && client.ID == $scope.workout.selectedDay.selectedClient.id)
						{
							console.log('selected client');
							console.log($scope.workout.selectedDay.selectedClient);
							$scope.workout.days[i].clients[x] = $scope.workout.selectedDay.selectedClient;
							break;
						}
					}

					break;
				}
			}
		};

		$scope.selectClient = function(client) {
			$scope.workout.selectedDay.selectedClient = client;
		};

		$scope.isActive = function(day) {

			if ($scope.workout.selectedDay.order == day.order) {
				return true;
			}

			return false;
		};

		$scope.isClientActive = function(client) {

			if ($scope.workout.selectedDay.selectedClient && $scope.workout.selectedDay.selectedClient.ID == client.ID) {
				return true;
			}

			return false;
		};

		$scope.$watch('selectedClient', function(val)
		{
			console.log(val);
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

					}

					break;
				}
			}

		});

		$scope.remove = function(exercise)
		{
			var idx = $scope.workout.selectedDay.exercises.indexOf(exercise);

			$scope.workout.selectedDay.exercises.splice(idx,1);
			console.log(exercise);
			console.log(idx);

		};

		function generateNewExercise()
		{
			return {exerciseOptions: angular.copy($scope.exerciseOptions), exerciseSQoptions: angular.copy($scope.exerciseSQoptions)};
		}

		function selectDay(day)
		{
			$scope.workout.selectedDay = angular.copy(day);

			if ($scope.workout.selectedDay.clients)
			{
				$scope.workout.selectedDay.selectedClient = day.clients[0];
			}
		}

		$("#idForm").submit(function (e) {
			//e.preventDefault();

			console.log($scope.workout);

			delete $scope.workout.selectedDay;

			for(var i in $scope.workout.days) {
				var day = $scope.workout.days[i];

				delete day.selectedClient;
				for(var e in day.exercises)
				{
					var ex = day.exercises[e];
					//delete ex.selectedPart;
					delete ex.exerciseOptions;
					delete ex.exerciseSQoptions;
				}
			}

			console.log($scope.workout);
			$('#idWorkoutForm').val(JSON.stringify($scope.workout));
			return true;

		});
	});
</script>

<div class="main-content padding20 matchHeight" ng-app="app" ng-controller="Controller" ng-cloak>

	<form id="idForm" action="/trainer/?data=workouts" method="POST">

		<div class="container trainer-header-section">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<span class="workout-day-name">
						<label>Workout Name: </label>
						<input type="text" ng-model="workout.name">
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
				>Day {{day.order}} - {{day.name}}</a>
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
								<input type="text" ng-model="workout.selectedDay.name">
							</span>
								</div>
								<div class="col-lg-6 col-md-6">
									<ul class="workout-btn-actions">
										<li><a href="#">Duplicate</a></li>
										<li><a href="javascript:void(0)">Delete</a></li>
									</ul>
								</div>
							</div>
						</div>

						<ul class="workout-exercise-lists">
							<li class="workout-exercise-item" ng-repeat="exercise in workout.selectedDay.exercises track by $index">
								<table class="workout-exercise-options">
									<td><span class="exercise-number"><label>{{$index + 1}}</label></span></td>
									<td>
										<select ng-model="exercise.selectedPart" ng-options="opt.part for opt in exercise.exerciseOptions">
											<option value="">Body Part</option>
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
								<span class="exercise-btn-action"><a href="#">Duplicate</a><span>
									</td>
									<td>
								<span class="exercise-btn-action"><a href="javascript:void(0)" ng-click="remove(exercise)">Delete</a><span>
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
										   data-toggle="tab"
										   role="tab"
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
												<div class="col-lg-4 col-md-4 assign-workout">
													<p>Client Focus: <span>Fat Loss</span></p>
													<select ng-model="workout.selectedDay.selectedClient.day_availability">
														<option value="" selected disabled hidden>Availability</option>
														<option value="1">Monday</option>
														<option value="2">Tuesday</option>
														<option value="3">Wednesday</option>
														<option value="4">Thursday</option>
														<option value="5">Friday</option>
														<option value="6">Saturday</option>
														<option value="7">Sunday</option>
													</select>
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
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_rep_1"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_weight_1"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_rep_2"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_weight_2"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_rep_3"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_weight_3"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_rep_4"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set1_weight_4"></td>
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
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_rep_1"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_weight_1"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_rep_2"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_weight_2"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_rep_3"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_weight_3"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_rep_4"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set2_weight_4"></td>
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
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_rep_1"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_weight_1"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_rep_2"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_weight_2"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_rep_3"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_weight_3"></td>
																		</tr>
																		<tr>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_rep_4"></td>
																			<td><input class="set-val" type="text" ng-model="workout.selectedDay.selectedClient.set3_weight_4"></td>
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
						<button type="submit">+ SUBMIT</button>
					</div>
				</div>
			</div>
		</div>
		
		<input type="hidden" name="workoutForm" value="test" id="idWorkoutForm" />
	</form>
</div>