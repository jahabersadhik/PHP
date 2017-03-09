<?php
	session_start();
	require("connection.php");
	$exten = $_SESSION['extn'];
	$loginTime = $_SESSION['loginTime'];
	$loginDate = $_SESSION['loginDate'];
	$username = $_SESSION['userName'];
	$logoutdate = date('Y-m-d');
	$logouttime = date('G:i:s');
	mysql_query("UPDATE loginLog SET logoutDate='$logoutdate',logoutTime='$logouttime' WHERE loginDate='$loginDate' AND loginTime='$loginTime' AND extension='$exten' AND empId='$username'") or die(mysql_error());
	
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

	$ch = curl_init();
	$testurl = "https://$IPAddress/asterisk/rawman?action=AgentLogoff&agent=$username";
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
	
	if(isset($_SESSION['uName']))
		unset($_SESSION['uName']);
	if(isset($_SESSION['err']))
		unset($_SESSION['err']);
	if(isset($_SESSION['userName']))
		unset($_SESSION['userName']);
	if(isset($_SESSION['extn']))
		unset($_SESSION['extn']);
	if(isset($_SESSION['loginTime']))
		unset($_SESSION['loginTime']);
	if(isset($_SESSION['loginDate']))
		unset($_SESSION['loginDate']);
	
	session_destroy();
	header("location:index.php");
?>
