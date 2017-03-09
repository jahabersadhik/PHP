<?php
include("connection.php");
function find($start,$end,$string)
{
	$startlength = strpos($string,$start);//+strlen($start);
	$endlength = strpos($string,$end);
	$finallength = $endlength - $startlength;
	$final = substr($string,$startlength,$finallength);
	return $final;
}
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
setcookie("mansession_id",$cookieValue,time()+3600,$strToAdd,$FSERVER);
curl_close($ch);
			


$pid = $_REQUEST['pid'];//$pidArr[0];
$res = mysql_query("SELECT team,breaktime,queueName FROM process WHERE processId = '$pid'");
$extensionsResult = mysql_fetch_row($res);
//$extensionsArr = explode(",",$extensionsResult[0]);
$queueName = $extensionsResult[2];
$agentsQuery = mysql_query("SELECT members FROM queues WHERE queueName='$queueName'");
$agentsResult = mysql_fetch_row($agentsQuery);
$agentsArr = explode(",",$agentsResult[0]);
//print_r($agentsArr);
//print_r($extensionsArr);
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=command&command=show%20queues%20$queueName");
curl_setopt($ch1, CURLOPT_HEADER, 1);
curl_setopt ($ch1, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch1, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput_status = curl_exec($ch1);
$arr1 = explode("Privilege: Command",$curlOutput_status);
//echo "<pre>";

$arrnew1 = explode("Members:",$arr1[1]);
$arr = explode("\n",$arrnew1[1]);
//print_r($arr);
echo "<table width='100%' cellpadding='2' cellspacing='2'>";
echo "<tr style='background:#ff9900;color:#fff;'>
		<th width='25%' height='25'>Agent Name</th>
		<th width='25%' height='25'>Logged In Extension</th>
		<th width='10%'>Status</th>
		<th width='10%'>Current Break Time (Sec)</th>
		<th width='10%'>Total Break Time <br>(Sec)</th>
		
	  </tr>";
$count = 0;
//print_r($arr);
for($i=1,$j=0;$i<count($arr);$i++)
{
	$str2 = str_replace(" ","",trim($arr[$i]));
	$str1 = $arr[$i];
	if(stristr($str1,'Agent/'))
    {
    	$channel1[$j] = substr(str_replace("AGENT/","",$str2),0,4);
        $numofagents++;
        if(stristr($str1,'Unavailable'))
        {
                $status[$j] = "Logged Out";
        }
        if(stristr($str1,'Busy'))
        {
                $status[$j] = "Busy";
        }
        if(stristr($str1,'Not in use'))
        {
                $status[$j] = "Logged In";
        }
        $j++;
    }
}
/*print_r($status);
print_r($channel1);*/
	/*if(strstr($str1,"Seconds:"))
	{
		$seconds1 = find("Seconds:","Link:",$str1);
		$seconds1 = trim(str_replace("Seconds:","",$seconds1));
	}*/
	//if(in_array($channel1,$agentsArr))
	for($i=0;$i<count($channel1);$i++)
	{
		if($status[$i] == 'Logged In' || $status[$i] == 'Busy')
		{
			
			$statusQuery = mysql_query("SELECT * FROM loginLog WHERE empId='".$channel1[$i]."' AND logoutDate='0000-00-00' ORDER BY id DESC LIMIT 0,1");
			$statusResult = mysql_fetch_row($statusQuery);
			//echo strtotime($statusResult[8]);
			if($statusResult[8] == "0000-00-00 00:00:00")
			{
				$currentbreaktime1 = 0;
				$totalbreaktime1 = $statusResult[9];
			}
			if($statusResult[8] != "0000-00-00 00:00:00")
			{
				$currentbreaktime1 = strtotime(date('Y-m-d G:i:s')) - strtotime($statusResult[8]);
				$totalbreaktime1 = $statusResult[9]+$currentbreaktime1;
			}
			$name = $statusResult[7];
			echo "
					  <tr style='background:#f6f6f6;'>
					  	<td width='25%'>$channel1[$i]</td>
					  	<td width='25%'>$name</td>
					  	<td width='10%'>$status[$i]</td>
					  	<td width='10%'>$currentbreaktime1</td>
					  	<td width='10%'>$totalbreaktime1</td>
					  </tr>
					 ";
			$currentbreaktime1 = 0;
			$totalbreaktime1 = 0;
		}
		else 
		{
			$currentbreaktime1 = 0;
			$totalbreaktime1 = 0;
			$name = "";
			//$name = $statusResult[7];
			echo "
					  <tr style='background:#f6f6f6;'>
					  	<td width='25%'>$channel1[$i]</td>
					  	<td width='25%'>$name</td>
					  	<td width='10%'>$status[$i]</td>
					  	<td width='10%'>$currentbreaktime1</td>
					  	<td width='10%'>$totalbreaktime1</td>
					  </tr>
					 ";
		}
	}
			
	
//}
//$resultArr  = array_diff($agentsArr,(array)$key);
//print_r($resultArr);

/*for($i=0;$i<count($resultArr);$i++)
{
	$statusQuery = mysql_query("SELECT * FROM loginLog WHERE empId='".trim($resultArr[$i])."' AND logoutDate='0000-00-00' ORDER BY id DESC LIMIT 0,1");
	if(@mysql_num_rows($statusQuery))
	{
		$statusResult = mysql_fetch_row($statusQuery);
		if($statusResult[6] == 1)
		{
			$status = "Logged In";
			$name = $statusResult[5];
			$currentbreaktime = 0;
			$totalbreaktime = $statusResult[9];
		}
		if($statusResult[6] == 2) 	
		{
			$status = "In Break";
			$name = $statusResult[5];
			$currentbreaktime = strtotime(date('Y-m-d G:i:s')) - strtotime($statusResult[8]);
			$totalbreaktime = $statusResult[9]+$currentbreaktime;
		}
		if($statusResult[6] == 0) 	
		{
			$status = "Logged Out";
			$name = "-";
			$currentbreaktime = "-";
			$totalbreaktime = "-";
		}
	}
	else 
	{
		$name = "-";
		$status = "Logged Out";
		$currentbreaktime = "-";
		$totalbreaktime = "-";
	}
	
	if($totalbreaktime > $extensionsResult[1])
	{
		$val = '<span style="color:#FF0000;">*</span>';
	} 
	else 
	{
		$val = '';
	}
	
	echo "
		  <tr style='background:#f6f6f6;'>
		  	<td width='25%'>$resultArr[$i]</td>
		  	<td width='25%'>$name</td>
			<td width='10%'>$status</td>
			<td width='10%'>$currentbreaktime</td>
			<td width='10%'>$totalbreaktime $val</td>
		  </tr>
		 ";
}*/
echo "</table>";
?>
