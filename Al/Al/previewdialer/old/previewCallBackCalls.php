<?php
/*****************************************************************************
 *	File Name   : customerUsersList.php                                      *
 *	Created by	: Madhusudhanan V P                                          *
 *	Version     : 1.0.0.0.0.0                                                *
 *	Connection  : mysql_connection.php                                       *
 *	Tables      : customer,users                                             *
 *	Links       : index.php,frame.php,customerAddUsers.php,                  *
 *                editCustomerUsersList.php,changeCustomerUsersPassword.php, *
 *                deleteCustomerUsersProcess.php                             *
 *	Created On  : 16 January 2009                                            *
 *	Last Updated:                                                            *
 *	Updated By  :                                                            *
 *****************************************************************************/
session_start();

include("mysql_connection.php");

$cust_type_message=$_SESSION['type'];
$cust_type_message1=$_SESSION['type1'];

$loginname=$_SESSION['hid']; 
$cust_cId=$_SESSION['u_Customer_Id'];
if(!isset($_SESSION['hid'])){
header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
}
mysql_query("update users set loggedInStatus='Preview' where userid='$cust_cId'");
$callbackcalls = mysql_query("select *from predictiveDialing where dialingComment='Call Back' AND calledExtension=ANY(select extensionNo from users where userid='$cust_cId')");

?>
<html>
<head>
<title>PERI Software Solutions iSystem Web Portal</title>
<link href="ButtonStyle.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="00" marginheight="0"  class=NormalFont onload="">
<?php include('frame.php');?>
			<div style=" height:auto; width:100%; overflow:auto;">
			<table width=100% class=normalfont cellpadding=0 border=0>			
		
			<tr align=right valign="top">
			<td colspan=7> 
				<INPUT type="button" value="Back" name="Back" onclick="location.href='agentAccountInformation.php'" class="NormalButton">
			</td>
			</tr>
			
			<tr  bgcolor="#ff9900" class="headings">
			<!--<td width="181"></td>-->
			<td width="100">First Name
			</td >
			<td width="100">Last Name
			</td >
			<td width="100">Phone
			</td >
			<td width="150">CallBack Date Time
			</td >
			<td width="100">Called Date Time
			</td >
			</tr>
<?php
if(mysql_num_rows($callbackcalls))
while($row = mysql_fetch_array($callbackcalls))
{
?>	

<tr bgcolor="#F2F5F8">

<td width="100" class="NormalFont">
	<?=$row['firstname'];?>
</td>
<td width="100" class="NormalFont">
	<?=$row['lastname'];?>
</td>
<td width="100" class="NormalFont">
	<?=$row['phone'];?>
</td>
<td width="150" class="NormalFont">
	<?=$row['callBackFromDateTime'];?>
</td>
<td width="150" class="NormalFont">
	<?=$row['calledDateTime'];?>
</td>
</tr>
<?php
}
?>
</table>
</div>
<?php include('footer1.php');?>
</body>
</html>
