<?php
    require_once 'vendor/autoload.php';
    use \Firebase\JWT\JWT;

    define('API_KEY', 'rNcEB2DqR3mOAhv127aZJg');
    define('API_SECRET', 'mx4vArnkuJytttQ3I8yYrjHEUzz67Im6TyjT');

    function getZoomTokenJWT() {
        // Returns a JWT token for Zoom APP.
        $issuedAt = time();
        $expirationTime = $issuedAt + 5 * 60; // We want the JWT token to expire in 60 secs.
        $payload = array(
            "iss" => API_KEY,
            "exp" => $expirationTime,
        );
        $jwt = JWT::encode($payload, API_SECRET, "HS256");
        return $jwt;
    }

    class ZoomAPI {
        /* Created on 13/9/2020 by Dionysis Balaskas, for Sofar.
        *
        * Available functionns:
        * getZoomUsers(tokennJWT)
        * postZoomUser(userInfo, tokennJWT)        # As userInfo you have to pass an object <{'email', 'first_name', 'last_name', 'password'}>, but only email is necessary.
        * getZoomUserDetails(userID,  tokenJWT)    # As userID you have to pass Zoom's userID or user's email.
        * getZoomUserMeetings(userID, tokenJWT)    # As userID you have to pass Zoom's userID.
        * postZoomUserMeeting(userID, tokenJWT)    # As userID you have to pass Zoom's userID.
        */
        

        function getZoomUsers() {
            try {
                $url = 'https://api.zoom.us/v2/users/';
                $options = array(
                    'http' => array(
                        'method'  => 'GET',
                        'header'  => "Authorization: Bearer ".getZoomTokenJWT(),
                    )
                );
                $context  = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                if ($response === FALSE) { 
                    return FALSE;
                } else {
                    return $response;
                }
            } catch(Exception $e) {
                return FALSE;
            }
        }

        function postZoomUser($userInfo) {
            if ($userInfo->{'email'} === '')
                return FALSE;
            try {
                $userInfo->{'type'} = 1;
                $url = 'https://api.zoom.us/v2/users/';
                $options = array(
                    'http' => array(
                        'method'  => 'POST',
                        'header'  => "Authorization: Bearer ".getZoomTokenJWT(),
                        'content'    =>  '{"action": ""'."\r\n".'"user_info": {"email": ""\r\n"type": ""\r\n"first_name: ""\r\n"last_name": ""}}',
                    )
                );
                $context  = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                if ($response === FALSE) { 
                    return FALSE;
                } else {
                    return $response;
                }
            } catch(Exception $e) {
                return FALSE;
            }
        }

        function getZoomUserDetails($userID) {
            try {
                $url = 'https://api.zoom.us/v2/users/'.$userID;
                $options = array(
                    'http' => array(
                        'method'  => 'GET',
                        'header'  => "Authorization: Bearer ".getZoomTokenJWT(),
                    )
                );
                $context  = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                if ($response === FALSE) { 
                    return FALSE;
                } else {
                    return $response;
                }
            } catch(Exception $e) {
                return FALSE;
            }
        }

        function getZoomUserMeetings($userID) {
            try {
                $url = 'https://api.zoom.us/v2/users/'.$userID.'/meetings';
                $options = array(
                    'http' => array(
                        'method'  => 'GET',
                        'header'  => "Authorization: Bearer ".getZoomTokenJWT(),
                    )
                );
                $context  = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                if ($response === FALSE) { 
                    return FALSE;
                } else {
                    return $response;
                }
            } catch(Exception $e) {
                return FALSE;
            }
        }

        function postZoomUserMeeting($userID) {
            try {
                $url = 'https://api.zoom.us/v2/users/'.$userID.'/meetings';
                $options = array(
                    'http' => array(
                        'method'  => 'POST',
                        'header'  => "Authorization: Bearer ".getZoomTokenJWT()."\r\n"
                            ."Content-Type: application/json",
                        'content'    =>  '{"type": "1"}',
                    )
                );
                $context  = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                if ($response === FALSE) { 
                    return FALSE;
                } else {
                    return $response;
                    // $myRes = json_decode($response);
                    // return $myRes->{'join_url'};
                }
            } catch(Exception $e) {
                return FALSE;
            }
        }
    }

?>