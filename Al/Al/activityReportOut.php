<?php 
if($_REQUEST['hidID']=="1")
{
	$startDate = $_REQUEST['startDate'];
	$endDate = $_REQUEST['endDate'];
	mysql_close();
	
	$connection = mysql_connect("localhost","root","PeRi") or die();
	mysql_select_db("cdr",$connection);
	if($_REQUEST['exten']!="0")
	{
		$condition = " AND src = '".$_REQUEST['exten']."'";
	} 
	else 
	{
		$condition = "";
	}
	$sql_select_cdr_outgoing = mysql_query("SELECT * FROM cdr WHERE length(src) = '4' $condition AND lastapp = 'dial' AND UNIX_TIMESTAMP(calldate) >= '".strtotime($startDate." 00:00:00")."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($endDate." 23:59:59")."'");
	$count = mysql_num_rows($sql_select_cdr_outgoing);
}
	$queryString = "SELECT * FROM cdr WHERE length(src) = '4' $condition AND lastapp = 'dial' AND UNIX_TIMESTAMP(calldate) >= '".strtotime($startDate." 00:00:00")."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($endDate." 23:59:59")."'";
?>
<?php
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
	$testurl = "https://$IPAddress/asterisk/rawman?action=getconfig&filename=users.conf";
	curl_setopt($ch, CURLOPT_URL, $testurl);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	//echo "<pre>";
	$curlOutput = curl_exec($ch);
	curl_close($ch);
	$userExplode = explode("Category-000",$curlOutput);
	for($i=0,$k=0;$i<count($userExplode);$i++)
	{
		$user = explode("\n",$userExplode[$i]);
		if(is_numeric(trim(substr($user[0],4,strlen($user[0])))))
		{
			$userArr[$k] = trim(substr($user[0],4,strlen($user[0])));
			$k++;
		}
	}
?>
<script>

function checkDate(){
	if(document.frmChart.startDate.value==""){
		alert("Please select the start date.");
		document.frmChart.startDate.focus();
		return false;
	} else if(document.frmChart.endDate.value==""){
		alert("Please select the end date.");
		document.frmChart.endDate.focus();
		return false;
	} else {
		document.frmChart.method = 'post';
		document.frmChart.action = 'home.php?page=activityreportout';
		document.frmChart.submit();
	}
	
}
</script>
<script type="text/javascript" src="./js/jquery.js"></script>
<script src="./js/thickbox-compressed.js" type="text/javascript"></script>
<link rel="stylesheet" href="./css/thickbox.css" type="text/css" media="screen" />

<form name="frmChart" method="POST">
<table>
<tr>
</tr>
<tr>
<td> Start Date :</td>
<td> <input type="text" name="startDate" id="startDate" size="15" value="<?php echo $startDate; ?>" />&nbsp;&nbsp;</td>
<td> End Date :</td>
<td> <input type="text" name="endDate" id="endDate" size="15" value="<?php echo $endDate; ?>" /></td>
</tr>
<tr>
<td> From Exten :</td>
	<td>
		<select name="exten" onchange="javascript:checkDate();">
			<option value="0">Select Extension</option>
			<?php 
				for($i=0;$i<count($userArr);$i++)
				{ 
			?>
					<option value="<?php echo $userArr[$i]; ?>" <?php if($_REQUEST['exten']==$userArr[$i]){ ?> selected <?php } ?>><?php echo $userArr[$i]; ?></option>
			<?php 
				} 
			?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<input type="button" name="tbnSearch" value="Search" onclick="javasvript:checkDate();">
	</td>
	<td colspan="2" align="right">
		<input type="button" name="tbnSearch" value="Export XLS" onclick="location.href='export.php?q=<?php echo urlencode($queryString);?>';">
	</td>
</tr>
<input type="hidden" name="hidID" value="1">
</table>
</form>
<table width="100%" cellpadding="2" cellspacing="2" >
	<tr bgcolor="#FF0000;">
		<th>S.No</th>
		<th>Call Date</th>
		<th>Caller Id</th>
		<th>Source </th>
		<th>Destination </th>
		<th>Duration </th>
		<th>Bill Sec. </th>
		<th>Disposition </th>
	</tr>
	<?php   if($count!="0")
		{
			$i = 1; 
			while ($row =@mysql_fetch_array($sql_select_cdr_outgoing)){ ?>
			<tr aligh="center" bgcolor="#fcfcfc">
				<td><?=$i?></td>
				<td><?=$row['calldate']?></td>
				<td><?=$row['clid']?></td>
				<td><?=$row['src']?></td>
				<td><?=$row['dst']?></td>
				<td><?=$row['duration']?></td>
				<td><?=$row['billsec']?></td>
				<td><?=$row['disposition']?></td>
			</tr>
			<?php $i++; } 
		}
		else
		{
			
			echo "<tr> 
				<td colspan='8' align='center' color='#FF0000'>
					No data found...
				</td></tr>";
		}
	?>
</table>



