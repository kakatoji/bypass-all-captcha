<?php

require_once "curl.php";

function rev2($key,$web,$sitekey){
    $ua  =["Host: api.anycaptcha.com","Content-Type: application/json"];
    $url = "https://api.anycaptcha.com";
    $data=json_encode([
        "clientKey" => $key,
        "task" => [
            "type"         => "RecaptchaV2TaskProxyless",
            "websiteURL"   => $web,
            "websiteKey"   => $sitekey,
            "isInvisible"  => false
            ],
        ]);
    $create =json_decode(curl($url."/createTask",$data,$ua)[1],1);
    if(!$task = $create["taskId"]){
        echo "\tanycaptcha ".$create["errorCode"]."\n";return false;
    }
    $data = json_encode([
        "clientKey" => $key,
        "taskId"    => $create["taskId"]
        ]);
    while(true):
    echo "wait for result....!";
    $solve=json_decode(curl($url."/getTaskResult",$data,$ua)[1],1);
    echo "\r                                           \r";
    if($solve["status"] == "processing"){
        echo "sedang memproses";
        sleep(7);
        echo "\r                                        \r";
        continue;}
        return $solve["solution"]["gRecaptchaResponse"];
    endwhile;
}
