<?php
/***********************************************************************************
Created By : Mallikarjuna Rao
Created Date: 7th August 2010
Modified Date : 7th August 2010
Purpose : To Create an Inbound(0) or Outbound(1) Process which helps in generating reports
Linked Tables : is_process,is_hierarchy

***********************************************************************************/
?>
<?php
	if($_POST['id']  && $_POST['processname'])
	{
		$queuename = $_POST['queuename'];
		$processname = $_POST['processname'];
		$previousvalue = $_POST['previousvalue'];
		
		mysql_query("UPDATE is_process SET queue='$queuename' WHERE id='".$_POST['id']."'",$connection);
		$timestamp = date("Y-m-d G:i:s");
		$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Inbound process Edit','$processname<br>$queuename<br>','$previousvalue','$timestamp')",$connection);
		echo "<script language='javascript'>
				alert('Process Updated Successfully');
				location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
			  </script>
			 ";
	}
?>
<?php

if($_POST['id'] && !$_POST['processname'])
{
	$processdetailsQuery = mysql_query("SELECT *FROM is_process WHERE id='".$_POST['id']."'",$connection);
	$row = mysql_fetch_row($processdetailsQuery);
?>
<script language="javascript">
function Validate()
{
	if(document.getElementById('processname').value=='')
	{
		alert("Please Enter the Inbound Process Name");
		document.getElementById('processname').focus();
		return false;
	}
	if(document.getElementById('queuename').value=='-1')
	{
		alert("Please Select the Queue for Inbound Process");
		document.getElementById('queuename').focus();
		return false;
	}
	return true;
}
</script>
<form method="POST" name="frmaddprocess" action="home.php?<?=$_SERVER['QUERY_STRING']?>">
<input type="hidden" name="id" value="<?=$_POST['id']?>" />
<input type="hidden" name="previousvalue" value="<?php echo $row[2]."<br>".$row[3]."<br>";?>" />
<table width="40%" cellpadding="0" cellspacing="0" style="margin:auto">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><span class="heading">Edit In-Bound Process</span></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Process Name <span class="red">*</span></td>
	<td><input type="text" name="processname" id="processname" readonly value="<?=$row[2]?>" /></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Assign Queue <span class="red">*</span></td>
	<td>
		<?php
			$assignedQueues = mysql_query("SELECT queue FROM is_process WHERE processtype = '0'",$connection);
			while($row2 = mysql_fetch_row($assignedQueues))
			{
				if(trim($row2[0])!='')
				$assignedQueuesArr[] = $row2[0];
			}
			if(count($assignedQueuesArr)>0)
				$existingQueues = implode(",",$assignedQueuesArr);
			else 	
				$existingQueues = '0';
		?>
		<select name="queuename" id="queuename" class="input">
		<option value="-1">--select Queue--</option>
		<option value="<?php echo $row[3];?>" selected><?php echo $row[3];?></option>
		<?php
			$availableQueues = mysql_query("SELECT queue FROM is_queue WHERE queue NOT IN($existingQueues)",$connection);
			while($row1 = mysql_fetch_row($availableQueues))
			{
			 echo "<option value='$row1[0]'>$row1[0]</option>";
			}
		?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="image" src="images/save.png" style="border:0px;" onclick="return Validate();"></td>
</tr>
</table>
</form>
<?php
}
else 
{
	$processQuery = mysql_query("SELECT *FROM is_process WHERE processtype='0'",$connection);
	$i=1;
	while($row1 = mysql_fetch_row($processQuery))
	{
		$queuename = $row1[3];
		$processname = $row1[2];
		if($i%2==0 && $i!=0)
			$classname = "stylefourtext";
		else 	
			$classname = "stylefourtext1";
?>
<?php
		if($i==1)
		{
?>
			<table width="100%" cellpadding="0" cellspacing="1">
			<tr>
				<td colspan="3" align="center"><span class="heading">Edit In-Bound Process</span></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="stylefour">Process Name</td>
				<td class="stylefour">Assigned Queue</td>
				<td class="stylefour">Edit</td>
			</tr>
				
<?php
		}
?>
		<tr>
			<td class="<?=$classname?>"><?=$processname?></td>
			<td class="<?=$classname?>"><?=$queuename?></td>
			<td class="<?=$classname?>">
				<form method="POST" action="home.php?<?=$_SERVER['QUERY_STRING']?>">
					<input type="hidden" name="id" value="<?=$row1[0]?>" />
					<input type="image" src="images/edit.png" style="border:0px;" />
				</form>
			</td>
		</tr>
<?php
		$i++;
	}
?>
	</table>

<?php
}
?>