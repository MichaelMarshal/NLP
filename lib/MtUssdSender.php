<?php
/**
 *   (C) Copyright 1997-2013 hSenid International (pvt) Limited.
 *   All Rights Reserved.
 *
 *   These materials are unpublished, proprietary, confidential source code of
 *   hSenid International (pvt) Limited and constitute a TRADE SECRET of hSenid
 *   International (pvt) Limited.
 *
 *   hSenid International (pvt) Limited retains all title to and intellectual
 *   property rights in these materials.
 */

 
ini_set('error_log', 'ussd-senders.log');

class MtUssdSender{
    var $server;

    public function __construct($server){
        $this->server = $server; // Assign server url
    }

    /*
        Get parameters form the application
        check one or more addresses
        Send them to ussdMany
    **/

    public function ussd($applicationId, $password, $message, $destinationAddress,$sessionId, $ussdOperation){
						 
        if (is_array($destinationAddress)) { //Check destination address is a array or not
            return $this->ussdMany($applicationId, $password, $message,$sessionId, $ussdOperation, $destinationAddress);
				
        } else if (is_string($destinationAddress) && trim($destinationAddress) != "") {
            return $this->ussdMany($applicationId, $password, $message,$sessionId, $ussdOperation, $destinationAddress);
        } else {
            throw new Exception("address should a string or a array of strings");
        }
    }

    /*
        Get parameters form the ussd
        Assign them to an array according to json format
        encode that array to json format
        Send json to sendRequest
    **/

    private function ussdMany($applicationId, $password, $message,$sessionId, $ussdOperation, $destinationAddress)
	{

        $arrayField = array("applicationId" => $applicationId,
            "password" => $password,
            "message" => $message,
            "destinationAddress" => $destinationAddress,
            "sessionId" => $sessionId,
            "ussdOperation" => $ussdOperation,
            "encoding" => "440"
            //"version" => $version,
          //  "chargingAmount" => $chargingAmount
			);

        $jsonObjectFields = json_encode($arrayField);
        return $this->sendRequest($jsonObjectFields);
    }

    /*
        Get the json request from ussdMany
        use curl methods to send Ussd
        Send the response to handleResponse
    **/

    private function sendRequest($jsonObjectFields){
        $ch = curl_init($this->server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonObjectFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch); //Send request and get response
        curl_close($ch);
		//$this->logFile($jsonObjectFields);
       // error_log(" $res: ". $res." | ");
	return $res;
       // return $this->handleResponse($res);
    }

	function logFile($rtn){
	$f=fopen("logeee.txt","a");
	fwrite($f,  "[".date('D M j G:i:s T Y')." | ".time()." ] ".$rtn . "\n");
	fclose($f);
	}
	
    /*
        Get the response from sendRequest
        check response is empty
        return response
    **/

    private function handleResponse($resp){
        if ($resp == "") {
            throw new UssdException
            ("Server URL is invalid", '500');
        } else {
            echo $resp;
        }
    }

}

class UssdException extends Exception{ // Ussd Exception Handler

    var $code;
    var $response;
    var $statusMessage;

    public function __construct($message, $code, $response = null){
        parent::__construct($message);
        $this->statusMessage = $message;
        $this->code = $code;
        $this->response = $response;
    }

    public function getStatusCode(){
        return $this->code;
    }

    public function getStatusMessage(){
        return $this->statusMessage;
    }

    public function getRawResponse(){
        return $this->response;
    }

}

?>