<?php
session_start();

include("mysql_connection.php");
$cust_type_message=$_SESSION['type'];
$cust_type_message1=$_SESSION['type1'];

$loginname=$_SESSION['hid']; 
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];
$extension = $_SESSION['extensionNo'];
$FSERVER=$_SESSION['Fserver'];
$Fusername=$_SESSION['Fusername'];
$Fpassword=$_SESSION['Fpassword'];
$custApp_name_message=$_SESSION['Name'];

if(!isset($_SESSION['hid'])){
header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
}
$today = date('Y-m-d G:i:s');
//$agentData = mysql_query("select popWindow from users where extensionNo='$extension'");
//$agent = mysql_fetch_row($agentData);
$crmData = mysql_query("select *from previewDialing where assignAgent='$extension' AND dialingStatus!='Called'");
$callbackData = mysql_query("select *from previewDialing where dialingStatus='Called' AND dialingComment='Call Back' AND assignAgent='$extension' AND UNIX_TIMESTAMP(callBackFromDateTime) >= UNIX_TIMESTAMP('$today')");
?>
<html>
<head>
<title>PERI Software Solutions iSystem Web Portal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ButtonStyle.css" rel="stylesheet" type="text/css">

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="00" marginheight="0"  class=NormalFont onload="">
<form method=post name="agentAccountInformation" >
<?php include('frame.php'); ?>
<div style=" height:430px; width:995px; overflow:auto;">
<table width=980 class=normalfont cellpadding=0 border=0 align=center>			
<tr align=right valign="top">
	<td colspan=7> 
		<INPUT type="button" value="Back" name="Back" onclick="location.href='headings.php'" class="NormalButton">
		<?php
		if(@mysql_num_rows($crmData)<=0)
		{
		?>
		<INPUT type="button" value="Next Record" name="Next Record" onclick="location.href='previewDialingNextRecord.php?extn=<?php echo $extension; ?>'" class="NormalButton">
		<?php
		}
		?>
	</td>
</tr>
<?php	
//	if(!mysql_num_rows($crmData))
//	{
?>
<meta http-equiv="refresh" content="10;URL=previewDialingAgentAccountInformation.php">
<?php
/*	}
	else 
	{
		$crm = mysql_fetch_array($crmData);
		if($agent[0] == 1)
		{
			mysql_query("update users set popWindow=0 where extensionNo='$extension'");*/
?>
	<!--<script language="javascript">
		window.open("agentPreviewCallAccountInformation.php?id=<?php echo $crm['dialingId'];?>","","scrollbars = yes,resizable=yes,width=1000,height=800");
	</script>-->
<?php
//		}
//	}

?>
<tr align=center valign="top">
	<td align="center" class="NormalFont">
		<table width="980" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td style="background:#669900;color:#fff;" width="30%">Current Call Details</td>
			<td colspan=3></td>
		</tr>
		<tr  bgcolor="#FF9900" class="headings">
			<th align='left' width="30%">Customer Name</th>
			<th align='left' width="30%">Phone No</th>
			<th align='left' width="30%">Address</th>
			<th align='left' width="10%">View</th>
		</tr>
		<?php
				if(@mysql_num_rows($crmData)>0)
				while($crm = mysql_fetch_array($crmData))
				{
				echo "<tr bgcolor='#f6f6f6'>
						<td align='left' class='NormalFont'>".$crm['firstname']." ".$crm['lastname']."</td>
						<td align='left' class='NormalFont'>".$crm['phone']."</td>
						<td align='left' class='NormalFont'>".$crm['city']." ".$crm['state']."</td>
						<td align='left'><a href='javascript:void(0)' onclick=\"window.open('agentPreviewCallAccountInformation.php?id=".$crm['dialingId']."','','scrollbars = yes,resizable=yes,width=1000,height=800');\" class=NormalFont>View&Call</a></td>
					  </tr>	
					 ";
				}
				else
				{
					echo "<tr><td colspan=4 class=NormalFont>No Current CallsFound.Please Click on Next Record to get another Customer details</td></tr>";
				}
		
		?>
		<tr>
			<td colspan="4" height="20"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td align=center class=NormalFont>
		<table width="980" cellpadding="2" cellspacing="2" align="center">
			
		<tr>
			<td style="background:#669900;color:#fff;" width="30%">CallBack Calls Details</td>
			<td colspan=3></td>
		</tr>
		<tr  bgcolor="#ff9900" class="headings">
			<th align='left' width="30%">Customer Name</th>
			<th align='left' width="30%">Phone No</th>
			<th align='left' width="30%">Address</th>
			<th align='left' width="10%">View</th>
		</tr>
		<?php
			if(@mysql_num_rows($callbackData)>0)
			while($callback = @mysql_fetch_array($callbackData))
			{				
				echo "<tr bgcolor='#f6f6f6'>
						<td align='left' class='NormalFont'>".$callback['firstname']." ".$callback['lastname']."</td>
						<td align='left' class='NormalFont'>".$callback['phone']."</td>
						<td align='left' class='NormalFont'>".$callback['city']." ".$callback['state']."</td>
						<td align='left'><a href='javascript:void(0)' onclick=\"window.open('agentPreviewCallAccountInformation.php?id=".$callback['dialingId']."','','scrollbars = yes,resizable=yes,width=1000,height=800');\" class=NormalFont>View&Call</a></td>
					  </tr>	
					 ";
			}
			else
			{
				echo "<tr><td colspan=4 class=NormalFont>No CallBack Calls.</td></tr>";
			}
		?>
		<tr>
			<td colspan="4" height="20"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>
<?php include('footer1.php');?>
</body>
</html>
