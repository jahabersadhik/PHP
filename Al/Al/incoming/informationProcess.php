<?php
session_start();
include("../connection.php");
$phone = $_REQUEST['phone'];
$name = $_REQUEST['name'];
$address1 = $_REQUEST['add1'];
$address2 = $_REQUEST['add2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$calldate = date('Y-m-d G:i:s');
$email = $_REQUEST['email'];
$agentid = $_SESSION['userName'];
$query = $_REQUEST['query'];
$extensionResult = mysql_query("SELECT extension FROM loginLog WHERE empId='".$_SESSION['userName']."' AND logoutDate='0000-00-00'");
$extensionArr = mysql_fetch_row($extensionResult);
$extension = $extensionArr[0];
$comments = $_REQUEST['comments']; 
$dialResult = mysql_query("INSERT INTO incomingCalls VALUES(NULL,'$phone','$name','$address1','$address2','$city','$state','$email','$calldate','$query','$comments','$agentid','$extension')");
if($_REQUEST['selAfterSubmitting'] == 'Break')
	mysql_query("UPDATE loginLog SET status='2' WHERE extension='$extension' AND empId='".$_SESSION['userName']."' AND logoutDate='0000-00-00'");
if($_REQUEST['selAfterSubmitting'] == 'loggedIn')
	mysql_query("UPDATE loginLog SET status='1' WHERE extension='$extension' AND empId='".$_SESSION['userName']."' AND logoutDate='0000-00-00'");
if($_REQUEST['selAfterSubmitting'] == 'loggedOut')
	mysql_query("UPDATE loginLog SET status='0',logoutDate='".date('Y-m-d')."',logoutTime='".date('G:i:s')."' WHERE extension='$extension' AND empId='".$_SESSION['userName']."' AND logoutDate='0000-00-00'");
if($dialResult)
{ 
?>
<script>
	opener.location.reload(true);
	self.close();
</script>
<?php 
} 
?>


