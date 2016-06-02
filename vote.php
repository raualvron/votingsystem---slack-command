<?php 

include 'checkSlack.php';
include 'voteSys.php';

// Getting value from POST method
$token = $_POST['token'];
$channel = $_POST['channel_id'];
$username = $_POST['user_name'];
$userID = $_POST["user_id"];
$text =  $_POST['text'];

//Creating new CheckSlack object 
$slackto = new CheckSlack();
$vote = new voteSystem();

//Getting command
$nominee = explode(" ", $text);
$reason = str_replace($nominee[0], "", $text);

//If the token and channel (screenshot channel) are false, are you a bot?
//If text doesn't exits or empty
if (!($slackto->checkTC($token)) || (empty($text) && isset($text))) {
    echo "Are you a bot?..";
    exit();
}

//If the string contain @ or is not total...
if(strpos($nominee[0], '@') !== false) {
	
	//Checking if the user votes before
	if( !(empty($vote->checkVote($channel, $username, $userID, $nominee[0], $reason)))) {
		echo "Have you voted before?";
	} else {
		//Saving in the database the result to vote the employee of the month
		$vote->addVote($channel, $username, $userID, $nominee[0], $reason);
  		echo "Hello " . $username . ", the system-vote records you vote. You have voted to " . $nominee[0] . " because " . $reason;
  	}
  	
//If the string does not contain
} else  {

	echo "¯\_(ツ)_/¯ Helloo bot!";
	die();
}


