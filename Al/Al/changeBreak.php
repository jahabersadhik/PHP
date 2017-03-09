<?php
	session_start();
	include "connection.php";
	$totalbreak = $_REQUEST['totbreak'];
	$existingBreakQuery = mysql_query("SELECT break FROM loginLog WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
	$existingBreakResult = mysql_fetch_row($existingBreakQuery);
	$nowtime = strtotime(date('Y-m-d G:i:s'));
	$prevtime = strtotime($existingBreakResult[0]);
	$totalsec = ($nowtime-$prevtime)+$totalbreak;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expCookeInfo = explode("mansession_id=",$expOutput[1]);
	$cookieValue = str_replace('"','',$expCookeInfo[1]);
	$expPhpSelf = explode("/",$_SERVER['PHP_SELF']);
	$strToAdd = "";
	if(!strstr($expPhpSelf[1],"."))
	{
		$strToAdd = $expPhpSelf[1]."/";
	}
	setcookie("mansession_id",$cookieValue,time()+3600,"/".$strToAdd,$_SERVER['SERVER_ADDR']);
	curl_close($ch);
	if($_REQUEST['val'] == 'bo')
	{
		mysql_query("UPDATE loginLog SET break='0000-00-00 00:00:00',status=1,totalbreak='$totalsec' WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
		$actionid = rand(11111,99999);
		$ch = curl_init();
		//$testurl = "https://$IPAddress/asterisk/rawman?action=AgentCallBackLogin&agent=".$_SESSION['userName']."&exten=".$_SESSION['extn']."&Context=default&AckCall=false&WrapupTime=30%20&ActionID=$actionid";
		$testurl = "https://$IPAddress/asterisk/rawman?action=QueuePause&interface=AGENT/".$_SESSION['userName']."&Paused=false";
		curl_setopt($ch, CURLOPT_URL, $testurl);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		curl_close($ch);
	}
	if($_REQUEST['val'] == 'tb')
	{
		mysql_query("UPDATE loginLog SET status=2,break='".date('Y-m-d G:i:s')."' WHERE empId='".$_SESSION['userName']."' AND extension='".$_SESSION['extn']."' AND loginDate='".$_SESSION['loginDate']."' AND loginTime='".$_SESSION['loginTime']."'");
		$ch = curl_init();
		//$testurl = "https://$IPAddress/asterisk/rawman?action=AgentLogoff&agent=".$_SESSION['userName']."";
		$testurl = "https://$IPAddress/asterisk/rawman?action=QueuePause&interface=AGENT/".$_SESSION['userName']."&Paused=true";
		//$agentExtensions = $_REQUEST['chk'];
		curl_setopt($ch, CURLOPT_URL, $testurl);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$mansession_id = $_COOKIE['mansession_id'];
		@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
		$curlOutput = curl_exec($ch);
		curl_close($ch);
		//die();
	}
	header("location:home.php");
?>
