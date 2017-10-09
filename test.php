<?php
/**
 * Created by PhpStorm.
 * User: Michael Marshal
 * Date: 10/4/2017
 * Time: 4:10 PM
 */


ini_set('error_log', 'error.log');
include 'lib/SMSSender.php';
include 'lib/SMSReceiver.php';
$Happycount=0;
$Shapecount=0;
$Sadcount=0;
date_default_timezone_set("Asia/Colombo");
$password= "";
$applicationId = "";
$serverurl= "https://api.dialog.lk/sms/send";

try{



    $receiver = new SMSReceiver(file_get_contents('php://input'));
    $content =$receiver->getMessage();
    $content=preg_replace('/\s{2,}/',' ', $content);
    $address = $receiver->getAddress();
    $requestId = $receiver->getRequestID();
    $applicationId = $receiver->getApplicationId();

    $sender = new SMSSender($serverurl, $applicationId, $password);


    list($key, $second) = explode(" ",$content);




    if ($second=="food") {



        $boradmsg = substr($content,9);

        $url = "https://language.googleapis.com/v1/documents:analyzeSentiment?key=<key>";

        $ch = curl_init($url);
        $json=array("encodingType"=> "UTF8", "document"=> array("type"=> "PLAIN_TEXT", "content"=> $boradmsg));
        $encode_data=json_encode($json,JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encode_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        $decode=json_decode($response,true);
        curl_close($ch);

        error_log("Broadcast Message ".$content);

        $emotion=$decode['documentSentiment']['score'];


        if($emotion>0.25){
            $Happycount++;
            $sender->sendMessage("Please come again",$address);
        }elseif ($emotion<0.25&&$emotion>-0.25){
            $Shapecount++;
            $sender->sendMessage("We are happy to have you",$address);
        }elseif ($emotion<-0.25){
            $Sadcount++;
            $sender->sendMessage("Please contact Shafraz Rahim to get more comfortable with us",$address);
        }


        $response=$sender->broadcastMessage($boradmsg);
    }else{


        error_log("Message received ".$content);

        $sender->sendMessage("Thanks".$second,$address);

    }





}catch (SMSServiceException $e){
    error_log("Passed Exception-not working ".$e);
}







