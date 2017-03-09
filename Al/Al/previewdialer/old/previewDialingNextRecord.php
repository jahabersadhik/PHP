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
$extn = $_REQUEST['extn'];
$res = mysql_query("select *from previewDialing where assignAgent='' AND dialingStatus='New' LIMIT 0,1");
if(@mysql_num_rows($res)>0)
{
	$row = mysql_fetch_row($res);
	mysql_query("update previewDialing set assignAgent='$extn' where dialingId='$row[0]'");
	mysql_query("update users set popWindow='1' where extensionNo='$extension'");
	//echo "yes";
}
header("location:previewDialingAgentAccountInformation.php");
?>
