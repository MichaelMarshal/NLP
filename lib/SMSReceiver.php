<?php

/*

	Author  : S S Rajapaksha <ssrajapaksha@outlook.com>
	Licence : Apache License, Version 2.0

	SMSReceiver class for handle server requests
	
*/

class SMSReceiver{
	private $version,			// Mandatory
			$applicationId,		// Mandatory
			$sourceAddress,		// Mandatory
			$message,			// Mandatory
			$requestId,			// Mandatory
			$encoding;			// Mandatory
	
	public function __construct($jsonRequest){
		$jsonRequest = json_decode($jsonRequest);
		
		// Mandatory fields may comment out if necessary.
	//	if(!(isset(
				//$jsonRequest->version,		// You can comment out this line
				//$jsonRequest->applicationId,	// You can comment out this line
				//$jsonRequest->sourceAddress,
				//$jsonRequest->message,
				//$jsonRequest->requestId,	// You can comment out this line
				//$jsonRequest->encoding		// You can comment out this line
			//	)))
				
				if(!((isset($jsonRequest->sourceAddress) && isset($jsonRequest->message) )))
					$response = array('statusCode'=>'E1312', 'statusDetail'=>'Request is Invalid.');
		else{
			$this->version = $jsonRequest->version;
			$this->applicationId = $jsonRequest->applicationId;
			$this->sourceAddress = $jsonRequest->sourceAddress;
			$this->message = $jsonRequest->message;
			$this->requestId = $jsonRequest->requestId;
			$this->encoding = $jsonRequest->encoding;
				
			$response = array('statusCode'=>'S1000',
			 				  'statusDetail'=>'Process completed successfully.');
		}
		
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	public function getVersion(){
		return $this->version;
	}
	
	public function getEncoding(){
		return $this->encoding;
	}
	
	public function getApplicationId(){
		return $this->applicationId;
	}
	
	public function getAddress(){
		return $this->sourceAddress;
	}
	
	public function getMessage(){
		return $this->message;
	}
	
	public function getRequestId(){
		return $this->requestId;
	}
}
?>