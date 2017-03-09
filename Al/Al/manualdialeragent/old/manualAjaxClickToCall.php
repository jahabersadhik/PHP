<?php
include("mysql_connection.php");
$FSERVER=$_SESSION['Fserver'];
$Fusername=$_SESSION['Fusername'];
$Fpassword=$_SESSION['Fpassword'];

//echo "<PRE>";print_r($_GET);print_r($_SESSION);
$strHost = $FSERVER;

#specify the username you want to login with (these users are defined in /etc/asterisk/manager.conf)
#this user is the default AAH AMP user; you shouldn't need to change, if you're using AAH.

$strUser = $Fusername;

#specify the password for the above user

$strSecret = $Fpassword;

#specify the channel (extension) you want to receive the call requests with
#e.g. SIP/XXX, IAX2/XXXX, ZAP/XXXX, etc

$strChannel = "SIP/".$_GET['fromNumber'];

#specify the context to make the outgoing call from.  By default, AAH uses from-internal
#Using from-internal will make you outgoing dialing rules apply

$strContext = "clicktocallmanual";//$clickToCallContext;

#specify the amount of time you want to try calling the specified channel before hangin up

$strWaitTime = "30";

#specify the priority you wish to place on making this call

$strPriority = "1";

#specify the maximum amount of retries

$strMaxRetry = "1";

#--------------------------------------------------------------------------------------------
#Shouldn't need to edit anything below this point to make this script work
#--------------------------------------------------------------------------------------------
#get the phone number from the posted form
if($clickToCallExtensionNo != ""){
	$strExten = $clickToCallExtensionNo.$_GET['toNumber'];
}else{
	$strExten = $_GET['toNumber'];
}
if($_GET['type'] == 'preview')
{
	$dialingId = $_GET['dialingId'];
}
else
{
	$dialingIdQuery = mysql_query("select dialingId from predictiveDialing where phone='$strExten'");
	$dialingIdRes = mysql_fetch_row($dialingIdQuery);
	$dialingId = $dialingIdRes[0];
}
if($strExten != ""){

	#specify the caller id for the call
	
	$strCallerId = "<$strExten>";
	
	$length = strlen($strExten);
	
	if ($length && is_numeric($strExten)){
		$oSocket = fsockopen($strHost, 5038, $errnum, $errdesc) or die("Connection to host failed");
		fputs($oSocket, "Action: login\r\n");
		fputs($oSocket, "Events: off\r\n");
		fputs($oSocket, "Username: $strUser\r\n");
		fputs($oSocket, "Secret: $strSecret\r\n\r\n");
		fputs($oSocket, "Action: originate\r\n");
		fputs($oSocket, "Channel: Local/$strExten@local/n\r\n");
		fputs($oSocket, "WaitTime: $strWaitTime\r\n");
		fputs($oSocket, "CallerId: PERI\r\n");
		fputs($oSocket, "Exten: $ringGroupExtension\r\n");
		fputs($oSocket, "Variable: var1=$strChannel|var2=$dialingId\r\n");
		fputs($oSocket, "Context: $strContext\r\n");
		fputs($oSocket, "Priority: $strPriority\r\n\r\n");
		fputs($oSocket, "Action: Logoff\r\n\r\n");
		fclose($oSocket);
		$calledDateTime = date('Y-m-d H:i:s');
		mysql_query("update previewDialing set calledDateTime='$calledDateTime' where phone='".$_GET['toNumber']."'");						
		echo true;
		exit;
	}
}
?>
