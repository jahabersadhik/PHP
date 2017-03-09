<?php
	if($_POST['submit'])
	{
		include "../connection.php";
		$checkedCustomers = $_POST['cust'];
		if(count($checkedCustomers))
		for($i=0;$i<count($checkedCustomers);$i++)
		{
			$customerDetailsName = "v".$checkedCustomers[$i];
			$customerDetails = explode("~",$_POST[$customerDetailsName]);
			$process = $_POST['process'];
			mysql_query("INSERT INTO previewDialing(accountId,callDate,firstname,lastname,email,address,phone,process) VALUES('$customerDetails[0]','$customerDetails[1]','$customerDetails[2]','$customerDetails[3]','$customerDetails[4]','$customerDetails[5]','$customerDetails[6]','$customerDetails[7]')");
			header("location:../home.php?page=previewmain2");
		}
		else
		header("location:../home.php?page=previewmain");
	}
?>
<form method="post" action="previewdialer/adminPreviewDialing.php">
<?php
	$process = mysql_query("SELECT * FROM process WHERE hierarchy like '%".$_SESSION['userName']."%'");
	echo "<table width='100%' cellpadding='2' cellspacing='2'>";
	echo "<tr>
 			<th width='25%' align='left'>Select the Process</th>
 			<th width='75%' align='left'>
 			<select name='process' id='process'>
 			<option value='-1'>--Select Process--</option>
 		 ";
	while($processResult = mysql_fetch_row($process))
	{
		echo "<option value='$processResult[0]'>$processResult[1]</option>";
	}
	echo "</select></th></tr></table>";
 	
	$customerMaster = mysql_query("SELECT * FROM customerMaster WHERE (status = 0 OR status = 1)  ORDER BY id");
	$callDate = date('Y-m-d');
 	echo "<table width='100%' cellpadding='2' cellspacing='2'>";
 	echo "<tr>
 			<th width='3%' align='left'><input type='checkbox' name='checkall' id='checkall' onclick='checkuncheckall()' /></th>
 			<th width='20%' align='left'>Name</th>
 			<th width='22%' align='left'>EMail</th>
 			<th width='40%' align='left'>Address</th>
 			<th width='15%' align='left'>Phone</th>
 		  </tr>";
 	$i = 0;
	while($customerResult = mysql_fetch_row($customerMaster))
	{
		if($customerResult[6]=="1"){
			echo "<tr style='background:#FF0000;'>
					<td width='3%'><input type='checkbox' name='cust[]' id='cust$i' value='$customerResult[0]' /></td>
					<td width='20%'>$customerResult[1] $customerResult[2]</td>
					<td width='22%'>$customerResult[3]</td>
					<td width='40%'>$customerResult[4]</td>
					<td width='15%'>$customerResult[5]</td>
				</tr>";
		} 
		else 
		{
			echo "<tr>
					<td width='3%'><input type='checkbox' name='cust[]' id='cust$i' value='$customerResult[0]' /></td>
					<td width='20%'>$customerResult[1] $customerResult[2]</td>
					<td width='22%'>$customerResult[3]</td>
					<td width='40%'>$customerResult[4]</td>
					<td width='15%'>$customerResult[5]</td>
				</tr>";
		}
		echo "<input type='hidden' name='v$customerResult[0]' value='$customerResult[0]~$callDate~$customerResult[1]~$customerResult[2]~$customerResult[3]~$customerResult[4]~$customerResult[5]' />";	
		$i++;
	}
	$i--;
	echo "<input type='hidden' id='totcustomer' name='totcustomer' value='$i' />";
	echo "</table>";
?>
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="center"><input type="submit" name="submit" value="Next" /></td>
</tr>
</table>
</form>
<script language="javascript">
function checkuncheckall()
{
	var tot = document.getElementById('totcustomer').value;
	if(document.getElementById('checkall').checked == true)
	for(var i=0;i<=tot;i++)
	{
		var elemId = 'cust'+i;
		document.getElementById(elemId).checked = true;
	}
	if(document.getElementById('checkall').checked == false)
	for(var i=0;i<=tot;i++)
	{
		var elemId = 'cust'+i;
		document.getElementById(elemId).checked = false;
	}
}
</script>
