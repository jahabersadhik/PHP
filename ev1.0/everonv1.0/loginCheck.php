<?php
session_start();
//include("mysql_connection.php");
$loginName=addslashes($_POST['txtLoginName']);
$password=addslashes(md5($_POST['txtPassWord']));


$host="localhost";
$username="root";
$userpassword="PeRi";
$callLog_db_name="cdr";
$db_name="everon";
$asteriskUsername = 'admin';
$asteriskPassword = 'peri123';
$IPAddress = '192.168.2.179';
$connection = mysql_connect($host,$username,$userpassword) or die('error');
$connection_cdr = mysql_connect($host,$username,$userpassword,true) or die('error');
mysql_select_db($db_name,$connection);
mysql_select_db($callLog_db_name,$connection_cdr);


$sql="SELECT * FROM users WHERE userName='$loginName' AND passWord='$password' AND status='Active' LIMIT 0,1;";
$result=mysql_query($sql,$connection) or die(mysql_error());
$count = mysql_num_rows($result); 

$row = mysql_fetch_array($result);
if($count==1)
{
	$fullName=ucfirst($row['fname'])." ".ucfirst($row['lname']);
	$_SESSION['hid'] = $fullName; 
	$_SESSION['u_Customer_Id']=$row['userId'];
	$_SESSION['c_Customer_Id']=$row['customerId'];
	$_SESSION['group']=$row['typeOfUser'];
	$_SESSION['username'] = $row['userName'];
	$ch = curl_init();
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
		$mess="Check for server status";
		$_SESSION['msg'] = "$mess"; 
		header("location:./index.php");
        	//exit(1);
	}
	else
	{
		if(stristr($finalResult['response'],"Success") == "")
		{
			echo $mess="Authentication Failed For Configurations";
			$_SESSION['msg'] = "$mess"; // store session data
			header("location:./index.php");
		        //exit(1);
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
			//exit(1);
		}
	}
}
else
{
	$mess="Invalid UserName or Password/User Inactive";
	$_SESSION['msg'] = "$mess"; 
	header("location:./index.php");
    	//exit(1);
}
?>
