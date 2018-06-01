<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );


add_filter('register','no_register_link');
function no_register_link($url){
    return '';
}

add_action('login_enqueue_scripts', 'strength_mextrix_login_scripts', 10);
function strength_mextrix_login_scripts(){
	wp_enqueue_script( 'strength_mextrix.js', get_stylesheet_directory_uri() . '/js/strength_metrix.js', array( 'jquery' ), 1.0 );
	wp_enqueue_style( 'strength_mextrix_login.css', get_stylesheet_directory_uri() . '/css/strength_mextrix_login.css', array(  ) );
}


/*
*
* Check what is the buddyPress member type
* Redirect them to a specific pages. 
*
*/

function sm_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		
		$member_type = bp_get_member_type($user->data->ID);

		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;

		} else if( $member_type == 'client' ){

			return home_url() . '/client';

		} else if( $member_type == 'trainer' ){

			return home_url() . '/trainer';

		} else if( $member_type == 'gym' ){

			return home_url() . '/gym';

		}


	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'sm_login_redirect', 10, 3 );


function test()
{
	global $wpdb;

	$querystr = "SELECT * FROM workout_tbl";

	$pageposts = $wpdb->get_results($querystr, OBJECT);

	print_r($pageposts);
}

function test2()
{
	global $wpdb;

	$get_user_ids = $wpdb->get_col( "SELECT u.ID FROM {$wpdb->users} u INNER JOIN 
{$wpdb->prefix}term_relationships r ON u.ID = r.object_id WHERE u.user_status = 0 AND r.term_taxonomy_id = 71");

	print_r($get_user_ids);
}

function helperGetCurrentDate()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	return Carbon\Carbon::now();

}

function workOutAdd($data)
{
	global $wpdb;

	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	$workout = json_decode(preg_replace('/\\\"/',"\"", $data['workoutForm']), true);
	$weekDays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

	$wpdb->insert('workout_tbl',
		array(
			'workout_name' => $workout['name'],
			'workout_trainer_ID' => $data['workout_trainer_ID']
		),
		array(
			'%s',
			'%d'
		)
	);

	$workOutId = (int) $wpdb->insert_id;

	if ($workout['days'])
	{
		foreach($workout['days'] as $d)
		{
			$wpdb->insert('workout_days_tbl',
				array(
					'wday_workout_ID' => $workOutId,
					'wday_name' => $d['name'],
					'wday_order' => (int) $d['order'],
					'wday_created_at' => date("Y-m-d H:i:s")
				)
			);

			$dayId = $wpdb->insert_id;

			if ($d['exercises'])
			{

				foreach($d['exercises'] as $ex)
				{
					$exercise = [
						'exer_day_ID' => $dayId,
						'exer_workout_ID' => $workOutId,
						'hash'	=> $ex['hash']
					];

					if (isset($ex['selectedPart']))
					{
						$exercise['exer_body_part'] = $ex['selectedPart']['part'];

						if (isset($ex['selectedPart']['selectedType']))
						{
							$exercise['exer_type'] = $ex['selectedPart']['selectedType']['type'];

							if (isset($ex['selectedPart']['selectedType']['selectedExercise1']))
							{
								$exercise['exer_exercise_1'] = $ex['selectedPart']['selectedType']['selectedExercise1'];
							}

							if (isset($ex['selectedPart']['selectedType']['selectedExercise2']))
							{
								$exercise['exer_exercise_2'] = $ex['selectedPart']['selectedType']['selectedExercise2'];
							}

							if (isset($ex['selectedPart']['selectedType']['selectedImplementation1']))
							{
								$exercise['exer_impl1'] = $ex['selectedPart']['selectedType']['selectedImplementation1'];
							}
						}
					}

					if (isset($ex['selectedSQ']))
					{
						$exercise['exer_sq'] = $ex['selectedSQ']['name'];

						if (isset($ex['selectedSQ']['selectedSet']))  {
							$exercise['exer_sets'] = $ex['selectedSQ']['selectedSet'];
						}

						if (isset($ex['selectedSQ']['selectedRep']))  {
							$exercise['exer_rep'] = $ex['selectedSQ']['selectedRep'];
						}

						if (isset($ex['selectedSQ']['selectedTempo']))  {
							$exercise['exer_tempo'] = $ex['selectedSQ']['selectedTempo'];
						}

						if (isset($ex['selectedSQ']['selectedRest']))  {
							$exercise['exer_rest'] = $ex['selectedSQ']['selectedRest'];
						}
					}

					$wpdb->insert('workout_exercises_tbl', $exercise);

				}
			}

			/* inserting clients */
			if($d['clients'])
			{

				foreach($d['clients'] as $client)
				{

					if ((int) $client['day_availability'] > 0)
					{
						$dNumber = ((int) $client['day_availability']) - 1;
						$scheduleDate = new \Carbon\Carbon($weekDays[$dNumber]);
						$wpdb->insert('workout_day_clients_tbl',
							array(
								'workout_client_dayID' => (int) $dayId,
								'workout_client_workout_ID' => (int) $workOutId,
								'workout_clientID' => (int) $client['ID'],
								'workout_day_availability' => (int) $client['day_availability'],
								'workout_client_schedule' => $scheduleDate->format('Y-m-d h:i:s')
							)
						);

						foreach ($client['exercises'] as $ex)
						{
							$exQuery = "SELECT * FROM workout_exercises_tbl WHERE hash='{$ex['hash']}' LIMIT 1";
							$exerciseResult = $wpdb->get_results($exQuery, OBJECT);

							if (count($exerciseResult) > 0)
							{
								$m = $exerciseResult[0];
			
								$wpdb->insert('workout_client_exercise_assignments',
									array(
										'exercise_id' => (int) $m->exer_ID,
										'client_id' => (int) $client['ID']
									)
								);

								$assignmentId = $wpdb->insert_id;

								if (isset($ex['assignment_sets']))
								{
									foreach ($ex['assignment_sets'] as $key => $set)
									{
										$wpdb->insert('workout_client_exercise_assignment_sets',
											array(
												'assignment_id' => (int) $assignmentId,
												'reps' => $set['reps'],
												'weight' => $set['weight'],
												'seq' => $key + 1
											)
										);
									}
								}
							}
						}
					}
				}
			}
		}
	}

}

