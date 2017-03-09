<?php
/*****************************************************************************
 *	File Name   : editCustomerUsersListProcess.php                           *
 *	Created by	: Madhusudhanan V P                                          *
 *	Version     : 1.0.0.0.0.0                                                *
 *	Connection  : mysql_connection.php                                       *
 *	Tables      : users                                                      *
 *	Links       : index.php,frame1.php,customerUsersList.php                 *
 *	Created On  : 16 January 2009                                            *
 *	Last Updated:                                                            *
 *	Updated By  :                                                            *
 *****************************************************************************/
session_start();

include("mysql_connection.php");

$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];

if(!isset($_SESSION['hid'])){
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

$userQuery = mysql_query("SELECT fname,lname,userName FROM users WHERE userId='".$_GET['id']."'",$connection);
$result = mysql_fetch_row($userQuery);

$updateQuery = mysql_query("UPDATE users SET fname='".$_REQUEST['txtFname']."',lname ='".$_REQUEST['txtLname']."', userName = '".$_REQUEST['txtUname']."', status ='".$_REQUEST['Status']."',customerId='".$_REQUEST['seluser']."',typeOfUser='".$_REQUEST['typeOfUser']."',modules='$moduleid' WHERE userId = ".$_GET['id']."",$connection);

$providedModificationValues = "FirstName : ".$_REQUEST['txtFname']."<br>LastName: ".$_REQUEST['txtLname']."<br>Username: ".$_REQUEST['txtUname']."<br>Status: ".$_REQUEST['Status'];

$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Portal User/Edit' AND trackExtension = '$result[2]' ORDER BY trackId DESC LIMIT 0,1",$connection);
if(mysql_num_rows($oldvaluesquery)==0)
	$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Portal User/Add' AND trackExtension = '$result[2]' ORDER BY trackId DESC LIMIT 0,1",$connection);
	
$oldvaluesresult =mysql_fetch_row($oldvaluesquery);

$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Portal User/Edit',trackFunction='$oldvaluesresult[0]',trackExtension='$result[2]',trackInformation='".$providedModificationValues."'";


$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());

header("Location:./customerUsersList.php");

?>
