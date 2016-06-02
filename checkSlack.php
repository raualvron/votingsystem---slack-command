<?php

class CheckSlack {

	public function checkTC($token) {

		if(isset($token) && !empty($token) && $token == ""){

			return true;

		} else {

			return false;
		}

	}

	public function postToSlack($message) {

		$data = "payload=" . json_encode(array(
		"text"          =>  $message,
		"icon_emoji"    =>  ':trophy:',
		"username"      => 'vote-system',
		));

		
		$ch = curl_init('');
		//curl_setopt($ch, CURLOPT_HTTPHEADERS, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}