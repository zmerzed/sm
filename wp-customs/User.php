<?php

class User
{

    public function __construct()
    {

    }

    public function uploadFile($file)
    {
        global $wpdb;

        $currentDir = getcwd();
        $userId = $_POST['userId'];
        $uploadDirectory = "\\sm-files\\{$userId}\\";

        echo $currentDir . $uploadDirectory . "\n";
        if (!is_dir($currentDir . $uploadDirectory)) {
            echo 'xxxxxxxxxxxxxxxxx';
            mkdir($currentDir . $uploadDirectory, 0777, true);
        }

        $errors = []; // Store all foreseen and unforseen errors here

        $fileImageExtensions = ['jpeg','jpg','png']; // Get all the file extensions

        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmpName  = $file['tmp_name'];
        $fileType = $file['type'];
        $fileExtension = strtolower(end(explode('.',$fileName)));

        if (!in_array($fileExtension, $fileImageExtensions)) {
            $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

        if ($fileSize > 2000000) {
            $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }

        if (empty($errors)) {

            $newFileName =  basename('photo_'.time()) . '.jpg';
            $uploadPath = $currentDir . $uploadDirectory . $newFileName;

            echo $uploadPath;
            echo "\n";

            // insert the filename
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            if ($didUpload) {
                $wpdb->insert('workout_user_files',
                    array(
                        'file'   => $newFileName,
                        'type' => 'image',
                        'user_id' => (int) $this->id
                    )
                );
                echo "The file " . basename($fileName) . " has been uploaded";
            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
            }
        }
    }

    public function getPhotos() {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT * FROM workout_user_files WHERE user_id = {$this->id} AND type='image'", ARRAY_A);
        return $results;
    }

    public static function find($userId)
    {
        $user = new self();
        $user->id = $userId;
        return $user;
    }
}