function workOutUpdate($data)
{
	global $wpdb;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	$workout = json_decode(preg_replace('/\\\"/',"\"", $data['updateWorkoutForm']), true);
	//dd($workout);
	$weekDays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	//dd($workout);

	$wpdb->update(
		'workout_tbl',
		array(
			'workout_name' => $workout['workout_name']
		),
		array( 'workout_ID' => $workout['workout_ID'] )
	);

	if (isset($workout['days']))
	{
		/* update days */

		foreach($workout['days'] as $d)
		{

			if (isset($d['wday_ID']) && $d['isDelete']) {

				/* delete exercises and clients */

				$wpdb->delete(
					'workout_day_clients_tbl',
					array( 'workout_client_dayID' => $d['wday_ID'] )
				);

				$wpdb->delete(
					'workout_exercises_tbl',
					array( 'exer_day_ID' => $d['wday_ID'] )
				);

				$wpdb->delete(
					'workout_days_tbl',
					array( 'wday_ID' => $d['wday_ID'] )
				);

			} else if (isset($d['wday_ID'])) {

				$wpdb->update(
					'workout_days_tbl',
					array(
						'wday_name' => $d['wday_name'],
						'wday_order' => $d['wday_order']
					),
					array( 'wday_ID' => $d['wday_ID'] )
				);

				if (isset($d['clients']))
				{

					foreach($d['clients'] as $client)
					{
						$dNumber = ((int) $client['day_availability']) - 1;
						$scheduleDate = new \Carbon\Carbon($weekDays[$dNumber]);
						$clientQuery = "SELECT * FROM workout_day_clients_tbl WHERE workout_client_dayID={$d['wday_ID']} AND workout_client_workout_ID={$workout['workout_ID']} AND workout_clientID={$client['ID']}";
						$result = $wpdb->get_results($clientQuery, OBJECT);

						if(count($result) <= 0)
						{
							// insert the new client id
							$wpdb->insert('workout_day_clients_tbl',
								array(
									'workout_client_dayID' => (int) $d['wday_ID'],
									'workout_client_workout_ID' => (int) $workout['workout_ID'],
									'workout_clientID' => (int) $client['ID'],
									'workout_day_availability' => (int) $client['day_availability'],
									'workout_client_schedule' => $scheduleDate->format('Y-m-d h:i:s')
								)
							);
						} else {
							$wpdb->update(
								'workout_day_clients_tbl',
								array(
									//'workout_client_dayID' => (int) $d['wday_ID'],
									//'workout_client_workout_ID' => (int) $workout['workout_ID'],
									//'workout_clientID' => (int) $client['ID'],
									'workout_day_availability' => (int) $client['day_availability'],
									'workout_client_schedule' => $scheduleDate->format('Y-m-d h:i:s')
								),
								array( 'workout_clientID' => $client['ID'],
									'workout_client_dayID' => (int) $d['wday_ID'],
									'workout_client_workout_ID' => (int) $workout['workout_ID'],
									)
							);
						}
					}
				}

				if ($d['exercises'])
				{

					foreach($d['exercises'] as $ex)
					{

						// check if the exercise exist

						if (isset($ex['exer_ID']))
						{

							$exerciseQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_day_ID={$d['wday_ID']} AND exer_workout_ID={$workout['workout_ID']} AND exer_ID={$ex['exer_ID']} LIMIT 1";
							$result = $wpdb->get_results($exerciseQuery, ARRAY_A);


							if(count($result) > 0)
							{
								$exercise = $result[0];

								if (isset($ex['selectedPart']))
								{
									$exercise['exer_body_part'] = $ex['selectedPart']['part'];

									if (isset($ex['selectedPart']['selectedType']))
									{
										$exercise['exer_type'] = $ex['selectedPart']['selectedType']['type'];

										if (isset($ex['selectedPart']['selectedType']['selectedExercise1']))
										{
											$exercise['exer_exercise_1'] = $ex['selectedPart']['selectedType']['selectedExercise1'];
										}

										if (isset($ex['selectedPart']['selectedType']['selectedExercise2']))
										{
											$exercise['exer_exercise_2'] = $ex['selectedPart']['selectedType']['selectedExercise2'];
										}

										if (isset($ex['selectedPart']['selectedType']['selectedImplementation1']))
										{
											$exercise['exer_impl1'] = $ex['selectedPart']['selectedType']['selectedImplementation1'];
										}
									}
								}


								if (isset($ex['selectedSQ']))
								{
									$exercise['exer_sq'] = $ex['selectedSQ']['name'];

									if (isset($ex['selectedSQ']['selectedSet']))  {
										$exercise['exer_sets'] = $ex['selectedSQ']['selectedSet'];
									}

									if (isset($ex['selectedSQ']['selectedRep']))  {
										$exercise['exer_rep'] = $ex['selectedSQ']['selectedRep'];
									}

									if (isset($ex['selectedSQ']['selectedTempo']))  {
										$exercise['exer_tempo'] = $ex['selectedSQ']['selectedTempo'];
									}

									if (isset($ex['selectedSQ']['selectedRest']))  {
										$exercise['exer_rest'] = $ex['selectedSQ']['selectedRest'];
									}
								}

								$wpdb->update('workout_exercises_tbl', $exercise, array('exer_ID' => $ex['exer_ID']));

							}

						} else {

							$exercise = [
								'exer_day_ID' => $d['wday_ID'],
								'exer_workout_ID' => $workout['workout_ID']
							];

							if (isset($ex['selectedPart']))
							{
								$exercise['exer_body_part'] = $ex['selectedPart']['part'];

								if (isset($ex['selectedPart']['selectedType']))
								{
									$exercise['exer_type'] = $ex['selectedPart']['selectedType']['type'];

									if (isset($ex['selectedPart']['selectedType']['selectedExercise1']))
									{
										$exercise['exer_exercise_1'] = $ex['selectedPart']['selectedType']['selectedExercise1'];
									}

									if (isset($ex['selectedPart']['selectedType']['selectedExercise2']))
									{
										$exercise['exer_exercise_2'] = $ex['selectedPart']['selectedType']['selectedExercise2'];
									}

									if (isset($ex['selectedPart']['selectedType']['selectedImplementation1']))
									{
										$exercise['exer_impl1'] = $ex['selectedPart']['selectedType']['selectedImplementation1'];
									}
								}
							}


							if (isset($ex['selectedSQ']))
							{
								$exercise['exer_sq'] = $ex['selectedSQ']['name'];

								if (isset($ex['selectedSQ']['selectedSet']))  {
									$exercise['exer_sets'] = $ex['selectedSQ']['selectedSet'];
								}

								if (isset($ex['selectedSQ']['selectedRep']))  {
									$exercise['exer_rep'] = $ex['selectedSQ']['selectedRep'];
								}

								if (isset($ex['selectedSQ']['selectedTempo']))  {
									$exercise['exer_tempo'] = $ex['selectedSQ']['selectedTempo'];
								}

								if (isset($ex['selectedSQ']['selectedRest']))  {
									$exercise['exer_rest'] = $ex['selectedSQ']['selectedRest'];
								}
							}

							$wpdb->insert('workout_exercises_tbl', $exercise);
						}

					}
				}

			} else { /* insert if there is no existing day */

				$wpdb->insert('workout_days_tbl',
					array(
						'wday_workout_ID' => $workout['workout_ID'],
						'wday_name' => $d['wday_name'],
						'wday_order' => (int) $d['wday_order']
					)
				);
				$dayId = $wpdb->insert_id;

				if (isset($d['clients']))
				{

					foreach($d['clients'] as $client)
					{
						$dNumber = ((int) $client['day_availability']) - 1;
						$scheduleDate = new \Carbon\Carbon($weekDays[$dNumber]);
						// insert the new client id
						$wpdb->insert('workout_day_clients_tbl',
							array(
								'workout_client_dayID' => $dayId,
								'workout_client_workout_ID' => (int) $workout['workout_ID'],
								'workout_clientID' => (int) $client['ID'],
								'workout_day_availability' => (int) $client['day_availability'],
								'workout_client_schedule' => $scheduleDate->format('Y-m-d h:i:s')
							)
						);
					}

				}

				if ($d['exercises'])
				{

					foreach($d['exercises'] as $ex)
					{

						// check if the exercise exist

						$exercise = [
							'exer_day_ID' => $dayId,
							'exer_workout_ID' => $workout['workout_ID']
						];

						if (isset($ex['selectedPart']))
						{
							$exercise['exer_body_part'] = $ex['selectedPart']['part'];

							if (isset($ex['selectedPart']['selectedType']))
							{
								$exercise['exer_type'] = $ex['selectedPart']['selectedType']['type'];

								if (isset($ex['selectedPart']['selectedType']['selectedExercise1']))
								{
									$exercise['exer_exercise_1'] = $ex['selectedPart']['selectedType']['selectedExercise1'];
								}

								if (isset($ex['selectedPart']['selectedType']['selectedExercise2']))
								{
									$exercise['exer_exercise_2'] = $ex['selectedPart']['selectedType']['selectedExercise2'];
								}

								if (isset($ex['selectedPart']['selectedType']['selectedImplementation1']))
								{
									$exercise['exer_impl1'] = $ex['selectedPart']['selectedType']['selectedImplementation1'];
								}
							}
						}


						if (isset($ex['selectedSQ']))
						{
							$exercise['exer_sq'] = $ex['selectedSQ']['name'];

							if (isset($ex['selectedSQ']['selectedRep']))  {
								$exercise['exer_rep'] = $ex['selectedSQ']['selectedRep'];
							}

							if (isset($ex['selectedSQ']['selectedTempo']))  {
								$exercise['exer_tempo'] = $ex['selectedSQ']['selectedTempo'];
							}

							if (isset($ex['selectedSQ']['selectedRest']))  {
								$exercise['exer_rest'] = $ex['selectedSQ']['selectedRest'];
							}
						}

						$wpdb->insert('workout_exercises_tbl', $exercise);

					}
				}
			}
		}
	}


}

