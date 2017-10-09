<?php

	$url='https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=6.9167,79.8333&radius=5000&types=food&sensor=false&key=AIzaSyASRXalCPtJrvodlYkYLOVczpD_ZsLvj5Y';
	$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL,  $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			$ss=json_decode($data, true);

		//var_dump($ss['results'][0]['name']);
			
	



?>