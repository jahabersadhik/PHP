<?php 
session_start();
include("mysql_connection.php");

$message=$_SESSION['misg'];
$message1=$_SESSION['miss'];
$cust_ip_message=$_SESSION['ipAddress'];
$cust_ip_message1=$_SESSION['ipAddress1'];



$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];
//echo $loginname."/".$cust_cId."/".$cust_uId."/".$Gtype."/".$C_IP;

if(!isset($_SESSION['hid'])){
header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
}
echo "<pre>";
print_r($_REQUEST['chkpriv']);


$arr = explode(",",$_REQUEST['moduleid']);
$moduleid = split(",",$_REQUEST['moduleid']);

$providedModificationValues = "";
for($i=0;$i<count($arr)-1;$i++)
{	
	$name = 'chk'.$arr[$i];
	if($_REQUEST[$name.'1'] == 'on')
		$val = "1";
	else
		$val = "0";
	if($_REQUEST[$name.'2'] == 'on')
		$val1 = "1";
	else
		$val1 = "0";
	if($_REQUEST[$name.'3'] == 'on')
		$val2 = "1";
	else
		$val2 = "0";
	if($_REQUEST[$name.'4'] == 'on')
		$val3 = "1";
	else
		$val3 = "0";

	$module .= ",".$arr[$i]."~".$val.$val1.$val2.$val3;
	
	if($arr[$i] == 1)
	{
		$providedModificationValues1 = "Extensions : ";
		if($val==1)
			$providedModificationValues1 .= "Add,";	
		if($val1==1)
			$providedModificationValues1 .= "Edit,";
		if($val2==1)
			$providedModificationValues1 .= "Delete";
		if($providedModificationValues1 == "Extensions : ")
			$providedModificationValues.="";
		else
			$providedModificationValues.=$providedModificationValues1."<br>";
	}
	if($arr[$i] == 2)
	{
		$providedModificationValues2 = "Portal Users : ";
		if($val==1)
			$providedModificationValues2 .= "Add,";	
		if($val1==1)
			$providedModificationValues2 .= "Edit,";
		if($val2==1)
			$providedModificationValues2 .= "Delete";
		if($providedModificationValues2 == "Portal Users : ")
			$providedModificationValues.="";
		else
			$providedModificationValues.=$providedModificationValues2."<br>";
	}
	if($arr[$i]==3)
	{
		$providedModificationValues3 = "User Privileges : ";
		if($val==1)
			$providedModificationValues3 .= "Add,";	
		if($val1==1)
			$providedModificationValues3 .= "Edit,";
		if($val2==1)
			$providedModificationValues3 .= "Delete";
		if($providedModificationValues3 == "User Privileges : ")
			$providedModificationValues.="";
		else
			$providedModificationValues.=$providedModificationValues3."<br>";
	}
	if($arr[$i]==11)
	{
		$providedModificationValues4 = "Conference : ";
		if($val==1)
			$providedModificationValues4 .= "Add,";	
		if($val1==1)
			$providedModificationValues4 .= "Edit,";
		if($val2==1)
			$providedModificationValues4 .= "Delete";
		if($providedModificationValues4 == "Conference : ")
			$providedModificationValues.="";
		else
			$providedModificationValues.=$providedModificationValues4."<br>";
	}
}
$typeofuser = split("-",$_REQUEST['seluser']);
$userQuery = mysql_query("select userName from users where userId='".$_POST['seluser']."'",$connection);
$resultQuery = mysql_fetch_row($userQuery);

$module = mysql_query("UPDATE users SET modules = '".$module."' WHERE userId='".$_POST['seluser']."'",$connection);

$resultQuery = mysql_query("select userName from users where userId = '".$_POST['seluser']."'",$connection);
$result = mysql_fetch_row($resultQuery);

$oldvaluesquery = mysql_query("select trackInformation from userAccessTracking WHERE name = 'User Privileges/Edit' AND trackExtension = '".$result[0]."' ORDER BY trackId DESC LIMIT 0,1",$connection);
$oldvaluesresult =mysql_fetch_row($oldvaluesquery);

$insertUserTracking = "insert into userAccessTracking set userName='".$_SESSION['username']."',trackDate='".date("Y-m-d H:i:s")."',name='User Privileges/Edit',trackFunction='$oldvaluesresult[0]',trackExtension='".$result[0]."',trackInformation='".$providedModificationValues."'";

$resultUserTracking=mysql_query($insertUserTracking,$connection) or die(mysql_error());
$_SESSION['mes'] = "SuccessfullyUpdated";
header("Location:userPrivileges.php");
?>
