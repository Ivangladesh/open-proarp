<?php
$configs = include('config.php');

define("CaptchaPrivateKey", "6LfB2CQcAAAAAH9PdqYOwiX1vGmQ_rPK7W7rBMgL");

function Recaptcha($reCaptchaToken)
{
    $cu = curl_init();
    curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($cu, CURLOPT_POST, 1);
    curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CaptchaPrivateKey, 'response' => $reCaptchaToken)));
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($cu);
    curl_close($cu);
    $jsonResponse = json_decode($response, true);
    if ($jsonResponse['success'] == 1 && $jsonResponse['score'] >= 0.5) {
        return true;
    } else {
        return false;
    }
}
