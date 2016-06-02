<?php 

include 'checkSlack.php';
include 'checkDay.php';
include 'voteSys.php';
include 'Mandrill.php';


$vote = new voteSystem();
$slackto = new CheckSlack();
$date = new checkDay();
$mandrill = new Mandrill('');


//Getting the last friday of the month
$last_friday_month = $date->lastf_month(date("Y"), date("m"));

//Converting to datetime
$last_daym_month = new DateTime($last_friday_month);
$last_dayt_month = new DateTime($last_friday_month);

//Getting the last monday of the month
$last_monday_month = $last_daym_month->sub(new DateInterval('P4D'));
$last_monday_month = $last_monday_month->format('Y-m-d');

//Getting the last thursday of the month
$last_thursday_month = $last_dayt_month->sub(new DateInterval('P1D'));
$last_thursday_month = $last_thursday_month->format('Y-m-d');


//Getting current date
$current_date = date('Y-m-d');

//Sending message to Slack because here is the last monday of the month.
if ($current_date == $last_monday_month) {
	
	$message = "Today is last monday of the month, so it is day to vote the employee of the month. Type the command /vote @name reason on this channel";
	$slackto->postToSlack($message);
	die();

} else if ($current_date == $last_thursday_month) {
	
	if ($vote->repeatVote() == true) {
		
		$message = "Come on guys! The employee of the month is very close. Did you forget to vote?";
		$slackto->postToSlack($message);
		die();

	}

} else if ($current_date == $last_friday_month) {

	$totalVotes = $vote->totalVote();

	$html = file_get_contents('template/total_vote.html');

	foreach ($totalVotes as $key => $value) {
		$row .= "<table cellpadding='0' cellspacing='0' width='100%''>
		<tr>
		<td>
		Date: " . $value['votedate'] . " ." . $value['user_name'] . " votes to <b>" . $value['nominee'] . "
		</b></td>
		</tr>
		<tr>
		<td style='padding: 20px 0 30px 0;''>
		" . $value['reason'] . "
		</td>
		</tr>
		<tr>
		</tr>
		</table>";
	}

	$nameWinners = $vote->winners();

	$html = str_replace('<span></span>', $row, $html);
	$html = str_replace('<p></p>', $nameWinners, $html);


	$message = array(
		'subject' => 'Employee of the month - ' . date('M-Y'),
		'from_email' => '',
		'html' => $html,
		'to' => array(
			array('email' => '', 'name' => ''),
			array('email' => '', 'name' => ''),

		),
	);

	$async = false;
    $result = $mandrill->messages->send($message, $async);

	if($result){
		$message = "Thanks for your contributions in the employee of the month. We have a winner!";
	}

	$slackto->postToSlack($message);


} else {

	die();

}

?>