function workOutGetClients()
{
	global $wpdb;
	$querystr = "SELECT * FROM wp_users";
	$users = $wpdb->get_results($querystr, OBJECT);
	$outputList = [];

	foreach($users as $user) {
		if(bp_get_member_type($user->ID) == 'client') {
			$outputList[] = $user;
		}
	}

	return $outputList;
}

function workOutUserList($userId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_tbl WHERE workout_trainer_ID =".$userId." ORDER by workout_ID desc";
	$workouts = $wpdb->get_results($querystr, OBJECT);
	//dd($workouts);
	return $workouts;
}

function workoutGetClientWorkouts($clientId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$todayQuery = "SELECT DISTINCT(workout_client_workout_ID), workout_client_dayID FROM workout_day_clients_tbl WHERE DATE(`workout_client_schedule`) = CURDATE() AND workout_clientID={$clientId}";
	$todayClientWorkouts = $wpdb->get_results($todayQuery, OBJECT);
	$upcomingWorkouts = [];

	foreach ($todayClientWorkouts as $k => $w)
	{
		$workoutQuery = "SELECT * FROM workout_tbl WHERE workout_ID=".$w->workout_client_workout_ID." LIMIT 1";
		$result = $wpdb->get_results($workoutQuery, OBJECT);

		if (count($result) > 0)
		{
			$todayClientWorkouts[$k]->workout = $result[0];
		}

		$dayQuery = "SELECT * FROM workout_days_tbl WHERE wday_ID=".$w->workout_client_dayID." LIMIT 1";
		$result = $wpdb->get_results($dayQuery, OBJECT);

		if (count($result) > 0)
		{
			$todayClientWorkouts[$k]->day = $result[0];
		}
	}

	$nextQuery = "SELECT DISTINCT(workout_client_workout_ID), workout_client_dayID FROM workout_day_clients_tbl WHERE DATE(`workout_client_schedule`) > CURDATE() AND workout_clientID={$clientId}";
	$nextClientWorkouts = $wpdb->get_results($nextQuery, OBJECT);

	foreach ($nextClientWorkouts as $k => $w)
	{
		$workoutQuery = "SELECT * FROM workout_tbl WHERE workout_ID=".$w->workout_client_workout_ID." LIMIT 1";
		$result = $wpdb->get_results($workoutQuery, OBJECT);

		if (count($result) > 0)
		{
			$nextClientWorkouts[$k]->workout = $result[0];
		}

		$dayQuery = "SELECT * FROM workout_days_tbl WHERE wday_ID=".$w->workout_client_dayID." LIMIT 1";
		$result = $wpdb->get_results($dayQuery, OBJECT);

		if (count($result) > 0)
		{
			$nextClientWorkouts[$k]->day = $result[0];
		}
	}

	$output = [
		'todayWorkouts' => $todayClientWorkouts,
		'upcomingWorkouts' => $nextClientWorkouts
	];

//	dd($output);
	return $output;
}

