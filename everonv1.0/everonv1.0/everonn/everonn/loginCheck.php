<?php
include("mysql_connection.php");
$loginName=$_POST['txtLoginName'];
$password=md5($_POST['txtPassWord']);

$sql="SELECT * FROM users WHERE userName='$loginName' AND passWord='$password' AND status='Active';";
$result=mysql_query($sql,$connection) or die(mysql_error());
$count = mysql_num_rows($result); 

$row = mysql_fetch_array($result);print_r($row);
if($count>=1)
{
	$fullName=ucfirst($row['fname'])." ".ucfirst($row['lname']);
	$_SESSION['hid'] = $fullName; // store session data
	$_SESSION['u_Customer_Id']=$row['userId'];
	$_SESSION['c_Customer_Id']=$row['customerId'];
	$_SESSION['group']=$row['typeOfUser'];
	$_SESSION['username'] = $row['userName'];
	$ch = curl_init();
	/*if($_SESSION['Fserver'] == '61.246.55.96')
		$connectUrl = "http://".$_SESSION['Fserver'].":8088/asterisk/rawman?action=login&username=$f_user_name&secret=$f_user_password";
	else*/
	$connectUrl = "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword";
	curl_setopt($ch, CURLOPT_URL, "$connectUrl");
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expResponse = explode("chunked",$expOutput[3]);
	$finalResult['response']=$expResponse[1];
	if($finalResult['response'] == "")
	{
		$mess="Check for server status123";
		$_SESSION['msg'] = "$mess"; // store session data
		header("location:./index.php");
        		exit(1);
	}
	else
	{
		if(stristr($finalResult['response'],"Success") == "")
		{
			echo $mess="Authentication Failed For Configurations";
			$_SESSION['msg'] = "$mess"; // store session data
			header("location:./index.php");
            exit(1);
		}
		else
		{
			$userQuery="update users set loggedInStatus='loggedIn' where userId='".$_SESSION['u_Customer_Id']."' AND sessionId='$sesId'";
			$resultUsers=mysql_query($userQuery,$connection) or die(mysql_error());
			$userLogQuery = "insert into userLogStatus set userId='".$_SESSION['u_Customer_Id']."', loggedInTime= '".date("Y-m-d H:i:s")."',userSession='".session_id()."'";
			$resultUserLog=mysql_query($userLogQuery,$connection) or die(mysql_error());
			$moduleIdQuery = mysql_query("SELECT modules,typeOfUser FROM users WHERE userId='".$_SESSION['u_Customer_Id']."'",$connection);
			$moduleIdResult = mysql_fetch_row($moduleIdQuery);
			header("Location: headings.php");
			exit;
		}
	}
}
else
{
	$mess="Invalid UserName or Password/User Inactive";
	$_SESSION['msg'] = "$mess"; // store session data
	header("location:./index.php");
    exit(1);
}
?>
