
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
	var clients = <?php echo json_encode(workOutGetClients()) ?>;
	console.log(clients);
	var app = angular.module('app', []);

	app.controller('Controller', function($scope) {

		$scope.clients = clients;
		$scope.workout = {
			days: [{name:'', order:1, exercises:[{}], clients:[]}],
		};

		init();

		function init()
		{

		}

		$scope.newWorkOutDay = function ()
		{
			var count = $scope.workout.days.length + 1;
			$scope.workout.days.push({order:count, exercises:[{}], clients:[]});
		};

		$scope.newExercise = function() {
			$scope.workout.selectedDay.exercises.push({})
		};

		$scope.selectDay = function(day)
		{
			console.log(day);
			$scope.workout.selectedDay = day;
		};

		$scope.selectClient = function(client) {
			$scope.workout.selectedDay.selectedClient = client;
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

		$scope.submit = function()
		{
			console.log($scope.workout);
		//	$("#idForm").submit();
		};

		$scope.remove = function(exercise)
		{
			var idx = $scope.workout.selectedDay.exercises.indexOf(exercise);

			$scope.workout.selectedDay.exercises.splice(idx,1);
			console.log(exercise);
			console.log(idx);

		}

//		setTimeout(function(){
//		//	idSelectClient
//			$('#idSelectClient').change(function(){
//				alert( $(this).find("option:selected").attr('value') );
//			});
//			console.log('llf');
//		}, 1000)
//

		$("#idForm").submit(function (e) {
			//	e.preventDefault();
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
				   ng-click="selectDay(day)"
				>Day {{day.order}} - {{day.name}}</a>
			</div>
		</nav>
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
									<a href="#lorem" class="nav-link" data-toggle="tab" role="tab" ng-click="selectClient(client)">{{ client.user_nicename }}</a>
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
																	<option>IMPL 1</option>
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
																	<option>IMPL 1</option>
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
																	<option>IMPL 1</option>
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
																	<option>IMPL 1</option>
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