function workOutGet($workoutId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	
	// get workout
	$querystr = "SELECT * FROM workout_tbl WHERE workout_ID=".$workoutId." LIMIT 1";
	$result = $wpdb->get_results($querystr, ARRAY_A);

	if(count($result) >= 1)
	{
		$workout = $result[0];

		// get workout days
		$querystr = "SELECT * FROM workout_days_tbl WHERE wday_workout_ID=".$workout['workout_ID'];
		$days = $wpdb->get_results($querystr, ARRAY_A);

		foreach($days as $key => $d)
		{
			// get workout exercises
			$exercisesQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_day_ID=".$d['wday_ID'];
			$exercises = $wpdb->get_results($exercisesQuery, ARRAY_A);
		//	dd($exercises);
			// get workout assigned clients
			$clientsQuery = "SELECT * FROM workout_day_clients_tbl WHERE workout_client_dayID=".$d['wday_ID'];
			$clients = $wpdb->get_results($clientsQuery, ARRAY_A);

			$userClients = [];

			foreach($clients as $c)
			{
				// get clients from users
				$userQuery = "SELECT * FROM wp_users WHERE ID=".$c['workout_clientID'] . " LIMIT 1";
				$userResult = $wpdb->get_results($userQuery, ARRAY_A);

				if(count($userResult) >= 1)
				{
					$client = $userResult[0];
					$client['day_availability'] = $c['workout_day_availability'];
					$client['exercises'] = [];

					foreach ($exercises as $ex)
					{
						$assignQuery = "SELECT * FROM workout_client_exercise_assignments WHERE client_id=" . (int) $client['ID'] . " AND exercise_id=". (int) $ex['exer_ID'] . " LIMIT 1";
						$assignResult = $wpdb->get_results($assignQuery, ARRAY_A);

						if (count($assignResult) > 0) {

							$assignSetsQuery = "SELECT * FROM workout_client_exercise_assignment_sets WHERE assignment_id=" . $assignResult[0]['id'];
							$assignSetsResult = $wpdb->get_results($assignSetsQuery, ARRAY_A);

							$ex['assignment_sets'] = $assignSetsResult;
							$client['exercises'][] = $ex;
						}
					}

					$userClients[] = $client;
				}
			}

			$days[$key]['clients'] = $userClients;
			$days[$key]['exercises'] = $exercises;
		}

		$workout['days'] = $days;
		
		return $workout;
	}

	return null;
}

