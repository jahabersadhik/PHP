<?php
include("mysql_connection.php");
unset($_SESSION['msg']);
$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<style>
#templatemo_menu li a:hover, #templatemo_menu li .<?php echo $current; ?>
{
color: #ffffff;
background: url(./images/templatemo_menu_hover.jpg) repeat-x;	
}
</style>
</head>
<body>
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div >
		<? include("menu.php"); ?>
    	<div id="maincontainer_fullpannel" class="clearfix">
    		<div id="fullpannel">
        		<div class="f_top"><!--&nbsp;--></div>
            		<div class="f_mid">
				<div style="padding:180px auto;padding-top:240px;text-align:center; "><span style="color:#ff9900;font-size:18px;">Welcome to iSystem Admin Panel</span></div>
			</div>
            <div class="f_bottom"></div>
        </div>
    </div>
<?php include("footer.php") ?>
</head>
</html>
