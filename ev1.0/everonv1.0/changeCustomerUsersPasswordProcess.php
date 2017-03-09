<?php
include("mysql_connection.php");

$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];

if(!isset($_SESSION['hid']))
{
header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
}

$passwd=$_GET['id'];
$npass = $_POST['txtnew'];
$nconfpass = $_POST['txtconfirm'];
$status=$_POST['Status'];			

$sql_pass = "UPDATE users SET passWord='".md5($nconfpass)."',status='$status' WHERE userId='$passwd';"; 
$result_pass = mysql_query($sql_pass,$connection)or die(mysql_error());

$userQuery = mysql_query("SELECT fname,lname,userName FROM users WHERE userId='$passwd'",$connection);
$result = mysql_fetch_row($userQuery);
$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Portal User/Change Password' AND trackExtension = '$result[2]' ORDER BY trackId DESC LIMIT 0,1",$connection);
$oldvaluesresult =mysql_fetch_row($oldvaluesquery);
$providedModificationValues = "Password: ".$nconfpass."<br>Status: ".$status;
$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Portal User/Change Password',trackFunction='$oldvaluesresult[0]',trackExtension='".$result[2]."',trackInformation='".$providedModificationValues."'";
$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());
			//header("Location:./customerUsersList.php");		
?>
<script language="javascript">
alert("Password changed successfully");
location.href="customerUsersList.php";
</script>