function workoutClientWorkoutWithDay($workoutId, $dayId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_day_clients_tbl WHERE workout_client_dayID={$workoutId} AND workout_client_workout_ID={$dayId} LIMIT 1";
	$result = $wpdb->get_results($querystr, OBJECT);
	//dd($result);
	if (count($result) >= 1)
	{
		$clientWorkout = $result[0];

		$queryWorkout = "SELECT * FROM workout_tbl WHERE workout_ID={$clientWorkout->workout_client_workout_ID} LIMIT 1";
		$workout = $wpdb->get_results($queryWorkout, OBJECT);

		if (count($workout) >= 1)
		{
			$clientWorkout->workout = $workout[0];
		}

		$queryDay = "SELECT * FROM workout_days_tbl WHERE wday_ID={$clientWorkout->workout_client_dayID} LIMIT 1";
		$day = $wpdb->get_results($queryDay, OBJECT);

		if (count($workout) >= 1)
		{
			$clientWorkout->day = $day[0];
		}

		$queryExercises =  "SELECT * FROM workout_exercises_tbl WHERE exer_workout_ID={$clientWorkout->workout_client_workout_ID}";
		$exercises = $wpdb->get_results($queryExercises, OBJECT);

		if (count($exercises) >= 1) {
			$clientWorkout->exercises = $exercises;
		}

	}

	return $clientWorkout;
}

