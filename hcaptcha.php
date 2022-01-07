<?php

require_once "curl.php";

function hcptcha($key,$web,$sitekey){
    $ua  =["Host: api.anycaptcha.com","Content-Type: application/json"];
    $url = "https://api.anycaptcha.com";
    $data=json_encode([
        "clientKey" => $key,
        "task" => [
            "type"         => "HCaptchaTaskProxyless",
            "websiteURL"   => $web,
            "websiteKey"   => $sitekey
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
        echo "processing bypass recaptcha";
        sleep(7);
        echo "\r                                        \r";
        continue;}
        return $solve["solution"]["gRecaptchaResponse"];
    endwhile;
}

