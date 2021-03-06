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

		/* $member_type = bp_get_member_type($user->data->ID); */

		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;

		} else if( in_array( 'client', $user->roles ) ){

			return home_url() . '/client';

		} else if( in_array( 'trainer', $user->roles ) ){

			return home_url() . '/trainer';

		} else if( in_array( 'gym', $user->roles ) ){

			return home_url() . '/gym';

		}


	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'sm_login_redirect', 10, 3 );


/*Simple Membership Plugin Functioins*/
add_action('swpm_after_login', 'after_login_callback');
function after_login_callback ()
{
	$user_roles = wp_get_current_user()->roles;
	$role_label = "";
	if(in_array( 'trainer', $user_roles )){
		$role_label = "trainer";
	}elseif(in_array( 'gym', $user_roles )){
		$role_label = "gym";
	}

	wp_redirect(home_url() . '/' . $role_label);
	exit();
}

/*ADD NEW ROLES*/
function wpcodex_set_capabilities() {
	$role = get_role( 'trainer' );
	$role2 = get_role( 'gym' );
	$role3 = get_role( 'client' );

	$caps = array('create_users');
	$caps2 = array('upload_files');
	$removeCaps = array('edit_posts', 'edit_users', 'list_users', 'remove_users', 'delete_users');

	foreach($caps as $cap){
		$role->add_cap( $cap );
		$role2->add_cap( $cap );

	}

	foreach($caps2 as $cap){
		$role3->remove_cap( $cap );
	}

	foreach($removeCaps as $removeCap){
		$role->remove_cap( $removeCap );
		$role2->remove_cap( $removeCap );
		$role3->remove_cap( $removeCap );
	}
}
add_action( 'init', 'wpcodex_set_capabilities' );

add_role(
	'gym',
	__( 'Gym' ),
	array(
		'create_users'   => true,
	)
);
add_role(
	'trainer',
	__( 'Trainer' ),
	array(
		'create_users'   => true
	)
);
add_role(
	'client',
	__( 'Client' ),
	array(
		'read'         => true
	)
);

/*Return array of Clients*/
function getClientsOfTrainer($user) {
	if ( ! in_array('trainer', $user->roles, true) ) {
		return array();
	}
	$meta = get_user_meta($user->ID, 'clients_of_trainer', true);
	if (empty($meta)) {
		return array();
	}

	$query = new WP_User_Query(array(
		'role'    => 'client',
		'include' => (array) $meta
	));

	return $query->results;
}
/*Return array of trainers*/
function getTrainersOfGym($user) {
	if ( ! in_array('gym', $user->roles, true) ) {
		return array();
	}
	$meta = get_user_meta($user->ID, 'trainers_of_gym', true);
	if (empty($meta)) {
		return array();
	}

	$query = new WP_User_Query(array(
		'role'    => 'trainer',
		'include' => (array) $meta
	));

	return $query->results;
}

/*Assign a Client to a Trainer*/
function assignClientToTrainer($client, $trainer) {
	if ( ! in_array('trainer', $trainer->roles, true) ) {
		return false;
	}

	if ( ! in_array('client', $client->roles, true) ) {
		return false;
	}

	$clients = get_user_meta($trainer->ID, 'clients_of_trainer', true);
	if(empty($clients)){
		$clients = array();
	}

	$clients[] = $client->ID;
	$update = update_user_meta($trainer->ID, 'clients_of_trainer', $clients);
	update_user_meta($client->ID, 'parent_trainer', $trainer->ID);

	return (int) $update > 0;
}

/*Assign a Trainer to a Gym*/
function assignTrainerToGym($trainer, $gym) {
	if ( ! in_array('gym', $gym->roles, true) ) {
		return false;
	}

	if ( ! in_array('trainer', $trainer->roles, true) ) {
		return false;
	}

	$trainers = get_user_meta($gym->ID, 'trainers_of_gym', true);
	if(empty($trainers)){
		$trainers = array();
	}

	$trainers[] = $trainer->ID;
	$update = update_user_meta($gym->ID, 'trainers_of_gym', $trainers);
	update_user_meta($trainer->ID, 'parent_gym', $gym->ID);

	return (int) $update > 0;
}

