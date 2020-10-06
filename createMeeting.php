<?php
    include 'zoom_api_Methods.php';
    include 'config.php';

    $zoom = new ZoomAPI(API_KEY, API_SECRET, 50);
    $res = json_encode($zoom->postZoomUserMeeting(USER_ID));
    if ($res === FALSE || $res == 'null') {
            echo "Request Failed!";
    } else {
        echo '<h4>'.$res.'</h4>';
    }
?>
