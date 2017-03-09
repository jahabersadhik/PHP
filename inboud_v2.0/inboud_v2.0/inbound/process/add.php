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
	if($_POST)
	{
		$queuename = $_POST['queuename'];
		$processname = $_POST['processname'];
		$check = mysql_query("SELECT *FROM is_process WHERE processname='$processname'",$connection);
		if(mysql_num_rows($check)==0)
		{
			mysql_query("INSERT INTO is_process VALUES(NULL,'0','$processname','$queuename')",$connection);
			$timestamp = date("Y-m-d G:i:s");
			$insertTracking = mysql_query("INSERT INTO is_tracking VALUES(NULL,'".$_SESSION['userid']."','Add Inbound Process','$processname<br>$queuename<br>','','$timestamp')",$connection);
			echo "<script language='javascript'>
					alert('Process Created Successfully');
					location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
				  </script>
				 ";
		}
		else 
		{
			echo "<script language='javascript'>
					alert('This Process Name already exists');
					location.href = 'home.php?".$_SERVER['QUERY_STRING']."'
				  </script>
				 ";
		}
	}
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
<table width="40%" cellpadding="0" cellspacing="0" style="margin:auto">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><span class="heading">Add In-Bound Process</span></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Process Name <span class="red">*</span></td>
	<td><input type="text" name="processname" id="processname" value="<?=$_POST['processname']?>" /></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="50%">Assign Queue <span class="red">*</span></td>
	<td>
		<?php
			$assignedQueues = mysql_query("SELECT queue FROM is_process WHERE processtype = '0'",$connection);
			while($row = mysql_fetch_row($assignedQueues))
			{
				if(trim($row[0])!='')
				$assignedQueuesArr[] = $row[0];
			}
			if(count($assignedQueuesArr)>0)
				$existingQueues = implode(",",$assignedQueuesArr);
			else 	
				$existingQueues = '0';
		?>
		<select name="queuename" id="queuename" class="input">
		<option value="-1">--select Queue--</option>
		<?php
			$availableQueues = mysql_query("SELECT queue FROM is_queue WHERE queue NOT IN($existingQueues)",$connection);
			while($row1 = mysql_fetch_row($availableQueues))
			 echo "<option value='$row1[0]'>$row1[0]</option>";
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