<?php
/**
 * Created by PhpStorm.
 * User: Michael Marshal
 * Date: 10/6/2017
 * Time: 9:52 AM
 */

//Initialization
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
ini_set('error_log', 'error.log');
include 'lib/SMSSender.php';
include 'lib/SMSReceiver.php';
$Happycount=0;
$Shapecount=0;
$Sadcount=0;
date_default_timezone_set("Asia/Colombo");
$password= "9bee33236f2e14d76ca75d8d7b8c2187";
$applicationId = "APP_039918";
$serverurl= "https://api.dialog.lk/sms/send";
$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/Ideamart-b2eef3b24efa.json');
$apiKey = 'b2eef3b24efa03808b0d06ffebebe7bd9ceb7a89';

$firebase = (new Factory)
    ->withServiceAccountAndApiKey($serviceAccount, $apiKey)
    ->withDatabaseUri('https://ideamart-393af.firebaseio.com')
    ->create();

$database = $firebase->getDatabase();





try {
    $receiver = new SMSReceiver(file_get_contents('php://input'));
    $content =$receiver->getMessage();
    $content=preg_replace('/\s{2,}/',' ', $content);
    $address = $receiver->getAddress();
    $requestId = $receiver->getRequestID();
    $applicationId = $receiver->getApplicationId();

    $sender = new SMSSender($serverurl, $applicationId, $password);


    list($key, $second) = explode(" ",$content);


    if($second=="kottu") {



        $boradmsg = substr($content,9);

        $url = "https://language.googleapis.com/v1/documents:analyzeSentiment?key=AIzaSyAEn7W1BlYLLvr9Tv6ryJt2rxvpM2U6wtk";

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

        $newPost = $database
            ->getReference('kottu');

        $me = $newPost->getValue();


        ////////////*********************************/////////////////////////////////////////////////////kottu-sad
        if ($emotion <-0.25) {
            $sadCount = $me['sad'];
            $sadCount++;
            $newPost = $database
                ->getReference('kottu');
            $newPost->getChild('sad')->set($sadCount);
            $sender->sendMessage("Please contact Shafraz Rahim to get more comfortable with us",$address);
        }
/////////////////////////////////////////////****************************///////////////////////////


////////////*********************************/////////////////////////////////////////////////////kottu-happy
        if($emotion>0.25){
            $happyCount = $me['happy'];
            $happyCount++;
            $newPost = $database
                ->getReference('kottu');
            $newPost->getChild('happy')->set($happyCount);
            $sender->sendMessage("Please come again",$address);
        }


/////////////////////////////////////////////****************************///////////////////////////


////////////*********************************/////////////////////////////////////////////////////kottu-shape
        if($emotion>-0.25&& $emotion<0.25){
            $shapeCount= $me['shape'];
            $shapeCount++;
            $newPost = $database
                ->getReference('kottu');
            $newPost->getChild('shape')->set($shapeCount);
            $sender->sendMessage("We are happy to have you",$address);
        }


/////////////////////////////////////////////****************************///////////////////////////


        $response=$sender->broadcastMessage($boradmsg);

    }elseif ($second=="rice"){
        $boradmsg = substr($content,9);

        $url = "https://language.googleapis.com/v1/documents:analyzeSentiment?key=AIzaSyAEn7W1BlYLLvr9Tv6ryJt2rxvpM2U6wtk";

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

        $newPost = $database
            ->getReference('rice');

        $me = $newPost->getValue();


        ////////////*********************************/////////////////////////////////////////////////////rice-sad
        if ($emotion <-0.25) {
            $sadCount = $me['sad'];
            $sadCount++;
            $newPost = $database
                ->getReference('rice');
            $newPost->getChild('sad')->set($sadCount);
            $sender->sendMessage("Please contact Shafraz Rahim to get more comfertable with us",$address);
        }
/////////////////////////////////////////////****************************///////////////////////////


////////////*********************************/////////////////////////////////////////////////////rice-happy
        if($emotion>0.25){
            $happyCount = $me['happy'];
            $happyCount++;
            $newPost = $database
                ->getReference('rice');
            $newPost->getChild('happy')->set($happyCount);
            $sender->sendMessage("Please come again",$address);
        }


/////////////////////////////////////////////****************************///////////////////////////


////////////*********************************/////////////////////////////////////////////////////rice-shape
        if($emotion>-0.25&& $emotion<0.25){
            $shapeCount= $me['shape'];
            $shapeCount++;
            $newPost = $database
                ->getReference('rice');
            $newPost->getChild('shape')->set($shapeCount);
            $sender->sendMessage("We are happy to have you",$address);
        }


/////////////////////////////////////////////****************************///////////////////////////


        $response=$sender->broadcastMessage($boradmsg);
    }elseif ($second=="noodles"){
        $boradmsg = substr($content,9);

        $url = "https://language.googleapis.com/v1/documents:analyzeSentiment?key=AIzaSyAEn7W1BlYLLvr9Tv6ryJt2rxvpM2U6wtk";

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

        $newPost = $database
            ->getReference('noodles');

        $me = $newPost->getValue();


        ////////////*********************************/////////////////////////////////////////////////////noodles-sad
        if ($emotion <-0.25) {
            $sadCount = $me['sad'];
            $sadCount++;
            $newPost = $database
                ->getReference('noodles');
            $newPost->getChild('sad')->set($sadCount);
            $sender->sendMessage("Please contact Shafraz Rahim to get more comfertable with us",$address);
        }
/////////////////////////////////////////////****************************///////////////////////////


////////////*********************************/////////////////////////////////////////////////////noodles-happy
        if($emotion>0.25){
            $happyCount = $me['happy'];
            $happyCount++;
            $newPost = $database
                ->getReference('noodles');
            $newPost->getChild('happy')->set($happyCount);
            $sender->sendMessage("Please come again",$address);
        }


/////////////////////////////////////////////****************************///////////////////////////


////////////*********************************/////////////////////////////////////////////////////noodles-shape
        if($emotion>-0.25&& $emotion<0.25){
            $shapeCount= $me['shape'];
            $shapeCount++;
            $newPost = $database
                ->getReference('noodles');
            $newPost->getChild('shape')->set($shapeCount);
            $sender->sendMessage("We are happy to have you",$address);
        }


/////////////////////////////////////////////****************************///////////////////////////


        $response=$sender->broadcastMessage($boradmsg);
    }else{
        error_log("Message received ".$content);

        $sender->sendMessage("Thanks".$second,$address);
    }
}catch (SMSServiceException $e){
    error_log("Passed Exception-not working ".$e);
}

