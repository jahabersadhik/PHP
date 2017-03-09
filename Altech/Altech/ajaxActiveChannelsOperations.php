<?php
session_start();
$FSERVER=$_SESSION['Fserver'];
$Fusername=$_SESSION['Fusername'];
$Fpassword=$_SESSION['Fpassword'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$FSERVER/asterisk/rawman?action=login&username=$Fusername&secret=$Fpassword");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,$strToAdd,$FSERVER);
curl_close($ch);


$channel=$_REQUEST['channel'];
if($_REQUEST['mode'] == "transfer")
{
	$extn = $_REQUEST['extn'];
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, "https://$FSERVER/asterisk/rawman?action=redirect&channel=$channel&exten=$extn&context=default&priority=1");
	curl_setopt($ch1, CURLOPT_HEADER, 1);
	curl_setopt ($ch1, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch1, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput_status = curl_exec($ch1);
	echo $curlOutput_status;
	curl_close($ch1);
}
if($_REQUEST['mode'] == "hangup")
{
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, "https://$FSERVER/asterisk/rawman?action=hangup&channel=$channel");
	curl_setopt($ch1, CURLOPT_HEADER, 1);
	curl_setopt ($ch1, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch1, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput_status = curl_exec($ch1);
	echo $curlOutput_status;
	curl_close($ch1);
}
?>