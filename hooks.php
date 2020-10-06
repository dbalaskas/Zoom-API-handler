<?php
	include 'zoom_api_Methods.php';
	include 'config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    	$data = file_get_contents("php://input");
        $decode = json_decode($data);
        $payload = json_encode($decode->payload);

        // expecting valid json
        if (json_last_error() !== JSON_ERROR_NONE) {
            die(header('HTTP/1.0 415 Unsupported Media Type'));
        }

        $file = 'results.txt';
        file_put_contents($file, print_r($decode->event, true) . PHP_EOL, FILE_APPEND);
        file_put_contents($file, print_r($payload, true) . PHP_EOL, FILE_APPEND);
    }

?>
