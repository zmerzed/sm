<?php

require_once 'db.php';

$ES = [];

$ES['set_options'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$ES['rep_options'] = ['5 sec',
    '10 sec',
    '15 sec',
    '20 sec',
    '30 sec',
    '45 sec',
    '60 sec',
    '75 sec',
    '90 sec',
    '5 meter',
    '10 meters',
    '15 meters',
    '20 meters',
    '25 meters',
    '30 meters',
    '40 meters',
    '50 meters',
    '60 meters',
    '100 meters',
    '200 meters',
    '300 meters',
    '400 meters',
    '800 meters',
    '25 feet',
    '50 feet',
    '75 feet',
    '100 feet'
];

$ES['tempo'] = ['maximal', 'submaximal', 'fast', 'slow', 'Moderate'];
$ES['rest'] = [
    '15 sec',
    '30 sec',
    '45 sec',
    '60 sec',
    '75 sec',
    '90 sec',
    '120 sec',
    '150 sec',
    '3 min',
    '4 min',
    '5 min'
];


$H = [];
$H['set_options'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$H['rep_options'] = [
    '6',
    '7',
    '8',
    '9',
    '10',
    '12',
    '15',
    '20',
    '6-8',
    '7-9',
    '8-10',
    '10-12',
    '12-15',
    '15-20',
    '(6,x,x)*',
    '(8,x,x)*',
    '(6,6,6)**',
    '(8,8,8)**',
    '(10,6,4)**',
    '12,10,8',
    '15,12,10',
    '20,15,12',
    '8,6,4,4,6,8',
    '8,6,4,20',
    '7,5,3,7,5,3',
    'AMRAP'
];

$H['tempo'] = [
    '2011',
    '3011',
    '4011',
    '5011',
    '2110',
    '3110',
    '4110',
    '5110'
];

$H['rest'] = [
    '15 sec',
    '30 sec',
    '45 sec',
    '60 sec',
    '75 sec',
    '90 sec',
    '120 sec',
    '150 sec',
    '3 min',
    '4 min',
    '5 min'
];

$H['repetition_pattern'] = [
    '*rest-pause',
    '**drop set',
    'Cluster',
    'Ascending Load',
    'Constant Load',
    'Descending Load*',
    'Pyramid Load',
    'Wave Load',
    'Step Load',
    'AMRAP(as many reps as possible)'
];

$H['tempo_explained'] = [
    'The First Number - Using the squat as an example, the 3 will represent the amount of time (in seconds) it takes you to descend to the bottom position.',
    'The Second Number - The second number refers to the time spent in the bottom/transition between eccentric(lowering) and the concentric(ascending) portion of the exercise. The 0 means the trainee immediately begins their ascent after they reach the bottom postion. If the prescription were 32X0, the trainee would pause for 2 seconds at the bottom position.',
    'The Third Number - The third number refers to ascending (concentric) phase of the lift. The X  indicates that the trainee must EXPLODE at the initiation of the concentric action and try to accelerate the load throughout the entire range of motion. Intent is vital; the load may move slowly but you must try to move as fast as possible. If number is 2, it should take 2 seconds to complete the lift even if they are capable of moving it faster.',
    'The Fourth Number - The fourth number indicates the pause at the moment before the start of the next repetition of the lift. For a 45 degree back extension with a tempo of 2012, the trainee will hold an isometric contraction in the extended position for two seconds before lowering.'
];

$MS = [];

$MS['set_options'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$MS['rep_options'] = [
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '2-4',
    '3-5',
    '4-6',
    '(1-1-1-1-1)',
    '(4,x,x)*',
    '(4,4,4)**',
    '5,3,2,2,3,5',
    '6,4,2,2,4,6',
    '5,4,3,2,1',
    '3,2,1,1,2,3'
];

$MS['tempo'] = [
    '20X1',
    '30X1',
    '40X1',
    '50X1',
    '21X0',
    '31X0',
    '41X0',
    '51X0'
];

$MS['rest'] = [
    '15 sec',
    '30 sec',
    '45 sec',
    '60 sec',
    '75 sec',
    '90 sec',
    '120 sec',
    '150 sec',
    '3 min',
    '4 min',
    '5 min'
];

$MS['repetition_pattern'] = [
    '*rest-pause',
    '**drop set',
    'Cluster',
    'Ascending Load*',
    'Constant Load*',
    'Descending Load*',
    'Pyramid Load',
    'Wave Load',
    'Step Load'
];


$MS['tempo_explained'] = [
    'The First Number - Using the squat as an example, the 3 will represent the amount of time (in seconds) it takes you to descend to the bottom position.',
    'The Second Number - The second number refers to the time spent in the bottom/transition between eccentric(lowering) and the concentric(ascending) portion of the exercise. The 0 means the trainee immediately begins their ascent after they reach the bottom postion. If the prescription were 32X0, the trainee would pause for 2 seconds at the bottom position.',
    'The Third Number - The third number refers to ascending (concentric) phase of the lift. The X  indicates that the trainee must EXPLODE at the initiation of the concentric action and try to accelerate the load throughout the entire range of motion. Intent is vital; the load may move slowly but you must try to move as fast as possible. If number is 2, it should take 2 seconds to complete the lift even if they are capable of moving it faster.',
    'The Fourth Number – The fourth number indicates the pause at the moment before the start of the next repetition of the lift. For a 45 degree back extension with a tempo of 2012, the trainee will hold an isometric contraction in the extended position for two seconds before lowering.',
    '*Ascending Load: Load is increased upon each successful set all while maintaining repetition range',
    '*Constant Load: load is slightly below your maximal performance for the prescribed repetitions, allows completition of all prescribed sets and reps',
    '*Descending Load: Initial load is at rep maximum, load is decrease after each set in order to maintain rep range',
    '*Pyramid Load: load is increased and repetitions decreased with each successive set'
];

$SE = [];

$SE['set_options'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$SE['rep_options'] = [
    '20-25',
    '25-30',
    '35-40',
    '45-50',
    '50',
    '75',
    '100',
    'AMRAP'
];

$SE['tempo'] = [
    '1010',
    '2010',
    '3010',
    '2011',
    '2110',
    '3011',
    '3110'
];

$SE['rest'] = [
    '15 sec',
    '30 sec',
    '45 sec',
    '60 sec',
    '75 sec',
    '90 sec',
    '120 sec',
    '150 sec',
    '3 min',
    '4 min',
    '5 min'
];

$SE['repetition_pattern'] = [
    'rest-pause',
    'drop set',
    'Cluster',
    'Ascending Load',
    'Constant Load',
    'Descending Load',
    'Pyramid Load',
    'Wave Load',
    'Step Load'
];


$strengthQualities = array(
    'ES' => $ES,
    'H' => $H,
    'MS' => $MS,
    'SE' => $SE
);

$db = new Db();
foreach($strengthQualities as $k => $v)
{
    $name     = $db->quote($k);
    $options  = $db->quote(preg_replace('/[\r\n]+/','', stripslashes(json_encode($v))));
    $query    = "INSERT INTO `workout_exercise_strength_qualities_tbl` (`name`, `options`)";
    $query   .= " VALUES (" . $name . "," . $options . ")";
    $result   = $db->query($query);
}