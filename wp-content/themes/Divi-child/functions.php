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

function workOutAdd($data)
{
	global $wpdb;

	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	$workout = json_decode(preg_replace('/\\\"/',"\"", $data['workoutForm']), true);
	//dd($workout);
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
					'wday_order' => (int) $d['order']
				)
			);

			$dayId = $wpdb->insert_id;

			if ($d['exercises'])
			{

				foreach($d['exercises'] as $ex)
				{
					$exercise = [
						'exer_day_ID' => $dayId,
						'exer_workout_ID' => $workOutId
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

			if($d['clients'])
			{

				foreach($d['clients'] as $client)
				{
					$wpdb->insert('workout_day_clients_tbl',
						array(
							'workout_client_dayID' => (int) $dayId,
							'workout_client_workout_ID' => (int) $workOutId,
							'workout_clientID' => (int) $client['ID'],
							'workout_day_availability' => (int) $client['day_availability']
						)
					);


					$newSetWeight = array(
						'client_id' 	=> (int) $client['ID'],
						'day_id' 		=> (int) $dayId
					);

					for ($setCount=1; $setCount<=3; $setCount++)
					{
						for($setField=1; $setField<=4; $setField++)
						{
							$repKey = "set{$setCount}_rep_{$setField}";
							$weightKey = "set{$setCount}_weight_{$setField}";

							if (isset($client[$repKey])) {
								$newSetWeight[$repKey] = floatval($client[$repKey]);
							}

							if (isset($client[$weightKey])) {
								$newSetWeight[$weightKey] = floatval($client[$weightKey]);
							}
						}
					}

					$wpdb->insert('workout_day_client_sets_tbl', $newSetWeight);
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
									'workout_day_availability' => (int) $client['day_availability']
								)
							);
						} else {
							$wpdb->update(
								'workout_day_clients_tbl',
								array(
									//'workout_client_dayID' => (int) $d['wday_ID'],
									//'workout_client_workout_ID' => (int) $workout['workout_ID'],
									//'workout_clientID' => (int) $client['ID'],
									'workout_day_availability' => (int) $client['day_availability']
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
						// insert the new client id
						$wpdb->insert('workout_day_clients_tbl',
							array(
								'workout_client_dayID' => $dayId,
								'workout_client_workout_ID' => (int) $workout['workout_ID'],
								'workout_clientID' => (int) $client['ID'],
								'workout_day_availability' => (int) $client['day_availability']
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
	global $wpdb;
	$querystr = "SELECT * FROM workout_tbl WHERE workout_trainer_ID =".$userId;
	$workouts = $wpdb->get_results($querystr, OBJECT);
	return $workouts;
}

function workOutGet($workoutId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_tbl WHERE workout_ID=".$workoutId." LIMIT 1";
	$result = $wpdb->get_results($querystr, ARRAY_A);

	if(count($result) >= 1) {
		$workout = $result[0];

		$querystr = "SELECT * FROM workout_days_tbl WHERE wday_workout_ID=".$workout['workout_ID'];
		$days = $wpdb->get_results($querystr, ARRAY_A);

		foreach($days as $key => $d)
		{
			$exercisesQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_day_ID=".$d['wday_ID'];
			$exercises = $wpdb->get_results($exercisesQuery, ARRAY_A);

			$clientsQuery = "SELECT * FROM workout_day_clients_tbl WHERE workout_client_dayID=".$d['wday_ID'];
			$clients = $wpdb->get_results($clientsQuery, ARRAY_A);

			$userClients = [];

			foreach($clients as $c)
			{
				$userQuery = "SELECT * FROM wp_users WHERE ID=".$c['workout_clientID'] . " LIMIT 1";
				$userResult = $wpdb->get_results($userQuery, ARRAY_A);

				if(count($userResult) >= 1)
				{
					$client = $userResult[0];
					$client['day_availability'] = $c['workout_day_availability'];

					$logsQuery = "SELECT * FROM workout_client_exercises_logs WHERE client_id=" . (int) $c['workout_clientID'] . " AND day_id=" . (int) $d['wday_ID'] . " AND workout_id=" . $workout['workout_ID'];
					$logs = $wpdb->get_results($logsQuery, ARRAY_A);

					foreach ($logs as $k => $log)
					{
						$exercisesLogQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_ID=".$log['exercise_id'] . " LIMIT 1";
						$exerciseResult = $wpdb->get_results($exercisesLogQuery, ARRAY_A);

						if(count($exerciseResult) >= 1)
						{
							$logs[$k]['exercise'] =  $exerciseResult[0];
						}
					}

					/* get workout_day_client_sets_tbl */
					$setsQuery = "SELECT * FROM workout_day_client_sets_tbl WHERE day_id=".$d['wday_ID'] . " AND client_id=" . $client['ID'] . " LIMIT 1";
					$set = $wpdb->get_results($setsQuery, ARRAY_A);
					
					$client['logs'] = $logs;

					if (count($set) >= 1) {
						$client['set'] = $set[0];
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
