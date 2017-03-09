<?php
include("mysql_connection.php");
//Start of Authentication and Initialisation of cookies
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",'$IPAddress');
curl_close($ch);
//End of Authentication and Initialisation of cookies
if($_REQUEST['mode']=='add')
{
	$valMeet = $_REQUEST['extn'].",".$_REQUEST['pass'].",";
	$valConf = $_REQUEST['extn'].",1,MeetMe(%24%7BEXTEN%7D%2C".$_REQUEST['m'].$_REQUEST['s'].$_REQUEST['i'].$_REQUEST['q']/*.$_REQUEST['a'].$_REQUEST['w']*/.")";



//Update
	$ch = curl_init();
	$urlDetailsName1 = "Action-000000=delete&Cat-000000=conferences&Var-000000=exten&Match-000000=$valConf";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&$urlDetailsName1";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	
	
	$ch = curl_init();
	$urlDetailsName2 = "&Action-000000=append&Cat-000000=conferences&Var-000000=exten&Value-000000=$valConf";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf$urlDetailsName2";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	
	$ch = curl_init();
	$urlDetailsName3 = "&Action-000000=delete&Cat-000000=rooms&Var-000000=conf&Match-000000=$valMeet";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=meetme.conf&dstfilename=meetme.conf$urlDetailsName3";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	

	
	$ch = curl_init();
	$urlDetailsName4 = "&Action-000000=append&Cat-000000=rooms&Var-000000=conf&Value-000000=$valMeet";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=meetme.conf&dstfilename=meetme.conf$urlDetailsName4";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	

	$providedModificationValues = "Conference Number:".$_REQUEST['extn']."<br>Options: <br>";
	if(trim($_REQUEST['m'])!='')
		$providedModificationValues .= "Play Hold Music for First Caller<br>";
	if(trim($_REQUEST['s'])!='')
		$providedModificationValues .= "Enable caller menu<br>";
	if(trim($_REQUEST['i'])!='')
		$providedModificationValues .= "Announce Callers<br>";
	if(trim($_REQUEST['q'])!='')
		$providedModificationValues .= "Quiet Mode<br>";

	$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Conference/Add' AND trackExtension = '".$_REQUEST['extn']."' ORDER BY trackId DESC LIMIT 0,1",$connection);
	
	$oldvaluesresult =mysql_fetch_row($oldvaluesquery);
	if($oldvaluesresult[0] != $providedModificationValues)
	{
		echo $insertUserTracking1 = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Conference/Add',trackFunction='$oldvaluesresult[0]',trackExtension='".$_REQUEST['extn']."',trackInformation='".$providedModificationValues."'";
		$resultUserTracking=mysql_query($insertUserTracking1,$connection) or die(mysql_error());
	}
}

