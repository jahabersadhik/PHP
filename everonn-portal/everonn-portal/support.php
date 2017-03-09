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
				<table width="90%" cellpadding="0" cellspacing="1" style="margin:auto;color:#ffffff;padding:4px;margin-top:30px;">
				<tr>
					<td colspan="6" align="center"><b style="font-size:14px;color:#ff9900;">iSystem Support Escalation Matrix</b></td>
				</tr>
				<tr>
					<td colspan="6" height="10">&nbsp;</td></tr>
				</tr>
				<tr>
					<th  class='stylefour'>Level</th>
					<th  class='stylefour'>Contact Person</th>
					<th  class='stylefour'>Designation</th>
					<th  class='stylefour'>email</th>
					<th  class='stylefour'>Contact No.</th>
					<th  class='stylefour'>Extn</th>
				</tr>
				<tr>
					<th  class='stylefourtext1'>0</th>
					<th  class='stylefourtext1'>iSystem Support</th>
					<th  class='stylefourtext1'>&nbsp;</th>
					<th  class='stylefourtext1'>support.isystem@perisoftware.com</th>
					<th  class='stylefourtext1'>+91 44 4340 6088</th>
					<th  class='stylefourtext1'>&nbsp;</th>
				</tr>
				<tr>
					<th  class='stylefourtext'>1</th>
					<th  class='stylefourtext'>Maruthi Rao</th>
					<th  class='stylefourtext'>Support Lead</th>
					<th  class='stylefourtext'>mrao@perisoftware.com</th>
					<th  class='stylefourtext'>+91 44 4340 6000</th>
					<th  class='stylefourtext'>5772</th>
				</tr>
				<tr>
					<th  class='stylefourtext1'>2</th>
					<th  class='stylefourtext1'>Vasanth Alagar</th>
					<th  class='stylefourtext1'>General Manager (Support)</th>
					<th  class='stylefourtext1'>vasanth@perisoftware.com</th>
					<th  class='stylefourtext1'>+91 44 4340 6000</th>
					<th  class='stylefourtext1'>5755</th>
				</tr>
				<tr>
					<th  class='stylefourtext'>3</th>
					<th  class='stylefourtext'>Rajesh Kumar</th>
					<th  class='stylefourtext'>Director - Business Development</th>
					<th  class='stylefourtext'>rkumar@perisoftware.com</th>
					<th  class='stylefourtext'>+91 44 4340 6000</th>
					<th  class='stylefourtext'>5783</th>
				</tr>
				<tr>
					<th  class='stylefourtext1'>4</th>
					<th  class='stylefourtext1'>Satish Chetty</th>
					<th  class='stylefourtext1'>Director - Technology</th>
					<th  class='stylefourtext1'>schetty@perisoftware.com</th>
					<th  class='stylefourtext1'>+1 40 8207 9600</th>
					<th  class='stylefourtext1'>5505</th>
				</tr>
				<tr>
					<td colspan="6" height="30">&nbsp;</td></tr>
				</tr>
				<?php
				if($Gtype=='admin')
				{
				?>
				<tr>
					<td colspan="6" height="80"><u><b>Note:</b></u>&nbsp;Please <a href="https://122.183.204.45/" style="color:#ff9900;" target="_blank">Click here</a> to access the iSystem support portal.</td>
				</tr>
				<?php
				}
				?>
				</table>
			</div>
            <div class="f_bottom"></div>
        </div>
    </div>
<?php include("footer.php") ?>
</head>
</html>
