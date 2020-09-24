<?php
    require_once 'vendor/autoload.php';
    use \Firebase\JWT\JWT;

    class ZoomAPI {
        /* Created on 13/9/2020 by Dionysis Balaskas, for Sofar.
        *
        * Available functions:
        * 
        * ZoomAPI(API_KEY, API_SECRET, EXPIRATION_TIME)
        *
        * getExpirationTime()            # Returns the expiration time of each request.
        * getZoomUsers()                 # Returns the users of the app.
        * postZoomUser(userInfo)         # As userInfo you have to pass an object <{'email', 'first_name', 'last_name', 'password'}>, but only email is necessary.
        * getZoomUserDetails(userID)     # As userID you have to pass Zoom's userID or user's email.
        * getZoomUserMeetings(userID)    # As userID you have to pass Zoom's userID or user's email.
        * postZoomUserMeeting(userID)    # As userID you have to pass Zoom's userID.
        */

        private $Api_key;
        private $Api_Secret;
        private $expirationTime;
        // private const EXPIRATION_TIME = 10;

        function __construct($_Api_key, $_Api_Secret, $_expiration_time) {
            $this->Api_key = $_Api_key;
            $this->Api_Secret = $_Api_Secret;
            $this->expirationTime = $_expiration_time;
        }
        
        function getExpirationTime() {
            return $this->expirationTime;
        }
        
        private function getZoomTokenJWT() {
            // Returns a JWT token for Zoom APP.
            $issuedAt = time();
            $expTime = $issuedAt + $this->expirationTime; // We want the JWT token to expire in 60 secs.
            $payload = array(
                "iss" => $this->Api_key,
                "exp" => $expTime,
            );
            $jwt = JWT::encode($payload, $this->Api_Secret, "HS256");
            return $jwt;
        }        

        function getZoomUsers() {
            try {
                $url = 'https://api.zoom.us/v2/users/';
                $options = array(
                    'http' => array(
                        'method'  => 'GET',
                        'header'  => "Authorization: Bearer ".$this->getZoomTokenJWT(),
                    )
                );
                $context  = stream_context_create($options);
                $response =json_decode(file_get_contents($url, false, $context));
                echo var_dump($http_response_header);
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
                $userInfo['type'] = 1;
                echo '{"action": "create", "user_info": '.json_encode($userInfo).'}'.'<br>'.'<br>';
                $url = 'https://api.zoom.us/v2/users/';
                $options = array(
                    'http' => array(
                        'method'     => 'POST',
                        'header'     => "Authorization: Bearer ".$this->getZoomTokenJWT()."\r\n"
                            ."Content-Type: application/json",
                        // 'content'    =>  '{"action": "create", "user_info": '.json_encode($userInfo).'}',
                        'content'    =>  '{"action": "create", "user_info": {"email": "grooum@gmail.com", "type": "1"}}',
                    )
                );
                $context  = stream_context_create($options);
                $response = json_decode(file_get_contents($url, false, $context));
                echo var_dump($http_response_header).'<br>';
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
                        'header'  => "Authorization: Bearer ".$this->getZoomTokenJWT(),
                    )
                );
                $context  = stream_context_create($options);
                $response =json_decode(file_get_contents($url, false, $context));
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
                $userID = $this->getZoomUserDetails($userID)->id;
                $url = 'https://api.zoom.us/v2/users/'.$userID.'/meetings';
                $options = array(
                    'http' => array(
                        'method'  => 'GET',
                        'header'  => "Authorization: Bearer ".$this->getZoomTokenJWT(),
                    )
                );
                $context  = stream_context_create($options);
                $response = json_decode(file_get_contents($url, false, $context));
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
                        'method'     => 'POST',
                        'header'     => "Authorization: Bearer ".$this->getZoomTokenJWT()."\r\n"
                            ."Content-Type: application/json",
                        'content'    =>  '{"type": "1"}',
                    )
                );
                $context  = stream_context_create($options);
                $response =json_decode(file_get_contents($url, false, $context));
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