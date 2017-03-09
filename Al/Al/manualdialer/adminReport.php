<?php
	$crmQuery = mysql_query("SELECT accountId,firstname,lastname FROM current GROUP BY accountId");
	$processQuery = mysql_query("SELECT processId,processName FROM process");
?>
<form method="POST" action="home.php?page=manualreport">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="10%">Process</td>
	<td width="20%">
	<select name="process">
	<option value="-1">--Select Process--</option>
	<?php
		while($process = mysql_fetch_row($processQuery))
		{
			if($_POST['process'] == $process[0])
				echo "<option value='$process[0]' selected>$process[1]</option>";
			else 	
				echo "<option value='$process[0]'>$process[1]</option>";
		}
	?>
	</select>
	</td>
	<td width="2%"></td>
	<td width="10%">Customer</td>
	<td width="20%">
	<select name="customer">
	<option value="-1">--Select Customer--</option>
	<?php
		while($customer = mysql_fetch_row($crmQuery))
		{
			if($_POST['customer'] == $customer[0])
				echo "<option value='$customer[0]' selected>$customer[1]&nbsp;$customer[2]</option>";
			else 	
				echo "<option value='$customer[0]'>$customer[1]&nbsp;$customer[2]</option>";
		}
	?>
	</select>
	</td>
	<td width="2%"></td>
	<td width="5%">Date</td>
	<td width="25%"><input type="text" name="date" value="<?php echo $_POST['date'];?>" style="width:120px;" /></td>
</tr>
<tr>
	<td colspan="8">&nbsp;</td>
</tr>
<tr>
	<td colspan="8" align="right" style="padding-right:25px;"><input type="submit" name="submit" value="Generate" /></td>
</tr>	
</table>
</form>
<?php
	if($_POST)
	{
		$processId = $_REQUEST['process'];
		$accountId = $_REQUEST['customer'];
		$date = $_REQUEST['date'];
		$query = "SELECT *FROM manualDialing WHERE 1 AND calledExtension IS NOT NULL";
		if(trim($processId)!='-1')
			$query = $query." AND process='$processId'";
		if(trim($accountId)!='-1')
			$query = $query." AND accountId='$accountId'";
		if(trim($date)!='')
			$query = $query." AND callDate='$date'";
		//echo $query;
		$result = mysql_query($query);
		//echo "<pre>";
		while($row = @mysql_fetch_array($result))
		{
			$agentDetailsQuery = mysql_query("SELECT firstname,lastname FROM userData WHERE empId=ANY(SELECT empId FROM loginLog WHERE loginDate='$row[2]')");
			$agentDetailsResult = mysql_fetch_row($agentDetailsQuery);
			
?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2" bgcolor="#f6f6f6">
		<?php echo $agentDetailsResult[0]." ".$agentDetailsResult[1]." ".$row[5];?>
	</td>
</tr>
<tr>
	<td width="50%">
<?php 
	$No = 1 ;	
	if($row[13]!="")
	{
		$commentsRow = split("~!",$row[13]);
		$count = count($commentsRow);
		for ($I=1; $I<$count; $I++)
		{ 
			$No = $I;
			$val = split("~",$commentsRow[$I]);
			$cDates=date("jS M, Y H:i:s",strtotime("$val[0]"));
			$comms = $val[1]; 
			$Ccomms = $val[2]; 
?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td><?php echo "<b>Comment #"." "."$No"."</b>"?></td>
				<td></td>						
				<td align="center"><?php echo "<font color='white'>&nbsp;</font>";?></td>
				<td></td>							
				<td align="right"><?php echo "<font color='white'>$cDates</font>";?></td>											
			</tr>
			</table>
			<table width="100%">
			<tr>
				<td><?php echo "Agent Comments:".$comms ."<BR><BR> Client Commentd:".$Ccomms ;?></td>
			</tr>
			</table>
<?php 
		} 
	}
	$No = $No + 1;
?>
	</td>
	<td width="50%" style="padding-left:20px;">
		<?php
			echo "<b>Name:</b> ".$row[7]." ".$row[8]."<br><b>Address:</b> ".$row[9]."<br><b>EMail:</b> ".$row[10]."<br><b>Phone:</b> ".$row[11];
		?>
	</td>
</tr>
</table>		
<?php
		}
	}
?>