else if($_REQUEST['mode']=='edit')
{
	$valMeet = $_REQUEST['extn'].",".$_REQUEST['pass'].",";
	$valConf = $_REQUEST['extn'].",1,MeetMe(%24%7BEXTEN%7D%2C".$_REQUEST['m'].$_REQUEST['s'].$_REQUEST['i'].$_REQUEST['q'].")";/*.$_REQUEST['a'].$_REQUEST['w']*/
	$oldvalMeet = $_REQUEST['pextn'].",".$_REQUEST['ppass'].",";
	echo $oldvalConf = $_REQUEST['pextn'].",1,MeetMe(".'$'.'{'."EXTEN".'},'.$_GET['pm'].$_GET['ps'].$_GET['pi'].$_GET['pq'].")";
	
//Update
	
	$ch = curl_init();
	$urlDetailsName1 = "Action-000000=delete&Cat-000000=conferences&Var-000000=exten&Match-000000=$oldvalConf";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf&$urlDetailsName1";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	//echo $curlOutput;
	
	$ch = curl_init();
	$urlDetailsName2 = "&Action-000000=append&Cat-000000=conferences&Var-000000=exten&Value-000000=$valConf";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf$urlDetailsName2";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	//echo $curlOutput;
	
	$ch = curl_init();
	$urlDetailsName3 = "&Action-000000=delete&Cat-000000=rooms&Var-000000=conf&Match-000000=$oldvalMeet";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=meetme.conf&dstfilename=meetme.conf$urlDetailsName3";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	//echo $curlOutput;

	$ch = curl_init();
	$urlDetailsName4 = "&Action-000000=append&Cat-000000=rooms&Var-000000=conf&Value-000000=$valMeet";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=meetme.conf&dstfilename=meetme.conf$urlDetailsName4";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	
	$providedModificationValues = "Conference Number: ".$_REQUEST['extn']." <br>Options:<br>";
	if(trim($_REQUEST['m'])!='')
		$providedModificationValues .= "Play Hold Music for First Caller<br>";
	if(trim($_REQUEST['s'])!='')
		$providedModificationValues .= "Enable caller menu<br>";
	if(trim($_REQUEST['i'])!='')
		$providedModificationValues .= "Announce Callers<br>";
	if(trim($_REQUEST['q'])!='')
		$providedModificationValues .= "Quiet Mode<br>";
	
	$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Conference/Edit' AND trackExtension = '".$_REQUEST['pextn']."' ORDER BY trackId DESC LIMIT 0,1",$connection);
	if(mysql_num_rows($oldvaluesquery)==0)
		$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Conference/Add' AND trackExtension = '".$_REQUEST['pextn']."' ORDER BY trackId DESC LIMIT 0,1",$connection);
	
	$oldvaluesresult =mysql_fetch_row($oldvaluesquery);
	if($oldvaluesresult[0] != $providedModificationValues)
	{
		echo "1".$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Conference/Edit',trackFunction='$oldvaluesresult[0]',trackExtension='".$_REQUEST['pextn']."',trackInformation='".$providedModificationValues."'";
		$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());
	}
	exit();
}
else if($_REQUEST['mode']=='delete')
{
	$valMeet = $_REQUEST['extn'].",".$_REQUEST['pass'].",";
	$valConf = $_REQUEST['extn'].",1,MeetMe(%24%7BEXTEN%7D%2C".$_REQUEST['m'].$_REQUEST['s'].$_REQUEST['i'].$_REQUEST['q']/*.$_REQUEST['a'].$_REQUEST['w']*/.")";
	
//Update
	$ch = curl_init();
	$urlDetailsName1 = "Action-000000=delete&Cat-000000=rooms&Var-000000=conf&Match-000000=$valMeet";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=meetme.conf&dstfilename=meetme.conf&$urlDetailsName1";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	//@curl_setopt ($ch , CURLOPT_REFERER, $params['referer'] );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	//echo $curlOutput;
	//echo "<br>";
	$ch = curl_init();
	$urlDetailsName2 = "&Action-000000=delete&Cat-000000=conferences&Var-000000=exten&Match-000000=$valConf";
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=extensions.conf&dstfilename=extensions.conf$urlDetailsName2";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	
	$providedModificationValues = "Deleted the Conference Number";
	$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Conference/Edit' AND trackExtension = '".$_REQUEST['extn']."' ORDER BY trackId DESC LIMIT 0,1",$connection);
	if(mysql_num_rows($oldvaluesquery)==0)
		$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Conference/Add' AND trackExtension = '".$_REQUEST['extn']."' ORDER BY trackId DESC LIMIT 0,1",$connection);
	
	$oldvaluesresult =mysql_fetch_row($oldvaluesquery);
	if($oldvaluesresult[0] != "Deleted the Conference Number")
	{
		echo "2".$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Conference/Edit',trackFunction='$oldvaluesresult[0]',trackExtension='".$_REQUEST['extn']."',trackInformation='".$providedModificationValues."'";
		$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());
	}
}
?>
