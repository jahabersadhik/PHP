<?php 

$sql_select_process = mysql_query("SELECT * FROM process WHERE hierarchy like '%".$_SESSION['userName']."%'");
//$sql_select_agent = mysql_query("SELECT * FROM userData WHERE designation = 'AGENT' AND ");
$sql_select_agent = mysql_query("SELECT queues.members FROM queues JOIN process ON(queues.queueName = process.queueName AND process.processId='".$_POST['selProcess']."')");
$s=mysql_fetch_row($sql_select_agent);
if(trim($s[0])!='')
$sql_select_agent_arr = explode(",",$s[0]);

if($_REQUEST['hidID']=="1"){
	$sql_process_date = mysql_query("SELECT * FROM process WHERE processId = ".$_REQUEST['selProcess']."");
	$val = mysql_fetch_array($sql_process_date);
	$queue = $val['queueName'];
	$startDate = $_REQUEST['startDate'];
	$endDate = $_REQUEST['endDate'];
	mysql_close();
	
	$connection = mysql_connect("localhost","root","PeRi") or die();
	mysql_select_db("cdr",$connection);
	if($_REQUEST['selAgent']!="0")
	{
		$condition = " AND dstchannel = '".$_REQUEST['selAgent']."'";
	} 
	else 
	{
		$condition = "";
	}
	$sql_select_cdr_incoming = mysql_query("SELECT * FROM cdr WHERE dst = '".$queue."' $condition AND lastapp = 'Queue' AND UNIX_TIMESTAMP(calldate) >= '".strtotime($startDate. " 00:00:00")."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($endDate." 23:59:59")."'");
	$count = mysql_num_rows($sql_select_cdr_incoming);
}
	$queryString = "SELECT * FROM cdr WHERE dst = '".$queue."' $condition AND lastapp = 'Queue' AND UNIX_TIMESTAMP(calldate) >= '".strtotime($startDate." 00:00:00")."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($endDate." 23:59:59")."'";

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
	} else if(document.frmChart.selProcess.value=="0"){
		alert("Please select the process.");
		document.frmChart.selProcess.focus();
		return false;
	} else {
		document.frmChart.method = 'post';
		document.frmChart.action = 'home.php?page=activityreport';
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
<td>Campaign Name :</td>
	<td>
		<select name="selProcess" onchange="javascript:checkDate();">
			<option value="0">Select Process</option>
			<?php while($row =@mysql_fetch_array($sql_select_process)){ ?>
			<option value="<?php echo $row['processId']; ?>" <?php if($_REQUEST['selProcess']==$row['processId']){ ?> selected <?php } ?>><?php echo $row['processName']; ?></option>
			<?php } ?>
		</select>
	</td>
<td> Agent Name :</td>
	<td>
		<select name="selAgent" onchange="javascript:checkDate();">
			<option value="0">Select agent name</option>
			<?php //while($row_agent = @mysql_fetch_array($sql_select_agent)){ 
				    for($i=0;$i<count($sql_select_agent_arr);$i++)
				    {
			?>
			<!--<option value="<?php echo 'Agent/'.$row_agent['empId']; ?>" <?php if($_REQUEST['selAgent']=='Agent/'.$row_agent['empId']){ ?> selected <?php } ?>><?php echo $row_agent['firstname']; ?></option>-->
					<option value="<?php echo 'Agent/'.$sql_select_agent_arr[$i]; ?>" <?php if($_REQUEST['selAgent']=='Agent/'.$sql_select_agent_arr[$i]){ ?> selected <?php } ?>><?php echo $sql_select_agent_arr[$i]; ?></option>
			<?php } ?>
		</select>
	</td>
</tr>

<tr>
	<td colspan="3">
		<input type="button" name="tbnSearch" value="Search" onclick="javasvript:checkDate();">
	</td>
	<td><a href="home.php?page=activityreportout">Out Bound Activity Report</a></td>
	<td colspan="2" align="right">
		<input type="button" name="tbnSearch" value="Export XLS" onclick="location.href='export1.php?q=<?php echo urlencode($queryString);?>';">
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
		<th>Agent Id </th>
		<th>Duration </th>
		<th>Bill Sec. </th>
		<th>Disposition </th>
	</tr>
	<?php   
		if($count!="0")
		{
			$i = 1; 
			while ($row =@mysql_fetch_array($sql_select_cdr_incoming))
			{
				if(trim($row['dstchannel'])!="")
				{
	?>
			<tr aligh="center" bgcolor="#fcfcfc">
				<td><?=$i?></td>
				<td><?=$row['calldate']?></td>
				<td><?=$row['clid']?></td>
				<td><?=$row['src']?></td>
				<td><?=$row['dstchannel']?></td>
				<td><?=$row['duration']?></td>
				<td><?=$row['billsec']?></td>
				<td><?=$row['disposition']?></td>
			</tr>
	<?php $i++; 
				} 
			}
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



