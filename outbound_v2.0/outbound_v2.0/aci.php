<meta http-equiv="refresh" content="10;home.php?aci.php" /> 
<?php
	include "connection.php";
	include "functions.php";
	$condition123 = mysql_query("SELECT *FROM callsList WHERE agentId='Agent/".$isagentValue[1]."' AND nextcallDate='0000-00-00 00:00:00' ORDER BY id DESC LIMIT 0,1",$connection);
	if(mysql_num_rows($condition123)>0)
	{
		
		$cookieValue = curlauth("http://$ipaddress:8088/asterisk/rawman?action=login&username=$asteriskusername&secret=$asteriskpassword");
		
		
		$testurl = "http://$ipaddress:8088/asterisk/rawman?action=command&command=show%20queues%20$processnamevalue[2]";
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
		
		$strArr = explode("\n",$curlOutput);
		for($i=0;$i<count($strArr);$i++)
		{
			$string = "Agent/".$isagentValue[1];
			if(stristr($strArr[$i],$string))
			{
				
				if(stristr($strArr[$i],"(paused)") && stristr($strArr[$i],"(Not in use)"))
				{
					$_SESSION['wraptime'] += 5;
				}
			}
		}
		if($_SESSION['wraptime'] >= 120)
		{
			$finalCallQuery = mysql_query("SELECT duration,calldate FROM cdr WHERE lastapp='Queue' AND dstchannel='Agent/".$isagentValue[1]."' ORDER BY calldate DESC LIMIT 0,1",$connection);
			$finalCallDuration = mysql_fetch_row($finalCallQuery);
			
			//For getting exact call picked time
			$tot = strtotime($finalCallDuration[1])+$finalCallDuration[0];
			$res_exactcalldateQuery = mysql_query("SELECT a.*,b.nextcallDate,b.agentId,b.callDate FROM cdr as a JOIN callsList as b ON((UNIX_TIMESTAMP(b.callDate)>=UNIX_TIMESTAMP('".$finalCallDuration[1]."') AND UNIX_TIMESTAMP(b.callDate)<='$tot') AND a.dst = b.dst ) WHERE a.src='".$row['src']."' AND a.lastapp='Dial' ORDER BY a.calldate",$connection) or die(mysql_error());
			$row_1 = mysql_fetch_array($res_exactcalldateQuery);
			$nextcalltime = strtotime($row_1['callDate'])+120+($finalCallDuration[0]-(strtotime($row_1['callDate'])-strtotime($finalCallDuration[1])));
			
			$res = mysql_query("SELECT max(id) FROM callsList where agentId='Agent/$isagentValue[1]' AND nextcallDate='0000-00-00 00:00:00'",$connection);
			$row = mysql_fetch_row($res);
			$nowdate = date("Y-m-d G:i:s");
			mysql_query("UPDATE callsList SET nextcallDate=FROM_UNIXTIME('$nextcalltime') WHERE id='$row[0]'",$connection);
			
			$sql_Break = mysql_query("SELECT startdate FROM Break WHERE agentId='$isagentValue[1]' AND enddate='0000-00-00 00:00:00'  ",$connection);
			$val = mysql_fetch_row($sql_Break);
			$sdate = strtotime($val[0]);
			$edate = strtotime(date('Y-m-d G:s:i'));
			$break = ($edate -$sdate );
			mysql_query("UPDATE Break SET enddate='".date('Y-m-d G:s:i')."',breaktime='$break' WHERE agentId ='$isagentValue[1]'  AND enddate='0000-00-00 00:00:00'",$connection); 
			
			$actionid = rand(11111,99999);
			$ch = curl_init();
			$testurl = "http://$ipaddress:8088/asterisk/rawman?action=QueuePause&interface=Agent/$isagentValue[1]&Paused=false";
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
			if(isset($_SESSION['wraptime']))
				unset($_SESSION['wraptime']);
		}
	}
?>



<?php
	$today = date('Y-m-d');
	$time = strtotime(date('Y-m-d G:i:s')) - 10;
	$time1 = strtotime(date('Y-m-d G:i:s')) + 10;
	$condition = mysql_query("SELECT *from cdr WHERE lastapp='Queue' AND calldate like '%$today%' AND dstchannel!='' AND dstchannel = 'Agent/$isagentValue[1]'",$connection);
	
	$approvedBreaksQuery = mysql_query("SELECT id,date,starttime,breaktype,popup FROM approvedBreak WHERE agent like '%$isagentValue[1]%' AND date='$today' AND popup NOT LIKE '%$isagentValue[1]%'",$connection);
	/*if(mysql_num_rows($approvedBreaksQuery)>0)
	{
		$approvedBreakResult = mysql_fetch_row($approvedBreaksQuery);
		$approvedBreakResult[1]."<br>".$approvedBreakResult[2];
		$approvedBreakTime = strtotime($approvedBreakResult[1]." ".$approvedBreakResult[2]);
		if($approvedBreakTime >= $time && $approvedBreakTime <= $time1)
		{
			$ins = $approvedBreakResult[4].",".$_SESSION['userName'];
			mysql_query("UPDATE approvedBreak set popup='$ins' WHERE id='$approvedBreakResult[0]'",$connection);
			echo "<Script language='javascript'>
				var i = confirm('Now you have ".$approvedBreakResult[3]." break. Kindly confirm?');
				if(i)
					location.href = 'changeBreak.php?val=tb&type=$approvedBreakResult[3]&time=$approvedBreakResult[2]';
				</script>";
		}
	}*/