/*User Trapping*/
function checkUserOrParentStatus($user){
	global $wpdb;
	$query = "";
	$member = array();
	$uemail = "";

	if(in_array( 'gym', $user->roles )){
		$uemail = $user->user_email;
	}elseif(in_array( 'trainer', $user->roles )){
		$parent_id = get_user_meta($user->ID, 'parent_gym', true);

		if($parent_id != ""){
			$uemail = get_user_by('id', $parent_id)->user_email;
		}else{
			$uemail = $user->user_email;
		}
	}elseif(in_array( 'client', $user->roles )){
		$parent_id = get_user_meta($user->ID, 'parent_trainer', true);

		if($parent_id != ""){
			$gparent_id = get_user_meta($parent_id, 'parent_gym', true);
			if($gparent_id != ""){
				$uemail = get_user_by('id', $gparent_id)->user_email;
			}else{
				$uemail = get_user_by('id', $parent_id)->user_email;
			}
		}
	}

	$query = "SELECT * FROM wp_swpm_members_tbl WHERE email = '" . $uemail . "'";
	$member = $wpdb->get_results($query, OBJECT);

	if(!empty($member)){
		if($member[0]->account_state == "active"){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

/*GET USER SCHEDULE*/
function getDaysOfWeek()
{
	$ddate = date('Y-m-d');
	$date = new DateTime($ddate);
	$week = $date->format("W");
	$year = $date->format("Y");
	$tempArr = array();

	for($day=1; $day<=7; $day++){
		$tempArr[$day] = date('Y-m-d', strtotime($year."W".$week.$day));
	}

	return $tempArr;
}

function getWeeklySchedule($user)
{
	global $wpdb;
	$day1 = getDaysOfWeek()[1];
	$day7 = getDaysOfWeek()[7];

	$results_w_day = $wpdb->get_results( "SELECT * FROM workout_day_clients_tbl WHERE workout_clientID = " . $user->ID . " AND workout_client_schedule BETWEEN '". $day1 ."' AND '" . $day7 . "'", OBJECT );
	$results_w = $wpdb->get_results( "SELECT * FROM workout_tbl", OBJECT );

	return getWOutArr($results_w_day, $results_w);
}
function getMonthlySchedule($u)
{
	global $wpdb;
	$urole = $u->roles;
	$uid = $u->ID;
	$wquery = "SELECT * FROM workout_day_clients_tbl WHERE workout_clientID";
	$results_w = $wpdb->get_results( "SELECT * FROM workout_tbl", OBJECT );


	if(in_array('client',$urole)){
		$results_w_day = $wpdb->get_results( $wquery . "=" . $uid, OBJECT );
	}elseif(in_array('trainer',$urole)){
		$umeta = get_user_meta($uid,'clients_of_trainer',true);
		$coft = array();
		if($umeta)
			$coft = implode(", ", get_user_meta($uid,'clients_of_trainer',true));

		$results_w_day = $wpdb->get_results( $wquery . " IN (" . $coft . ")", OBJECT );
	}

	return getWOutArr($results_w_day, $results_w);
}

function getWOutArr($results_w_day, $results_w)
{
	global $wpdb;
	$woutArray = array();
	foreach($results_w_day as $rwd){
		foreach($results_w as $rw){
			$wid = $rwd->workout_client_workout_ID;
			if($wid == $rw->workout_ID){
				$arrTemp = array();
				$dayid = $rwd->workout_client_dayID;
				$arrTemp['dayid'] = $dayid;
				$arrTemp['workout_clientid'] = $rwd->workout_clientID;
				$arrTemp['wid'] = $wid;
				$arrTemp['wsched'] = date_format(date_create($rwd->workout_client_schedule), 'Y-m-d');
				$rday = $wpdb->get_results( "SELECT * FROM workout_days_tbl WHERE wday_ID = ". $dayid, OBJECT );
				$arrTemp['wdname'] = $rday[0]->wday_name;
				$woutArray[] = $arrTemp;
			}
		}
	}
	return $woutArray;
}

function jabs($u)
{

	$woutArray = getMonthlySchedule($u);
	$ctrTemp = 0;
	$tempArr = array();
	$caot = array(); //Client Array of Trainer
	$urole = "";

	if(in_array('client', $u->roles)){
		$urole = "client";
	}elseif(in_array('trainer',$u->roles)){
		$urole = "trainer";
		$caot = get_user_meta($u->ID,'clients_of_trainer', true);
	}

	if(!empty($woutArray)){
		foreach($woutArray as $wa){
			$tempArr2 = array();
			$ctrTemp++;
			$daylink = home_url() ."/".$urole."/?data=workout&dayId=".$wa['dayid']."&workoutId=".$wa['wid']."&workout_client_id=".$wa['workout_clientid'];
			$tempArr2[] = ['wdname' => $wa['wdname'], 'daylink' => $daylink, 'clientid' => $wa['workout_clientid']];
			$tempArr[$wa['wsched']][$ctrTemp] = $tempArr2;
		}
	}

	echo "<pre>";
	print_r($tempArr);
	echo "</pre>";
}

function getSchedData($u)
{
	$woutArray = getMonthlySchedule($u);
	$ctrTemp = 0;
	$tempArr = array();
	$caot = array(); //Client Array of Trainer
	$urole = "";

	if(in_array('client', $u->roles)){
		$urole = "client";
	}elseif(in_array('trainer',$u->roles)){
		$urole = "trainer";
		$caot = get_user_meta($u->ID,'clients_of_trainer', true);
	}

	if(!empty($woutArray)){
		foreach($woutArray as $wa){
			$tempArr2 = array();
			$ctrTemp++;
			$daylink = home_url() ."/".$urole."/?data=workout&dayId=".$wa['dayid']."&workoutId=".$wa['wid']."&workout_client_id=".$wa['workout_clientid'];
			$tempArr2[] = ['wdname' => $wa['wdname'], 'daylink' => $daylink];
			$tempArr[$wa['wsched']][$ctrTemp] = $tempArr2;
		}
	}

	return $tempArr;
}

/*Recursive IN_ARRAY function*/
function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}


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

	$workout = htmlspecialchars_decode($data['workoutForm'], ENT_NOQUOTES);
	$workout = preg_replace('/\\\"/',"\"", $workout);

	$workout = stripslashes($workout);
	$workout = json_decode($workout, true);

	$wpdb->insert('workout_tbl',
		array(
			'workout_name' => $workout['name'],
			'workout_trainer_ID' => $data['workout_trainer_ID'],
			'workout_created_by' => (int) $workout['user_id']
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

					if ((int) $client['date_availability'] > 0)
					{

						$scheduleDate = new \Carbon\Carbon($client['date_availability']);

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

	$workout = htmlspecialchars_decode($data['updateWorkoutForm'], ENT_NOQUOTES);
	$workout = preg_replace('/\\\"/',"\"", $workout);

	$workout = stripslashes($workout);
	$workout = json_decode($workout, true);

	$mWorkoutId = (int) $workout['workout_ID'];
	$weekDays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

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

			if (isset($d['wday_ID']) && $d['isDelete'])
			{

				/* delete exercises and clients */


				// delete day exercises relates to workout
				$mDayId = (int) $d['wday_ID'];

				$dayExercisesQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_day_ID={$mDayId} AND exer_workout_ID={$mWorkoutId}";
				$dayExercises = $wpdb->get_results($dayExercisesQuery, ARRAY_A);


				foreach ($dayExercises as $ex)
				{
					// delete workout_client_exercise_assignments relates to exercise
					$assignmentQuery = "SELECT * FROM workout_client_exercise_assignments WHERE exercise_id={$ex['exer_ID']}";
					$assignments = $wpdb->get_results($assignmentQuery, ARRAY_A);

					// delete workout_client_exercise_assignment_sets > which is sets relates to exercise assignment
					foreach ($assignments as $assign)
					{
						$wpdb->delete(
							'workout_client_exercise_assignment_sets',
							array( 'assignment_id' => $assign['id'] )
						);
					}

					$wpdb->delete(
						'workout_client_exercise_assignments',
						array('exercise_id' => $ex['exer_ID'])
					);
				}

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

			}
			// existing day
			else if (isset($d['wday_ID']))
			{
				$wpdb->update(
					'workout_days_tbl',
					array(
						'wday_name' => $d['wday_name'],
						'wday_order' => $d['wday_order']
					),
					array( 'wday_ID' => $d['wday_ID'] )
				);

				// for exercises
				if ($d['exercises'])
				{

					foreach($d['exercises'] as $ex)
					{
						// check if the exercise exist

						if (isset($ex['exer_ID']))
						{

							$exerciseQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_ID={$ex['exer_ID']} LIMIT 1";
							$result = $wpdb->get_results($exerciseQuery, ARRAY_A);

							if(count($result) > 0)
							{

								$exercise = $result[0];

								// delete exercise
								if (isset($ex['isDelete']))
								{

									$wpdb->delete(
										'workout_exercises_tbl',
										array('exer_ID' => $ex['exer_ID'])
									);

									// delete client assignments exercise
									// delete assignment sets

								} else {

									// update part

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

									// update sq
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

									$exercise['hash'] = $ex['hash'];

									$wpdb->update('workout_exercises_tbl', $exercise, array('exer_ID' => (int) $ex['exer_ID']));

								}

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

							$exercise['hash'] = $ex['hash'];
							$wpdb->insert('workout_exercises_tbl', $exercise);
						}

					}
				}

				if (isset($d['clients']))
				{

					foreach($d['clients'] as $client)
					{
						$dNumber = ((int) $client['day_availability']) - 1;
						//	$scheduleDate = new \Carbon\Carbon($weekDays[$dNumber]);

						$scheduleDate = new \Carbon\Carbon($client['date_availability']);
						$clientQuery = "SELECT * FROM workout_day_clients_tbl WHERE workout_client_dayID={$d['wday_ID']} AND workout_client_workout_ID={$workout['workout_ID']} AND workout_clientID={$client['ID']}";
						$result = $wpdb->get_results($clientQuery, OBJECT);

						// if theres no client
						if(count($result) <= 0)
						{
							// insert the new client id
							$wpdb->insert('workout_day_clients_tbl',
								array(
									'workout_client_dayID' => (int) $d['wday_ID'],
									'workout_client_workout_ID' => (int) $workout['workout_ID'],
									'workout_clientID' => (int) $client['ID'],
									'workout_day_availability' => (int) $client['day_availability'],
									'workout_client_schedule' => $scheduleDate->format('Y-m-d')
								)
							);
						} else {
							$wpdb->update(
								'workout_day_clients_tbl',
								array(
									'workout_day_availability' => (int) $client['day_availability'],
									'workout_client_schedule' => $scheduleDate->format('Y-m-d')
								),
								array( 'workout_clientID' => $client['ID'],
									'workout_client_dayID' => (int) $d['wday_ID'],
									'workout_client_workout_ID' => (int) $workout['workout_ID'],
								)
							);
						}

						// insert assignment exercises and sets

						if (isset($client['exercises']))
						{

							$exHashes = implode("','", array_column($client['exercises'], 'hash'));
							$clientExercisesQuery = "SELECT * FROM workout_exercises_tbl WHERE hash in ('{$exHashes}')";
							//	dd($clientExercisesQuery);
							$clientExercisesResult = $wpdb->get_results($clientExercisesQuery, ARRAY_A);
							//dd($clientExercisesResult);
							$exIds = implode(",", array_column($clientExercisesResult, 'exer_ID'));
							$clientAssignmentsQuery = "SELECT * FROM workout_client_exercise_assignments WHERE exercise_id in ({$exIds}) AND client_id=".(int) $client['ID'];

							$clientAssignmentsResult = $wpdb->get_results($clientAssignmentsQuery, ARRAY_A);
							$assignmentIds = implode(",", array_column($clientAssignmentsResult, 'id'));

							$wpdb->query( "DELETE FROM `workout_client_exercise_assignment_sets` WHERE `assignment_id` IN ({$assignmentIds})");
							$wpdb->query( "DELETE FROM `workout_client_exercise_assignments` WHERE `exercise_id` IN ({$exIds}) AND client_id=".(int) $client['ID']);

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
										//dd($assignmentId);
										foreach ($ex['assignment_sets'] as $key => $set)
										{
											$wpdb->insert('workout_client_exercise_assignment_sets',
												array(
													'assignment_id' => (int) $assignmentId,
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
			else { /* insert if there is no existing day */

				$wpdb->insert('workout_days_tbl',
					array(
						'wday_workout_ID' => $workout['workout_ID'],
						'wday_name' => $d['wday_name'],
						'wday_order' => (int) $d['wday_order']
					)
				);

				$dayId = $wpdb->insert_id;

				if ($d['exercises'])
				{
					foreach($d['exercises'] as $ex)
					{

						$exercise = [
							'exer_day_ID' => $dayId,
							'exer_workout_ID' => (int) $workout['workout_ID'],
							'hash' => $ex['hash']
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

				if (isset($d['clients']))
				{
					foreach($d['clients'] as $client)
					{
						$dNumber = ((int) $client['day_availability']) - 1;
						//	$scheduleDate = new \Carbon\Carbon($weekDays[$dNumber]);
						$scheduleDate = new \Carbon\Carbon($client['date_availability']);
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


						if (isset($client['exercises']))
						{

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

//									dd(
//										array(
//											'exercise_id' => (int) $m->exer_ID,
//											'client_id' => (int) $client['ID']
//										)
//									);


									$assignmentId = $wpdb->insert_id;
									//dd($assignmentId);
									if (isset($ex['assignment_sets']))
									{

										foreach ($ex['assignment_sets'] as $key => $set)
										{
											$wpdb->insert('workout_client_exercise_assignment_sets',
												array(
													'assignment_id' => (int) $assignmentId,
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

	return $workout['workout_ID'];
}

function workOutGetClients()
{
	global $wpdb;
	$querystr = "SELECT * FROM wp_users";
	$users = $wpdb->get_results($querystr, OBJECT);
	$outputList = [];

	$listOfClients = getClientsOfTrainer(wp_get_current_user());

	foreach($users as $user) {
		foreach($listOfClients as $listOfClient){
			if($listOfClient->ID == $user->ID){
				if(in_array('client', get_userdata($user->ID)->roles)) {
					$outputList[] = $user;
				}
			}
		}
	}

	return $outputList;
}

function workoutGetTrainerExercises($userId)
{
	global $wpdb;
	$workOutQuery = "SELECT * FROM workout_tbl WHERE workout_trainer_ID=".(int) $userId;
	$result = $wpdb->get_results($workOutQuery, ARRAY_A);

	if (count($result) > 0)
	{
		$workouts = $result;
		$workoutIds = implode(",", array_column($workouts, 'workout_ID'));

		$exercisesQuery = "SELECT * FROM workout_exercises_tbl WHERE exer_workout_ID IN ({$workoutIds})";
		$exercises = $wpdb->get_results($exercisesQuery, ARRAY_A);

		return $exercises;
	}

	return [];

}

function workoutClientsList($workout_ID)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	global $wpdb;

	$querystr = 'SELECT * FROM `workout_day_clients_tbl` WHERE workout_client_workout_ID = '.$workout_ID.' GROUP BY workout_clientID';
	$workout_clients = $wpdb->get_results($querystr, OBJECT);

	return $workout_clients;

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
//$querystr = "SELECT * FROM workout_tbl WHERE workout_ID=".$workoutId." LIMIT 1";
//$result = $wpdb->get_results($querystr, ARRAY_A);

function workoutGetClientWorkouts($clientId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$todayQuery = "SELECT DISTINCT(workout_client_workout_ID), workout_client_dayID, workout_isDone FROM workout_day_clients_tbl WHERE DATE(`workout_client_schedule`) = CURDATE() AND workout_clientID={$clientId}";
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

	$days = [];
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
					$client['date_availability'] = explode(" ", $c['workout_client_schedule'])[0];
					$client['exercises'] = [];

					foreach ($exercises as $ex)
					{
						$assignQuery = "SELECT * FROM workout_client_exercise_assignments WHERE client_id=" . (int) $client['ID'] . " AND exercise_id=". (int) $ex['exer_ID'] . " LIMIT 1";
						$assignResult = $wpdb->get_results($assignQuery, ARRAY_A);
						$ex['query'] = $assignQuery;
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

function workoutClientWorkoutWithDay($workoutId, $dayId, $clientId)
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

	global $wpdb;
	$querystr = "SELECT * FROM workout_day_clients_tbl WHERE workout_client_dayID={$workoutId} AND workout_client_workout_ID={$dayId} LIMIT 1";
	$result = $wpdb->get_results($querystr, OBJECT);

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

		$clientWorkout->exercises = [];
		$queryExercises =  "SELECT * FROM workout_exercises_tbl WHERE exer_workout_ID={$clientWorkout->workout_client_workout_ID} AND exer_day_ID={$workoutId}";
		$exercises = $wpdb->get_results($queryExercises, ARRAY_A);
		//dd($queryExercises);
		if (count($exercises) >= 1) {

			foreach ($exercises as $k => $ex)
			{
				$assignQuery = "SELECT * FROM workout_client_exercise_assignments WHERE client_id=" . (int) $clientId . " AND exercise_id=". (int) $ex['exer_ID'] . " LIMIT 1";
				$assignResult = $wpdb->get_results($assignQuery, ARRAY_A);

				if (count($assignResult) > 0) {

					$assignSetsQuery = "SELECT * FROM workout_client_exercise_assignment_sets WHERE assignment_id=" . $assignResult[0]['id'];
					$assignSetsResult = $wpdb->get_results($assignSetsQuery, ARRAY_A);

					$exercises[$k]['sets'] = $assignSetsResult;
					$exercises[$k]['setss'] = $assignSetsResult;
				}
			}

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
		'callback' => 'workoutCreateClientExerciseLog',
	));

	register_rest_route( 'v1', 'hash', array(
		'methods' => 'GET',
		'callback' => 'workoutGenerateHash',
	));

	register_rest_route( 'v1', 'client/upload', array(
		'methods' => 'POST',
		'callback' => 'smUpload',
	));
}

function smUpload()
{
	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-customs/User.php';
	var_dump($_FILES);
	/* $u = wp_get_current_user();

	$upload_file = wp_handle_upload($_FILES["myFile"], array('test_form' => false), date('Y/m'));
	var_dump($upload_file);
	
	$wp_upload_dir = wp_upload_dir();
	$guid = $wp_upload_dir['baseurl'] . "/" . _wp_relative_upload_path( $upload_file );
	
	$attachment = array(
		'post_mime_type' => $_FILES["myFile"]['type'],
		'guid' => $guid,
		'post_title' => 'Uploaded: ' . $upload_file['file'],
		'post_content' => '',
		'post_author' => $u->ID,
		'post_status' => 'inherit',
		'post_date' => date('Y-m-d H:i:s'),
		'post_date_gmt' => date('Y-m-d H:i:s')
	);
	
	$attach_id = wp_insert_attachment($attachment, $upload_file['file']);
	$meta = wp_generate_attachment_metadata($attach_id,$upload_file['file']);
	wp_update_attachment_metadata($meta);
	
	$upload_feedback = false;
	
	return $attach_id; */

	/* $image = New ImageMeta; */

	if (isset($_POST['userId']))
	{
		$user = User::find($_POST['userId']);
		
		if ($user) {
			$user->uploadFile($_FILES['myFile']);

			print_r($user->getPhotos());
		}
	}
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

function workoutCreateClientExerciseLog()
{

	global $wpdb;

	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	$data = json_decode( file_get_contents('php://input') , true);
	//dd($data);
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

	if (isset($data['sets']))
	{
		foreach ($data['sets'] as $set)
		{
			$seq = (int) $set['seq'];

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
						//	'reps' 		  => $set['reps'],
						//'isMet' 	  => (int) $set['isMet'] ? true : false,
						//'isDone'      => $set['isMet'],
					),
					array('id' => $currentSet['id'])
				);

			} else {
				// log insert exercise log into workout_client_exercises_logs

				/* insert set logs */
				$wpdb->insert('workout_client_set_logs',
					array(
						'exercise_log_id' => $exerciseLogId,
						//'reps' 		  => $set['reps'],
						//	'isMet' 	  => (int) $set['isMet'] ? true : false,
						//'isDone'      => $set['isMet'],
						'seq'		  => (int) $set['seq'],
						'client_id'   => $userId
					)
				);
			}
		}
	}

	// update the workout_day_clients_tbl table for flagging isDone

	$wpdb->update(
		'workout_day_clients_tbl',
		array('workout_isDone' => TRUE),
		array(
			'workout_client_dayID' => (int) $data['exer_day_ID'],
			'workout_client_workout_ID' => (int) $data['exer_workout_ID'],
			'workout_clientID' => $userId
		)
	);
}

function workoutCreateClientSetLog()
{

	global $wpdb;

	require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
	$data = json_decode( file_get_contents('php://input') , true);
	dd($data);
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