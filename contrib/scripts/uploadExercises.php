<?php

require_once 'db.php';

$row = 0;
$countPart = 0;
$exercises = [];
$exercise  = [];

$db = new Db();

if (($handle = fopen("..\\matrix.csv", "r")) !== FALSE)
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    {

        if ($row >= 1)
        {
            $implementations = [];
            if (strlen($data[4])) {
                $implementations = explode("\n", $data[4]);

                foreach($implementations as $k => $i)
                {
                    $implementations[$k] = preg_replace('/[\r\n\t\x09\x0A]+/','', $i);
                }

                $exercise2 = [];
            }

            if (strlen($data[3])) {
                $exercise2 = explode("\n", $data[3]);
            }

            $exercise1 = [];
            if (strlen($data[2])) {
                $exercise1 = explode("\n", $data[2]);
            }

            $typeName = $data[1];
            $part = $data[0];

           // if ($countPart >= 2) break;


          //  print_r($data);
            if (trim(strlen($part)) > 0) { // insert part here

               // echo "{$part} >> {$countPart} \n";

                $type = array(
                    'type' => $typeName,
                    'exercise_1' => $exercise1,
                    'exercise_2' => $exercise2,
                    'implementation_options' => $implementations
                );

                $exercises[$countPart] = [
                    'part' => $part,
                    'types' => [$type]
                ];

            } else {

                $type = array(
                    'type' => $typeName,
                    'exercise_1' => $exercise1,
                    'exercise_2' => $exercise2,
                    'implementation_options' => $implementations
                );

                // echo "-----{$type} >> {$countPart}--------\n";
                $exercises[$countPart - 1]['types'][] = $type;

            }

            if(trim(strlen($part)) > 0) {
                $countPart++;
            }
        }

        $row++;

    }

    fclose($handle);

   // print_r($exercises);
//
//    foreach($exercises as $ex)
//    {
//
//        foreach($ex['types'] as $type)
//        {
//
//        }
//    }

    foreach($exercises as $ex)
    {
        $part = $db->quote($ex['part']);
        $options = $db->quote(preg_replace('/[\r\n]+/','', stripslashes(json_encode($ex['types']))));

        $query    = "INSERT INTO `workout_exercise_options_tbl` (`part`, `options`)";
        $query   .= " VALUES (" . $part . "," . $options . ")";
        $result   = $db->query($query);
    }
}