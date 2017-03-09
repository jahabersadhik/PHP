<?php
session_start();
require_once("mysql_connection.php");

$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];
$custid = $_REQUEST['seluser'];

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}
	if($_REQUEST['typeOfUser']=="Administrator")
	{
		$moduleid = '2,';
	}
	else if($_REQUEST['typeOfUser']=="Assetadmin")
	{
		$moduleid = '16,';
	}
	else if($_REQUEST['typeOfUser']=="Customer")
	{
		$moduleid = '9,';
	}
	else if($_REQUEST['typeOfUser']=="Techsupport")
	{
		$moduleid = '9';
	}	
	else
	{
		$moduleid = "";
	}
	$selectAuth = mysql_query("SELECT users FROM authentication WHERE customerid = $cust_uId");
	$rowAuth = mysql_fetch_row($selectAuth);
	
	$countUser = mysql_fetch_row(mysql_query("SELECT count(*) FROM users WHERE customerId = $cust_uId"));
	//if($rowAuth[0]>=$countUser[0])
	//{
	
		$insertQuery = mysql_query("INSERT INTO users (customerId,userName,passWord,ip,fname,lname,status,typeOfUser,modules) VALUES ($custid,'".$_REQUEST['txtUname']."','".md5($_REQUEST['txtpassword'])."','".$C_IP."','".$_REQUEST['txtFname']."','".$_REQUEST['txtLname']."','".$_REQUEST['Status']."','".$_REQUEST['typeOfUser']."','$moduleid')",$connection);
		
		$providedModificationValues = "FirstName : ".$_REQUEST['txtFname']."<br>LastName: ".$_REQUEST['txtLname']."<br>Username: ".$_REQUEST['txtUname']."<br>Status: ".$_REQUEST['Status'];
		
		

	$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Portal User/Add',trackFunction='$oldvaluesresult[0]',trackExtension='".$_REQUEST['txtUname']."',trackInformation='".$providedModificationValues."'";	

		$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());

		header("Location:./customerUsersList.php?type=sucess");		
	//}
	//else 
	//{
		
		//header("Location:./customerUsersList.php?type=error");		
	//}



?>