function workOutDelete($workoutId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_tbl WHERE workout_ID=".$workoutId." LIMIT 1";
	$result = $wpdb->get_results($querystr, ARRAY_A);

	if(count($result) >= 1)
	{
		$workout = $result[0];

		$querystr = "SELECT * FROM workout_days_tbl WHERE wday_workout_ID=".$workout['workout_ID'];
		$days = $wpdb->get_results($querystr, ARRAY_A);

		foreach($days as $key => $d)
		{
			/* days table name workout_days_tbl with primary key > wday_ID */

			/* delete workout_exercises_tbl target exer_day_ID */
			$wpdb->delete('workout_exercises_tbl', array('exer_day_ID' => (int) $d['wday_ID']));

			/* delete workout_day_clients_tbl target workout_client_dayID*/
			$wpdb->delete('workout_day_clients_tbl', array('workout_client_dayID' => (int) $d['wday_ID']));

			/* delete workout_client_exercises_logs target day_id */
			$wpdb->delete('workout_client_exercises_logs', array('day_id' => (int) $d['wday_ID']));
		}

		/* delete workout_days_tbl target wday_ID */
		$wpdb->delete('workout_days_tbl', array('wday_workout_ID' => (int) $workout['workout_ID']));

		/* delete workout */
		$wpdb->delete('workout_tbl', array('workout_ID' => (int) $workout['workout_ID']));

		return $workout;
	}

	return null;
}

function workOutExerciseOptions()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_exercise_options_tbl";
	$options = $wpdb->get_results($querystr, ARRAY_A);

	foreach($options as $key => $option)
	{
		$options[$key]['options'] = json_decode($option['options']);
	}
	//dd($options);
	return $options;

}

