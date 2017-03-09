<?php
include("mysql_connection.php");

$loginname=$_SESSION['hid']; 
if(!isset($_SESSION['hid']))
{
	header("Location: index.php");
	die();
}

$npass = $_POST['txtnew'];
$nconfpass = md5($_POST['txtconfirm']);

$sql_pass = "UPDATE users SET passWord='$nconfpass' WHERE userId='$cust_cId';"; 
$result_pass = mysql_query($sql_pass,$connection)or die(mysql_error());

$userQuery = mysql_query("SELECT fname,lname,userName FROM users WHERE userId='$cust_cId'",$connection);
$result = mysql_fetch_row($userQuery);
$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Portal User/Change Own Password' AND trackExtension = '$result[2]' ORDER BY trackId DESC LIMIT 0,1",$connection);
$oldvaluesresult =mysql_fetch_row($oldvaluesquery);
$providedModificationValues = "Password: $npass";
$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Portal User/Change Own Password',trackFunction='$oldvaluesresult[0]',trackExtension='".$result[2]."',trackInformation='".$providedModificationValues."'";
$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());
?>
<script language="javascript">
alert("Password changed successfully");
location.href="headings.php";
</script>