//	$res = mysql_query("SELECT a.accountcode,a.calldate,a.src,a.duration,a.billsec,b.nextcallDate FROM cdr as a JOIN callsList as b ON((UNIX_TIMESTAMP(a.calldate)+(a.duration-a.billsec ))=UNIX_TIMESTAMP(b.callDate) AND instr(b.dst,a.dst)) WHERE 1  AND a.lastapp = 'Dial' AND a.calldate like '%$today%'",$connection);

	echo "
			<table width='100%' cellpadding='2' cellspacing='2'>
			<tr>
				<td colspan='2' width='50%'>";
	
	if(mysql_num_rows($condition123)>0 && isset($_SESSION['wraptime']))
	{
		echo "			<form method='post' action='changeBreak.php' style='padding:0px;margin:0px;'>
						<input type='hidden' name='val' value='bo' />
						<input type='hidden' name='callpause' value='1' />
						<input type='submit' value='Ready to Take another Call' />
						</form>
			 ";
		$disabled = "disabled";
	}
	else 
	{
		$disabled = "";	
		echo "&nbsp;";
	}
	echo "</td>
	      
	      <td colspan='1' width='50%'>";
	
	echo "		</td>
			</tr>
			<tr>
				<td bgcolor='#ff9900'><b>Source</b></td>
				<td bgcolor='#ff9900'><b>Duration</b></td>
				<!--<td bgcolor='#ff9900'><b>Bill Seconds</b></td>-->
				<td bgcolor='#ff9900'><b>Wrap Time</b></td>
			</tr>
		 ";
	$calls =0;
	while($row = mysql_fetch_array($condition))
	{
		$dur = strtotime($row['calldate'])+$row['duration'];
$res1 = mysql_query("SELECT a.*,b.nextcallDate,b.agentId,b.callDate FROM cdr as a JOIN callsList as b ON((UNIX_TIMESTAMP(b.callDate)>=UNIX_TIMESTAMP('".$row['calldate']."') AND UNIX_TIMESTAMP(b.callDate)<='$dur') AND a.dst = b.dst ) WHERE a.src='".$row['src']."' AND a.lastapp='Dial' ORDER BY a.calldate",$connection) or die(mysql_error());
		if(mysql_num_rows($res1)>0)
		{
			$row1 = mysql_fetch_array($res1);
			$wraptime = (strtotime($row1['nextcallDate'])-strtotime($row1['callDate']))-($row['duration']-(strtotime($row1['callDate'])-strtotime($row['calldate'])));
			if($wraptime<0)
				$wraptime = '-';
			if(is_numeric($wraptime))
			{
				$totalWrapTime += $wraptime;
				 
			}
			//$totalCallDuration += $row['duration']; 
			$totalCallDuration += ($row['duration']-(strtotime($row1['callDate'])-strtotime($row['calldate']))); 
			$totalBillSec += $row[3];
			echo "
					<tr>
						<td bgcolor='#fcfcfc'>".$row['src']."</td>
						<!--<td bgcolor='#fcfcfc'>".$row['duration']."</td>-->
						<td bgcolor='#fcfcfc'>".($row['duration']-(strtotime($row1['callDate'])-strtotime($row['calldate'])))."</td>
						<!--<td bgcolor='#fcfcfc'>$row[3]</td>-->
						<td bgcolor='#fcfcfc'>$wraptime</td>
					</tr>
				 ";
			$calls++;
		}
	}
	if($calls>0)
	{
		$totalWrapTime = converthours($totalWrapTime);
		$totalCallDuration = converthours($totalCallDuration);
		$totalBillSec = converthours($totalBillSec);
		echo "<tr><td bgcolor='#ff9900'><b>TotalCalls:</b>$calls</td><td bgcolor='#669900'><b>TotalCallsDuration:</b>$totalCallDuration</td><!--<td bgcolor='#999999'><b>TotalBilledSeconds:</b>$totalBillSec</td>--><td bgcolor='#f6f6f6'><b>TotalWrapTime:</b>$totalWrapTime</td></tr>";
	}
	echo "</table>";
	
?>

