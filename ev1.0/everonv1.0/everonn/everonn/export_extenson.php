<?php
session_start();
require_once("mysql_connection.php");

// Functions for export to excel.
function xlsBOF() 
{
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}
function xlsEOF() 
{
echo pack("ss", 0x0A, 0x00);
return;
}
function xlsWriteNumber($Row, $Col, $Value) 
{
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}
function xlsWriteLabel($Row, $Col, $Value ) 
{
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

$ch = curl_init();
$connectUrl = "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword";
curl_setopt($ch, CURLOPT_URL, "$connectUrl");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
/*if($_SESSION['userIp'] == '61.246.55.96')
	$expOutput[1] = $expOutput[0];*/
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",$_SERVER['SERVER_ADDR']);
curl_close($ch);

$ch = curl_init();
$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=users.conf";
curl_setopt($ch, CURLOPT_URL, $testurl);
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$mansession_id = $_COOKIE['mansession_id'];
@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expResponse = explode("chunked",$expOutput[3]);
$finalResult['response']=$expResponse[1];
$file = explode("\n",$finalResult['response']);
curl_close($ch);

$ch = curl_init();
$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=extensions.conf";
curl_setopt($ch, CURLOPT_URL, $testurl);
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$mansession_id = $_COOKIE['mansession_id'];
@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expResponse = explode("chunked",$expOutput[3]);
$finalResult['response']=$expResponse[1];
/*if($_SESSION['userIp'] == '61.246.55.96')
	$finalResult['response'] = $expOutput[2];*/
$file2 = explode("\n",$finalResult['response']);
curl_close($ch);


header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=extensionReport.xls ");
header("Content-Transfer-Encoding: binary "); 

xlsBOF();

xlsWriteLabel(0,3,"Extension Report");
xlsWriteLabel(2,0,"S.No");
xlsWriteLabel(2,1,"Extension");
xlsWriteLabel(2,2,"Name");
xlsWriteLabel(2,3,"Secret");
xlsWriteLabel(2,4,"Dial Plan");

$xlsRow = 3;




$j=0;
$k=0;
$l=0;
for($i=0;$i<count($file);$i++){
	if(strstr($file[$i],"Category-")){
		$extension=substr(trim($file[$i]),-5);
		if(!is_numeric(trim($extension)))
		$extension=substr(trim($file[$i]),-4);
		$userArr[$j]['exten']=$extension;
		$userExtensionArr[$j] = trim($extension);
	}
	if(strstr($file[$i],": fullname")){
		$fullname=substr(trim($file[$i]),29);
		$userArr[$j]['fullname']=$fullname;
		$userFullnameArr[$j] = $fullname;
		$j++;
	}

	if(strstr($file[$i],": secret")){
		$secret = substr(trim($file[$i]),27);
		if($secret!="")
		{
			$userArr[$k]['secret']=$secret;
			$secretArr[$k] = $secret;
		}
		$k++;
	}

	if(strstr($file[$i],": context")){
		$dialplan = substr(trim($file[$i]),28);
		if($dialplan!="")
		{
			$userArr[$l]['context']=$dialplan;
			$dialplanArr[$k] = $dialplan;
		}
		$l++;
	}

}
//asort($userExtensionArr);
$i=1;
$xlsRow = 3;
foreach($userExtensionArr as $extenKey => $extenValue)
{ 
	xlsWriteLabel($xlsRow,0,$i);
	xlsWriteLabel($xlsRow,1,trim($userExtensionArr[$extenKey]));
	xlsWriteLabel($xlsRow,2,$userFullnameArr[$extenKey]); 
	xlsWriteLabel($xlsRow,3,$secretArr[$extenKey]); 	
	xlsWriteLabel($xlsRow,4,$dialplanArr[$extenKey]);
	$xlsRow++;
	$i++;
}
xlsEOF();
exit();
?>
