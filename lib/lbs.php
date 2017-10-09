<?php


require_once 'SMSServiceException.php';


/**
* 
*/
class lbs
{
	private $serverurl,
			$appid,
			$password,
			$servicetype;
	function lookrev($lat,$long)
	{
		$key='AIzaSyASRXalCPtJrvodlYkYLOVczpD_ZsLvj5Y';
		$url='http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=true';
		$data = @file_get_contents($url);
		$response = json_decode($data,true);
		return $response['results'][0]['formatted_address'];
	}

	function ss($serverurl,$appid,$password)
	{
		$this->serverurl=$serverurl;
		$this->appid=$appid;
		$this->password=$password;
	}

	public function getplaces($lat,$long)
	{
		$placeskey='AIzaSyASRXalCPtJrvodlYkYLOVczpD_ZsLvj5Y';
		$url='https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=6.9167,79.8333&radius=5000&types=food&sensor=false&key=AIzaSyASRXalCPtJrvodlYkYLOVczpD_ZsLvj5Y';

		$places=$this->send_request($url);
		
		return $this->resolve_res($places);
	}


	public function send_request($url)
	{		
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL,  $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);

			return json_decode($data, true);
	}

	public function resolve_res($ss)
	{
		$i=0;
		$texts='';
		$pl='';
		$t=0;
		while ( isset($ss['results'][$i]) && $t<=5 ) {
		 	$pl=$ss['results'][$i]['name'].' : '.$ss['results'][$i]['rating'].', ';

		 	if (strlen($texts)+strlen($pl)<=140  ) {
		 		$texts.=$pl;
		 		$t++;
		 	}
		 	$i++;
		}

		return $texts;

	}


	public function request($address,$appid,$password)
	{
	
		$this->appid=$appid;
		$this->password=$password;
	
		$sethttp = 	array('http'=>array('method'=>'POST',
							  			'header'=>'Content-Type: application/json',
							 			 'content'=> $this->stream($address)));

		$context = stream_context_create($sethttp);

		// http://api.dialog.lk:8080/lbs/locate
		//$response = file_get_contents('http://localhost:7000/lbs/locate', 0, $context);
		$response = file_get_contents('http://api.dialog.lk:8080/lbs/locate', 0, $context);
		$jres=json_decode($response,true);

		return $this->response($jres) ;

	}


	public function response($jsonResponse){

		$statusCode = $jsonResponse['statusCode'] ;
		$statusDetail = $jsonResponse['statusDetail'];
		
		if(empty($jsonResponse)){

			throw new SMSServiceException('Invalid server URL', '500');

		}else if(strcmp($statusCode, 'S1000')==0){

			  return  array($jsonResponse['longitude'],
         					$jsonResponse['latitude'],
         					$jsonResponse['timeStamp'],
       						$jsonResponse['statusDetail'],
         					$jsonResponse['subscriberState'],
        					$jsonResponse['horizontalAccuracy'],
        					$jsonResponse['freshness'],
       						$jsonResponse['messageId'],
        					$jsonResponse['version']);

		}else{
			throw new SMSServiceException($statusDetail, $statusCode);
		}
	}

	public function stream($address)
	{
		$details= array('applicationId'=>$this->appid,
						'password'=>$this->password,
						'serviceType'=>'IMMEDIATE',
						'subscriberId'=>$address,
						'freshness'=>'HIGH_LOW',
						'horizontalAccuracy'=>'100',
						'responseTime'=>'LOW_DELAY'
					);

	  	return json_encode($details);
	}


	public function setServiceType($servicetype)
	{
		$this->$servicetype=$servicetype;
	}
}

?>