<?php
	include "check.php";
	require("connection.php");
	$queueName = $_REQUEST['q'];
	mysql_query("DELETE FROM queues WHERE queueName='$queueName'");
	mysql_query("DELETE FROM availability WHERE queueName='$queueName'");
	mysql_query("DELETE FROM incomingavailability WHERE queueName='$queueName'");
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
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=queues.conf&dstfilename=queues.conf&Action-000000=delcat&Cat-000000=$queueName&Var-000000=&Value-000000=";
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
	
	$ch = curl_init();
	//$match = "$queueName,1,Queue(%24{EXTEN})";
	$match = "$queueName,1,Macro(incoming,$queueName,%24{EXTEN})";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=delete&Cat-000000=queues&Var-000000=exten&Match-000000=$match";
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
	
	$ch = curl_init();
	//$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=append&Cat-000000=queues&Var-000000=exten&Value-000000=$queueName,1,Queue(%24{EXTEN})";
	$testurl = "https://$IPAddress/asterisk/rawman?action=command&command=reload";
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
	echo "
			<script language='javascript'>
				alert(\"Queue Deleted Successfully\");
				location.href='home.php?page=queues';
			</script>
	 	 ";

?>
