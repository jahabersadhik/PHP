<?php
include("mysql_connection.php");

$loginname=$_SESSION['hid']; 
if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}
$use_id=$_GET['id'];			
$query = "DELETE FROM users WHERE userId in('$use_id')";  

$userQuery = mysql_query("SELECT fname,lname,userName FROM users WHERE userId='".$_GET['id']."'",$connection);
$result = mysql_fetch_row($userQuery);

$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Portal User/Edit' AND trackExtension = '$result[2]' ORDER BY trackId DESC LIMIT 0,1",$connection);
if(mysql_num_rows($oldvaluesquery)==0)
	$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'Portal User/Add' AND trackExtension = '$result[2]' ORDER BY trackId DESC LIMIT 0,1",$connection);
	
$oldvaluesresult =mysql_fetch_row($oldvaluesquery);

$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='Portal User/Edit',trackFunction='$oldvaluesresult[0]',trackExtension='$result[2]',trackInformation='User Deleted'";


$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());

$dresult=mysql_query($query,$connection)or die(mysql_error());
header("Location:./customerUsersList.php");
?>