function workOutExerciseStrengthQualitiesOptions()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_exercise_strength_qualities_tbl";
	$options = $wpdb->get_results($querystr, ARRAY_A);

	foreach($options as $key => $option)
	{
		$options[$key]['options'] = json_decode($option['options']);
	}
	//dd($options);
	return $options;
}

// END ENQUEUE PARENT ACTION


/* api action */

add_action( 'rest_api_init', 'wpc_register_wp_api_endpoints' );
function wpc_register_wp_api_endpoints() {
	register_rest_route( 'v1', 'client/get', array(
		'methods' => 'GET',
		'callback' => 'workoutClientExerciseLogs',
	));

	register_rest_route( 'v1', 'client/process', array(
		'methods' => 'POST',
		'callback' => 'workoutCreateClientSetLog',
	));

	register_rest_route( 'v1', 'hash', array(
		'methods' => 'GET',
		'callback' => 'workoutGenerateHash',
	));
}

function workoutClientExerciseLogs() {

	global $wpdb;

	$data = $_REQUEST;
	$exerciseId = (int) $data['exerciseId'];
	$userId = (int) $data['user_id'];

	$queryExercise = "SELECT * FROM workout_client_exercises_logs WHERE exercise_id={$exerciseId} AND client_id=$userId";
	$result = $wpdb->get_results($queryExercise, ARRAY_A);

	if (count($result) > 0)
	{
		$exerciseLog = $result[0];
		$querySet = "SELECT * FROM workout_client_set_logs WHERE exercise_log_id={$exerciseLog['id']} AND client_id=$userId";
		$sets = $wpdb->get_results($querySet, ARRAY_A);

		$exerciseLog['sets'] = $sets;

		return $exerciseLog;
	}

	return [];
}

function workoutCreateClientSetLog()
{

	global $wpdb;

	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	$data = json_decode( file_get_contents('php://input') , true);
	$exerciseId = (int) $data['exer_ID'];
	$userId = (int) $data['user_id'];

	/* check if it has already client exercise */

	$queryExercise = "SELECT * FROM workout_client_exercises_logs WHERE exercise_id={$exerciseId}";
	$result = $wpdb->get_results($queryExercise, ARRAY_A);

	if (count($result) > 0) // it has already exercise log
	{
		$exerciseLogId = $result[0]['id'];

	} else {

		$wpdb->insert('workout_client_exercises_logs',
			array(
				'exercise_id' => (int) $data['exer_ID'],
				'client_id'   => $userId,
				'day_id' => (int) $data['exer_day_ID'],
				'workout_id' => (int) $data['exer_workout_ID']
			)
		);

		$exerciseLogId = $wpdb->insert_id;
	}

	$seq = (int) $data['currentSet']['seq'];
	/* check if the set is exists in workout_client_set_logs */
	$querySet = "SELECT * FROM workout_client_set_logs WHERE client_id={$userId} AND exercise_log_id={$exerciseLogId} AND seq={$seq}";
	$result = $wpdb->get_results($querySet, ARRAY_A);

	if (count($result) > 0)
	{
		$currentSet = $result[0];
		// update the set
		$wpdb->update(
			'workout_client_set_logs',
			array(
				'reps' 		  => $data['currentSet']['reps'],
				'isMet' 	  => (int) $data['currentSet']['isMet'] ? true : false,
				'isDone'      => $data['currentSet']['isMet'],
			),
			array('id' => $currentSet['id'])
		);

	} else {
		// log insert exercise log into workout_client_exercises_logs

		/* insert set logs */
		$wpdb->insert('workout_client_set_logs',
			array(
				'exercise_log_id' => $exerciseLogId,
				'reps' 		  => $data['currentSet']['reps'],
				'isMet' 	  => (int) $data['currentSet']['isMet'] ? true : false,
				'isDone'      => $data['currentSet']['isMet'],
				'seq'		  => $seq,
				'client_id'   => $userId
			)
		);
	}
}

function workoutGenerateHash()
{
	$m=microtime(true);
	return ['hash' => sprintf("%8x%05x",floor($m),($m-floor($m))*1000000)];
}