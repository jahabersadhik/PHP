<?php
include "check.php";
require("connection.php");
if(isset($_REQUEST['submit']))
{
	if($_REQUEST['formtype']!='single' && $_REQUEST['emp']!=1)
	{
		$handle = fopen($_FILES['agentData']['tmp_name'], "r");
		while (($data = fgetcsv($handle, 50000, ",")) !== FALSE) 
		{
		    $num = count($data);
		    $row++;
		    $insertString = "";
		    for ($c=0; $c < $num; $c++) 
		    {
		        if($c == 0)
		        {
		        	if(trim($data[$c]) != 'empid')
		        	{
		        		$insertString .= "empId='".$data[$c]."'";
		        	}
		        }
		        if($c == 1)
		        {
		        	if(trim($data[$c]) != 'secret')
		        	{
		        		$insertString .= ",secret='".$data[$c]."'";
		        	}
		        }
		    	if($c == 2)
		        {
		        	if(trim($data[$c]) != 'firstName')
		        	{
		        		$insertString .= ",firstName='".$data[$c]."'";
		        	}
		        }
		        if($c == 3)
		        {
		        	if(trim($data[$c]) != 'lastName')
		        	{
		        		$insertString .= ",lastName='".$data[$c]."'";
		        	}
		        }
		        if($c == 4)
		        {
		        	if(trim($data[$c]) != 'doj')
		        	{
		        		$insertString .= ",DOJ='".$data[$c]."'";
		        	}
		        }
		        if($c == 5)
		        {
		        	if(trim($data[$c]) != 'email')
		        	{
		        		$insertString .= ",email='".$data[$c]."'";
		        	}
		        }
		        if($c == 6)
		        {
		        	if(trim($data[$c]) != 'phone')
		        	{
		        		$insertString .= ",phone='".$data[$c]."'";
		        	}
		        }
		        if($c == 7)
		        {
		        	if(trim($data[$c]) != 'designation')
		        	{
		        		$insertString .= ",designation='".str_replace(" ","~",strtoupper($data[$c]))."'";
		        		$desig = str_replace(" ","~",strtoupper($data[$c]));
		        	}
		        }
		    }
		    if($insertString != "")
		    {
		    	$insertAgents = "insert into userData set $insertString ";
		    	$resultAgents = mysql_query($insertAgents)or die(mysql_error());
		    	mysql_query("INSERT INTO hierarchy VALUES(NULL,'$desig','','')");
		    }
		}
		fclose($handle);
	}
	if($_REQUEST['formtype'] == 'single' && $_REQUEST['emp']!=1)
	{
		$empId = $_REQUEST['empId'];
		$fname = $_REQUEST['fName'];
		$lname = $_REQUEST['lName'];
		$doj = $_REQUEST['doj'];
		$secret = $_REQUEST['secret'];
		$desig = strtoupper(str_replace(" ","~",$_REQUEST['designation']));
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		mysql_query("INSERT INTO userData VALUES('$empId','$secret','$fname','$lname','$doj','$email','$phone','$desig','')");
		mysql_query("INSERT INTO hierarchy VALUES(NULL,'$desig','','')");
		/*header("location:home.php?page=teams");*/
	}
	if($_REQUEST['formtype'] != 'single' && $_REQUEST['emp']==1)
	{
		$empId = $_REQUEST['empId'];
		$fname = $_REQUEST['fName'];
		$lname = $_REQUEST['lName'];
		$doj = $_REQUEST['doj'];
		$secret = $_REQUEST['secret'];
		$desig = strtoupper(str_replace(" ","~",$_REQUEST['designation']));
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		mysql_query("UPDATE userData SET secret='$secret',firstname='$fname',lastname='$lname',doj='$doj',email='$email',phone='$phone',designation='$desig' WHERE empId='$empId'");
		mysql_query("INSERT INTO hierarchy VALUES(NULL,'$desig','','')");
		/*header("location:home.php?page=teams");*/
	}
	//Updating agents.conf File
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
	$testurl = "https://$IPAddress/asterisk/rawman?action=updateconfig&srcfilename=agents.conf&dstfilename=agents.conf&Action-000000=delcat&Cat-000000=agents&Var-000000=&Value-000000=&Action-000001=newcat&Cat-000001=agents&Var-000001=&Value-000001=";
	$res = mysql_query("SELECT empId,secret,firstname FROM userData WHERE designation='AGENT'");
	
	for($j=0,$i=2;$j<mysql_num_rows($res);$i++,$j++)
	{
		$row = mysql_fetch_row($res);
		if(strlen($i)==1)
			$zeros = "00000";
		if(strlen($i)==2)
			$zeros = "0000";
		if(strlen($i)>2)
			$zeros = "000";
		$testurl .=  "&Action-$zeros$i=append&Cat-$zeros$i=agents&Var-$zeros$i=agent&Value-$zeros$i=$row[0],$row[1],$row[2]";
	}
	//echo $testurl;
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
	//End of Updating agents.conf File
	header("location:home.php?page=teams");
}
?>
<script language="javascript">
	function Validate()
	{
		if(document.getElementById('empId').value == '' || isNaN(document.getElementById('empId').value) || (document.getElementById('empId').value).length!=4)
		{
			alert ("Emp Id is a Numeric Value & length should be 4");
			document.getElementById('empId').focus();
			return false;
		}
		if(document.getElementById('secret').value == '' || isNaN(document.getElementById('secret').value) || (document.getElementById('empId').value).length!=4)
		{
			alert ("Secret is a Numeric Value & length should be 4");
			document.getElementById('secret').focus();
			return false;
		}
		if(document.getElementById('fName').value == '')
		{
			alert ("Please enter the FirstName");
			document.getElementById('fName').focus();
			return false;
		}
		if(document.getElementById('lName').value == '')
		{
			alert ("Please enter the LastName");
			document.getElementById('lName').focus();
			return false;
		}
		if(document.getElementById('doj').value == '')
		{
			alert ("Please enter the Joining date");
			document.getElementById('doj').focus();
			return false;
		}
		if(document.getElementById('email').value == '')
		{
			alert ("Please enter the emailID");
			document.getElementById('email').focus();
			return false;
		}
		if(document.getElementById('phone').value == '')
		{
			alert ("Please enter the Phone Number");
			document.getElementById('phone').focus();
			return false;
		}
		if(document.getElementById('designation').value == '' || !isNaN(document.getElementById('designation').value)) 
		{
			alert ("Please enter the Designation");
			document.getElementById('designation').focus();
			return false;
		}
		return true;
	}
	function Validate1()
	{
		if(document.getElementById('secret').value == '' || isNaN(document.getElementById('secret').value))
		{
			alert ("Secret is a Numeric Value");
			document.getElementById('secret').focus();
			return false;
		}
		if(document.getElementById('fName').value == '')
		{
			alert ("Please enter the FirstName");
			document.getElementById('fName').focus();
			return false;
		}
		if(document.getElementById('lName').value == '')
		{
			alert ("Please enter the LastName");
			document.getElementById('lName').focus();
			return false;
		}
		if(document.getElementById('doj').value == '')
		{
			alert ("Please enter the Joining date");
			document.getElementById('doj').focus();
			return false;
		}
		if(document.getElementById('email').value == '')
		{
			alert ("Please enter the emailID");
			document.getElementById('email').focus();
			return false;
		}
		if(document.getElementById('phone').value == '')
		{
			alert ("Please enter the Phone Number");
			document.getElementById('phone').focus();
			return false;
		}
		if(document.getElementById('designation').value == '')
		{
			alert ("Please enter the Designation");
			document.getElementById('designation').focus();
			return false;
		}
		return true;
	}
