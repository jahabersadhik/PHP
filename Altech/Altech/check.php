<?php
	session_start();
	if(!isset($_SESSION['uName']))
		header("location:index.php");
?>