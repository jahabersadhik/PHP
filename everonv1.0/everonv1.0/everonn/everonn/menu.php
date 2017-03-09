<?php 
include("mysql_connection.php");
$sql="select * from users where userId='$cust_cId';";
$result=mysql_query($sql,$connection)or die(mysql_error());
$row= mysql_fetch_array($result);
$Name = $row['fname']."".$row['lname'];
$moduleid= explode(",",$row['modules']);	
$callLogs=$row['logs'];
for ($i=1;$i<count($moduleid);$i++)
{
	$privilege = explode("~",$moduleid[$i]);
}

?>

<div id="stylefour">
	<?php 
	echo "<ul>";
	if(strstr($_SERVER['PHP_SELF'],"headings.php"))
		echo "<li><a href='headings.php' class='current'>Home</a></li>"; 
	else
		echo "<li><a href='headings.php'>Home</a></li>";
	for($i=0,$j=1;$i<count($moduleid);$i++)
	{ 
		$privilege = explode("~",$moduleid[$i]);
		if($moduleid[$i]!="" && $privilege[1]!="0000")
		{
			$moduleQuery = mysql_query("SELECT link,src,moduleId,moduleName FROM modules WHERE moduleId = '".$moduleid[$i]."'",$connection);
			$row = mysql_fetch_row($moduleQuery); 
			if($row[3]=="Portal Users" && (stristr($_SERVER['PHP_SELF'], 'changeCustomerUsersPassword.php') || strstr($_SERVER['PHP_SELF'], 'customerAddUsers.php') || strstr($_SERVER['PHP_SELF'], 'editCustomerUsersList.php')))
			{
				$_SERVER['PHP_SELF'] = "customerUsersList.php";
			}
			if(strstr($_SERVER['PHP_SELF'],$row[0]))
				echo "<li><a href='$row[0]' class='current'>$row[3]</a></li>";
			else
				echo "<li><a href='$row[0]'>$row[3]</a></li>";
		}
	}
	if(strstr($_SERVER['PHP_SELF'],"accessLog.php"))
		echo "<li><a href='accessLog.php' class='current'>Access Log</a></li>";
	else
		echo "<li><a href='accessLog.php'>Access Log</a></li>";
	if(strstr($_SERVER['PHP_SELF'],"callLogs.php"))
		echo "<li><a href='callLogs.php' class='current'>Call Logs</a></li>";
	else
		echo "<li><a href='callLogs.php'>Call Logs</a></li>";  
	if(strstr($_SERVER['PHP_SELF'],"support.php"))
		echo "<li><a href='support.php' class='current'>Support</a></li>";
	else
		echo "<li><a href='support.php'>Support</a></li>";  
	if(strstr($_SERVER['PHP_SELF'],"changeUserPass.php"))
		echo "<li><a href='changeUserPass.php' class='current'>Change Password</a></li>"; 
	else
		echo "<li><a href='changeUserPass.php'>Change Password</a></li>"; 
 	echo "<li><a href='index.php'>Log Out</a></li>";
echo "</ul>"; 
echo "<div style='float:right;position:absolute;right:10px;top:10px;'>Welcome&nbsp;&nbsp;  <span style='color:#ff9900'>$Name</span></div>";
?>
</div>
