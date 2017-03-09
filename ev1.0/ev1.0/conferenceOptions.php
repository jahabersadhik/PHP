<?php
	include "mysql_connection.php";
	//Start of function for getting content between two strings
	function find($start,$end,$string)
	{
		$startlength = strpos($string,$start)+strlen($start);
		$endlength = strpos($string,$end)-strlen($end);
		$finallength = $endlength - $startlength;
		$final = substr($string,$startlength,$finallength-3);
		return $final;
	}
	//End of function for getting content between two strings
	
	
	//Start of Authentication and Initialisation of cookies	
	$ch = curl_init();

/*if($IPAddress == '61.246.55.96')
	$connectUrl  = "http://".$IPAddress.":8088/asterisk/rawman?action=login&username=$Fusername&secret=$Fpassword";
else*/
$connectUrl = "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword";
curl_setopt($ch, CURLOPT_URL, "$connectUrl");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
/*if($IPAddress == '61.246.55.96')
	$expOutput[1] = $expOutput[0];*/
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",$_SERVER['SERVER_ADDR']);
curl_close($ch);

	//End of Authentication and Initialisation of cookies
	
	//Start of fetching extensions.conf
	$ch = curl_init();
	/*if($IPAddress== '61.246.55.96')
		$testurl = "http://$IPAddress:8088/asterisk/rawman?action=getconfig&filename=extensions.conf";
	else*/
	$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=extensions.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput_extensions = curl_exec($ch);
	curl_close($ch);
	//End of fetching extensions.conf
	
	//start of displaying extensions strings
	$abc = $curlOutput_extensions;
	$phn = addslashes($_REQUEST['phn']).",";
	$pass = addslashes($_REQUEST['pass']).",";

	$final = find(': conferences',': ringgroups',$abc);
	

	$arr = explode("exten=",$final);
	
	for($i=1,$j=0;$i<count($arr);$i++,$j++)
	{
		if($i==count($arr)-1)
		{
			$n[$j] = $arr[$i];
		}
		else 
		{
			$n[$j] = substr($arr[$i],0,-20);
		}
		
	}
	
	for($k=0;$k<count($n);$k++)
	{
		if(strstr($n[$k],$phn))
		{
			if(stristr($n[$k],'},M'))
			{
				$chk1 = "checked";
			}
			if(stristr($n[$k],'s'))
			{
				$chk2 = "checked";
			}
			if(stristr($n[$k],'I'))
			{
				$chk3 = "checked";
			}
		}
	}


	echo "<input type='checkbox' name='chk1' id='chk1' $chk1 />&nbsp;Play Hold Music for First Caller<br><br>
		  <input type='checkbox' name='chk2' id='chk2' $chk2 />&nbsp;Enable caller menu<br><br>
		  <input type='checkbox' name='chk3' id='chk3' $chk3 />&nbsp;Announce Callers<br><br>
		 ";
	if($chk1 == "checked")
		echo "<input type='hidden' name='oldchk1' id='oldchk1' value='M' />";
	else 
		echo "<input type='hidden' name='oldchk1' id='oldchk1' value='' />"; 
	if($chk2 == "checked")
		echo "<input type='hidden' name='oldchk2' id='oldchk2' value='s' />";
	else 
		echo "<input type='hidden' name='oldchk2' id='oldchk2' value='' />";
	if($chk3 == "checked")
		echo "<input type='hidden' name='oldchk3' id='oldchk3' value='I' />";
	else 
		echo "<input type='hidden' name='oldchk3' id='oldchk3' value='' />";
	
	echo "<input type='hidden' name='oldextn' id='oldextn' value='".$_REQUEST['phn']."' />
		  <input type='hidden' name='oldpass' id='oldpass' value='".$_REQUEST['pass']."' />
		 ";
	//echo "<pre>";
	//print_r($n);
	//end of displaying extensions strings
?>
