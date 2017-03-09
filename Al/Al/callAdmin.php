<?php
include "check.php";
require("connection.php");
	
if($_REQUEST['page']=="manualAdmin"){
	$condition = "AND dialertype = 0";
	$dialType = 0;	
} 
if($_REQUEST['page']=="previewAdmin"){
	$condition = "AND dialertype = 1";
	$dialType = 1;
} 
if ($_REQUEST['page']=="predictiveAdmin"){
	if($_REQUEST['act'] == 'No')
		header("location:home.php?page=startdialing");
	$condition = "AND dialertype = 2";	
	$dialType = 2;
} 

if($_GET['action']=="change"){
	$sql_count = mysql_query("SELECT count(*) as count FROM current WHERE 1 AND processid = ".$_REQUEST['selProcess']." $condition");
	$row = mysql_fetch_array($sql_count);
	$count = $row['count'];
}




if($_POST['btnSubmit']=="Submit"){
	$processId = $_REQUEST['selProcess1'];
	$vale = mysql_query("DELETE FROM `current` WHERE processid=".$_REQUEST['selProcess']." AND dialertype = $dialType "); 
	$handle = fopen($_FILES['fileUpload']['tmp_name'], "r");
	while ($data = fgetcsv($handle, 1000, ",")) {
        	$num = count($data);
		$row++;
		$insertString = "";
		
		for ($c=0; $c < $num; $c++) 
		{
		        if($c == 0)
		        {
		        	if(trim($data[$c]) != '')
		        	{
		        		$insertString .= "firstname='".$data[$c]."'";
		        		$macId = $data[$c];
		        	}
		        }
		        if($c == 1)
		        {
		        	if(trim($data[$c]) != '')
		        	{
		        		$insertString .= ",lastname='".$data[$c]."'";
		        	}
		        }
		    	if($c == 2)
		        {
		        	if(trim($data[$c]) != '')
		        	{
		        		$insertString .= ",address='".$data[$c]."'";
		        	}
		       }
		       if($c == 3)
		       {
		       		if(trim($data[$c]) != '')
		       		{
		        		$insertString .= ",city='".$data[$c]."'";
		        	}
		        }
			if($c == 4)
			{
			       	if(trim($data[$c]) != '')
			       	{
			       		$insertString .= ",state='".$data[$c]."'";
			       	}
			}
			if($c == 5)
			{
				if(trim($data[$c]) != '')
			        {
			        	$insertString .= ",country='".$data[$c]."'";
			        }
			}
			if($c == 6)
			{
				if(trim($data[$c]) != '')
				{
					$insertString .= ",phone='".$data[$c]."'";
					$currentTableString = "phone='".$data[$c]."'";
				}
			}
			if($c == 7)
			{
			       	if(trim($data[$c]) != '')
			       	{
			       		$insertString .= ",email='".$data[$c]."'";
			       	}
			}
			if($c == 8)
			{
				if($dialType==0)
			       	{
			       		if(trim($data[$c]) != '')
			       		{
			       			$currentTableString .= ",dialedextension='".$data[$c]."'";
			       		}
			       	}
			}
		}
   	        if($currentTableString!="")
		{
			
			$inserCurrent = "insert into current set $currentTableString , dialertype = '$dialType' , processid = $processId ";
			$resultCurrent = mysql_query($inserCurrent);
		}
		if($insertString != "")
		{
		   	$insertEquipment = "insert into master set $insertString ";
		   	$resultEquipment = mysql_query($insertEquipment);
		}
	}
	fclose($handle);
	if($dialType=='2')
	{
		header("Location:home.php?page=startdialing");
	} 
	else 
	{
		header("Location:home.php?page=".$_REQUEST['page']);
	}

	
}
?>
<script languange="javascript">
	function showUpload(val){ 
		var page = document.getElementById('page').value;
		document.frmAdmin.method ='post';
		document.frmAdmin.action ='home.php?page='+page+'&action=change&act='+val;
		document.frmAdmin.submit();
	}

	function Validation(){
		if(document.getElementById('fileUpload').value==true){
			alert("Please select the file to upload.");			
			document.frmAdmin.fileUpload.focuse();
			return false;
		} 
		if(document.getElementById('selProcess').value=="-1"){
			alert("Please select process");			
			document.frmAdmin.selProcess.focuse();
			return false;
		} else {
			return true;
		}
		
	}
	
	function frmSubmit(){
		var page = document.getElementById('page').value;
		document.frmAdmin.method ='post';
		document.frmAdmin.action ='home.php?page='+page+'&action=change';
		document.frmAdmin.submit();
	}
	
</script>
<form name="frmAdmin" method="post" enctype="multipart/form-data">
	<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>" />
	<table style="" border=1>
		<tr>
			<td>Total Yet to be called: <?=$count?> &nbsp; &nbsp;
			
		<select name="selProcess" id="selProcess" onchange="frmSubmit();">
		<option value="-1">--Select Process--</option>
		<?php 
			$processResult = mysql_query("SELECT *FROM process ORDER BY processId");
			
			while($process = mysql_fetch_row($processResult))
			{  
				if($_REQUEST['selProcess'] == $process[0])
					echo "<option value='$process[0]' selected>$process[1]</option>";
				else 
					echo "<option value='$process[0]'>$process[1]</option>";
			} ?>
			
			</td>
		</tr>
		<tr>
			<td>Would you like to flush-out the table
				<input type="button" name="btnYes" value="Yes" onclick="javascript:showUpload('Yes');">
				<input type="button" name="btnNo" value="No" onclick="javascript:showUpload('No');">
			</td>
				
		</tr>
		<?php if($_REQUEST['act']=="Yes") { ?>
		<tr>
			<td><input type="file" name="fileUpload" id="fileUpload"></td>
		</tr>
		<tr>
			<td>
			   
			    
		<select name="selProcess1" id="selProcess">
		<option value="-1">--Select Process--</option>
		<?php 
			$processResult = mysql_query("SELECT *FROM process ORDER BY processId");
			
			while($process = mysql_fetch_row($processResult))
			{  
				if($_REQUEST['selProcess'] == $process[0])
					echo "<option value='$process[0]' selected>$process[1]</option>";
				else 
					echo "<option value='$process[0]'>$process[1]</option>";
			} ?>
			    <input type="submit" name="btnSubmit" value ="Submit" onclick="return Validation();">
			</td>
		</tr>
		<?php } ?>
	</table>
</form>


