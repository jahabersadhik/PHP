<?php
	$connection = mysql_connect("localhost","root","PeRi") or die('error');
	mysql_select_db("altech",$connection);
	$asteriskUsername = 'admin';
	$asteriskPassword = 'peri123';
	$IPAddress = '115.113.233.146';
//	$IPAddress = '192.168.2.195';
	$userName = $_SESSION['userName'];
	$sshUsername='isystem';//$_SESSION['sshUsername'];
	$sshPassword='isystem12345';//$_SESSION['sshPassword'];
	$moduleDetailsResult = mysql_query("SELECT hierarchy.modules FROM userData JOIN hierarchy ON(hierarchy.hierarchy = userData.designation) WHERE userData.empId='$userName'");
	$modules = mysql_fetch_row($moduleDetailsResult);

/*	function asteriskLog($phone){
		 Asterisk Connection
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
		
		//Asterisk Update the Block List
		$ch = curl_init();
		$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&Action-000000=append&Cat-000000=default&Var-000000=exten&Value-000000=$phone,1,HangUp";
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
	
		// Asterisk reload the updation
		$ch = curl_init();
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
	}*/
/*function converthours($totalbreak)
{
	$hours = intval(intval($totalbreak) / 3600); 
	$minutes = intval(($totalbreak / 60) % 60);
	$seconds = intval($totalbreak % 60); 
	if(strlen($hours)!="2")
	{
		$hours = "0".$hours;
	}
	if(strlen($minutes)!="2")
	{
		$minutes = "0".$minutes;
	}
	if(strlen($seconds)!="2")
	{
		$seconds = "0".$seconds;
	}	
	return $breaktime = $hours.':'.$minutes.':'.$seconds;
}*/
?>
