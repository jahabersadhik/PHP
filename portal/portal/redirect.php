<?php
include("mysql_connection.php");

if(!isset($_SESSION['hid'])){
header("Location: index.php");// redirect out
die(); // need this, or the rest of the script executes
} 
$socket = fsockopen("$IPAddress","5038", $errno, $errstr);
fputs($socket, "Action: Login\r\n");
fputs($socket, "UserName: $asteriskUsername\r\n");
fputs($socket, "Secret: $asteriskPassword\r\n\r\n");
fputs($socket, "Action: Command\r\n");
fputs($socket, "Command: reload\r\n\r\n");
$wrets=fgets($socket,128);

if($wrets)
{
	echo "Success";
}
?>