</script>


	<form method="post" action="home.php?page=teams" enctype="multipart/form-data">
	<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="30%">Import Employees Data</td>
		<td width="70%"><input type="file" name="agentData">&nbsp;&nbsp;<input type="submit" name="submit" value="Upload"></td>
	</tr>
	<tr>
		<td colspan="2" height="25">Upload CSV in <u><i>empid, secret, firstname, lastname, doj, email, phone, designation</i></u> format</td>
	</tr>
	</table>
	</form>
	<?php
		if($_REQUEST['id'] == '')
		{
			$empId = $_REQUEST['id'];
			$employeeResult = mysql_query("SELECT *FROM userData WHERE empId = '$empId'");
			$employeeData = mysql_fetch_row($employeeResult);
	?>
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td><input type="button" value="Add.New Employee" onclick="document.getElementById('addemployee').style.display='block';" /></td>
	</tr>
	</table>
	<form method="post" action="home.php?page=teams" onsubmit="return Validate();" name="frm1">
	<input type="hidden" name="formtype" value="single" />
	<div style="width:96%;height:120px;display:block;border:1px solid #000;padding:10px;" id="addemployee">
	<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="15%" height="25">EmpID</td>
		<td width="25%"><input type="text" name="empId" id="empId" /></td>
		<td width="5%"></td>
		<td width="15%">Secret</td>
		<td width="25%"><input type="text" name="secret" id="secret" /></td>
	</tr>
	<tr>
		<td width="15%" height="25">First Name</td>
		<td width="25%"><input type="text" name="fName" id="fName" /></td>
		<td width="5%"></td>
		<td width="15%">Last Name</td>
		<td width="25%"><input type="text" name="lName" id="lName" /></td>
	</tr>
	<tr>
		<td width="15%" height="25">DOJ</td>
		<td width="25%"><input type="text" name="doj" id="doj" /></td>
		<td width="5%"></td>
		<td width="15%">EMail ID</td>
		<td width="25%"><input type="text" name="email" id="email" /></td>
	</tr>
	<tr>
		<td width="15%" height="25">Phone</td>
		<td width="25%"><input type="text" name="phone" id="phone" /></td>
		<td width="5%"></td>
		<td width="15%">Designation</td>
		<td width="25%"><input type="text" name="designation" id="designation" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Save" name="submit" /></td>
		<td colspan="2" align="center"><input type="button" value="Cancel" onclick="document.getElementById('addemployee').style.display = 'none';" /></td>
	</tr>
	</table>
	</div>
	</form>
	<?php
		}
	?>
	<?php
		if($_REQUEST['id'] != '')
		{
			$empId = $_REQUEST['id'];
			$employeeResult = mysql_query("SELECT *FROM userData WHERE empId = '$empId'");
			$employeeData = mysql_fetch_row($employeeResult);
	?>
	<form method="post" action="home.php?page=teams" onsubmit="return Validate1();" name="frm2">
	<input type="hidden" name="emp" value="1" />
	<div style="width:96%;height:120px;display:block;border:1px solid #000;padding:10px;" id="addemployee">
	<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="15%" height="25">EmpID</td>
		<td width="25%"><input type="text" name="empId" readonly id="empId" value="<?php echo $employeeData[0]; ?>" /></td>
		<td width="5%"></td>
		<td width="15%">Secret</td>
		<td width="25%"><input type="text" name="secret" id="secret" value="<?php echo $employeeData[1]; ?>" /></td>
	</tr>
	<tr>
		<td width="15%" height="25">First Name</td>
		<td width="25%"><input type="text" name="fName" id="fName" value="<?php echo $employeeData[2]; ?>" /></td>
		<td width="5%"></td>
		<td width="15%">Last Name</td>
		<td width="25%"><input type="text" name="lName" id="lName" value="<?php echo $employeeData[3]; ?>" /></td>
	</tr>
	<tr>
		<td width="15%" height="25">DOJ</td>
		<td width="25%"><input type="text" name="doj" id="doj" value="<?php echo $employeeData[4]; ?>" /></td>
		<td width="5%"></td>
		<td width="15%">EMail ID</td>
		<td width="25%"><input type="text" name="email" id="email" value="<?php echo $employeeData[5]; ?>" /></td>
	</tr>
	<tr>
		<td width="15%" height="25">Phone</td>
		<td width="25%"><input type="text" name="phone" id="phone" value="<?php echo $employeeData[6]; ?>" /></td>
		<td width="5%"></td>
		<td width="15%">Designation</td>
		<td width="25%"><input type="text" name="designation" id="designation" value="<?php echo str_replace("~"," ",$employeeData[7]); ?>" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Save" name="submit" /></td>
		<td colspan="2" align="center"><input type="button" value="Cancel" onclick="location.href='home.php?page=teams';" /></td>
	</tr>
	</table>
	</div>
	</form>
	<?php
		}
	?>
	<table width="100%" cellpadding="2" cellspacing="2" align="center">
	<tr style="background:#f6f6f6;">
		<th width="5%" align="left">EmpID</th>
		<th width="15%" align="left">FirstName</th>
		<th width="15%" align="left">LastName</th>
		<th width="25%" align="left">EMail</th>
		<th width="10%" align="left">Phone</th>
		<th width="15%" align="left">Designation</th>
		<th width="10%" align="left">Modify</th>
	</tr>
	<?php
	$employeesResult = mysql_query("SELECT *FROM userData ORDER BY doj");
	while($employeesData = mysql_fetch_row($employeesResult))
	{
		echo "
				<tr style='background:#f6f6f6;'>
					<td>$employeesData[0]</td>
					<td>$employeesData[2]</td>
					<td>$employeesData[3]</td>
					<td>$employeesData[5]</td>
					<td>$employeesData[6]</td>
					<td>".str_replace('~','',$employeesData[7])."</td>
					<td><a href='home.php?page=teams&id=$employeesData[0]'>Edit</a></td>
				</tr>
			 ";
	}
	?>
	</table>
