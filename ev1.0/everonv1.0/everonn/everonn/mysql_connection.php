<?php
session_start();
/*exec("isystem",$output);
if($output[0]==0)
{
	header("location:../index.php");
}*/
if(!isset($_SESSION['hid']))
{
	header("location:./index.php");
}
$host="localhost";
$username="root";
$userpassword="PeRi";
$callLog_db_name="cdr";
$db_name="everon";

$asteriskUsername = 'admin';
$asteriskPassword = 'peri123';
$IPAddress = '10.10.1.25';


$connection = mysql_connect($host,$username,$userpassword) or die('error');
$connection_cdr = mysql_connect($host,$username,$userpassword,true) or die('error');
mysql_select_db($db_name,$connection);
mysql_select_db($callLog_db_name,$connection_cdr);

if(isset($_SESSION['hid']))
{
	$loginname=$_SESSION['hid']; 
	$name=$_SESSION['hidd'];
	$cust_cId=$_SESSION['u_Customer_Id'];
	$cust_uId=$_SESSION['c_Customer_Id'];
	$Gtype=$_SESSION['group'];
}
?>
