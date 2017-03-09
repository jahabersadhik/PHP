<?php
	if($_POST['submit'])
	{
		include "../connection.php";
		$checkedCustomers = $_POST['cust'];
		if(count($checkedCustomers))
		{
			for($i=0;$i<count($checkedCustomers);$i++)
			{
				$customerDetailsName = "v".$checkedCustomers[$i];
				$customerDetails = explode("~",$_POST[$customerDetailsName]);
				$process = $_POST['process'];
				$elemName = 'assignedExtension'.$customerDetails[0];
				$assignedExtension = $_REQUEST[$elemName];
				mysql_query("INSERT INTO manualDialing(accountId,callDate,firstname,lastname,email,address,phone,process,calledExtension) VALUES('$customerDetails[0]','$customerDetails[1]','$customerDetails[2]','$customerDetails[3]','$customerDetails[4]','$customerDetails[5]','$customerDetails[6]','$customerDetails[7]','$assignedExtension')");
				header("location:home.php?page=manualmain");
				$_SESSION['result'] = "Agents Assigned Successully for Customers";
			}
		}
		else
		{
			header("location:home.php?page=manualmain");
			$_SESSION['result'] = "Please select Customers";
		}
	}
?>
<form method="post" action="home.php?page=manualmain" id="frm1" name="frm1">
<?php
	$process = mysql_query("SELECT * FROM process WHERE hierarchy like '%".$_SESSION['userName']."%'");
	$agents = mysql_query("");
	echo "<table width='100%' cellpadding='2' cellspacing='2'><tr><td colspan='2'>".$_SESSION['result']."</td></tr>";
	echo "<tr>
 			<th width='25%' align='left'>Select the Process</th>
 			<th width='75%' align='left'>
 			<select name='process' id='process' onchange=\"updateExtensions()\">
 			<option value='-1'>--Select Process--</option>
 		 ";
	while($processResult = mysql_fetch_row($process))
	{
		if($_REQUEST['pid'] == $processResult[0])
		echo "<option value='$processResult[0]' selected>$processResult[1]</option>";
		else
		echo "<option value='$processResult[0]'>$processResult[1]</option>";
	}
	echo "</select></th></tr>";
	echo "</table>";
 	
	$customerMaster = mysql_query("SELECT * FROM customerMaster WHERE (status = 0 OR status = 1)  ORDER BY id");
	$callDate = date('Y-m-d');
 	echo "<table width='100%' cellpadding='2' cellspacing='2'>";
 	echo "<tr>
 			<th width='3%' align='left'><input type='checkbox' name='checkall' id='checkall' onclick='checkuncheckall()' /></th>
 			<th width='20%' align='left'>Name</th>
 			<th width='22%' align='left'>EMail</th>
 			<th width='40%' align='left'>Address</th>
 			<th width='15%' align='left'>Phone</th>
 			<th width='15%' align='left'>AssignExtn</th>
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
	 				<td width='15%'>
	 					<select name='assignedExtension$customerResult[0]' id='assignedExtension'>
	 						<option value='-1'>Extn</option>";
							$extensionsResult = mysql_query("SELECT team FROM process WHERE processId='".$_REQUEST['pid']."'");
							$extensionsRow = mysql_fetch_row($extensionsResult);
							$extensionsArr = explode(",",$extensionsRow[0]);
							for($j=0;$j<count($extensionsArr);$j++)
							{
								if(trim($extensionsArr[$j])!='')
								echo "<option value='$extensionsArr[$j]'>$extensionsArr[$j]</option>";
							}
	 				echo "</select> 
	 				</td>
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
	 				<td width='15%'>
	 					<select name='assignedExtension$customerResult[0]' id='assignedExtension'>
	 						<option value='-1'>Extn</option>";
							$extensionsResult = mysql_query("SELECT team FROM process WHERE processId='".$_REQUEST['pid']."'");
							$extensionsRow = mysql_fetch_row($extensionsResult);
							$extensionsArr = explode(",",$extensionsRow[0]);
							for($j=0;$j<count($extensionsArr);$j++)
							{
								if(trim($extensionsArr[$j])!='')
								echo "<option value='$extensionsArr[$j]'>$extensionsArr[$j]</option>";
							}
	 				echo "</select> 
	 				</td>
	 		  	</tr>";
		}
		echo "<input type='hidden' name='v$customerResult[0]' value='$customerResult[0]~$callDate~$customerResult[1]~$customerResult[2]~$customerResult[3]~$customerResult[4]~$customerResult[5]' />";	
		$i++;
	}
	$i--;
	echo "<input type='hidden' id='totcustomer' name='totcustomer' value='$i' />";
	echo "</table>";
	unset($_SESSION['result']);
?>
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="center"><input type="submit" name="submit" value="Assign" /></td>
</tr>
</table>
</form>
<script language="javascript">
function checkuncheckall()
{
	var tot = document.getElementById('totcustomer').value;
	if(document.getElementById('checkall').checked == true)
	{
		for(var i=0;i<=tot;i++)
		{
			var elemId = 'cust'+i;
			document.getElementById(elemId).checked = true;
		}
	}
	if(document.getElementById('checkall').checked == false)
	{
		for(var i=0;i<=tot;i++)
		{
			var elemId = 'cust'+i;
			document.getElementById(elemId).checked = false;
		}
	}
}
function updateExtensions()
{
	var val = document.getElementById('process').options[document.getElementById('process').selectedIndex].value;
	location.href = 'home.php?page=manualmain&pid='+val;
}
</script>
