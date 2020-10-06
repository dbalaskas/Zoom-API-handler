<?php
    include 'zoom_api_Methods.php';
    include 'config.php';

    $zoom = new ZoomAPI(50, API_KEY, API_SECRET);
    $jwt = $zoom->getZoomTokenJWT();
    echo '<h4>Encode: '.$jwt.'</h4>';
?>