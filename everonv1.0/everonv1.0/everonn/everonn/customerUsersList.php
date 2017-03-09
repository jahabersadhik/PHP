<?php
session_start();

require_once("mysql_connection.php");

$cust_type_message=$_SESSION['type'];
$cust_type_message1=$_SESSION['type1'];

$loginname=$_SESSION['hid']; 
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];
$Gtype=$_SESSION['group'];
$C_IP=$_SESSION['c_ip'];

$FSERVER=$_SESSION['Fserver'];
$Fusername=$_SESSION['Fusername'];
$Fpassword=$_SESSION['Fpassword'];
$custApp_name_message=$_SESSION['Name'];

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");// redirect out
	die(); // need this, or the rest of the script executes
}

$url_cpm_name= $_GET['cn'];
if($Gtype == 'Client Admin')
{ 
	$disableOption = "disabled";
}
else
{
	$disableOption = "";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<style>
#stylefour li a:hover, #stylefour li .<?php echo $current; ?>
{
	color: #999;
	background: url(./images/templatemo_menu_hover.jpg) repeat-x;	
}
</style>
</head>
<script language="javascript">
	var url_name = window.location.search.substring(1);
</script>
<form method=post name="customerUsersList" >
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="">
		<?php include("menu.php");

			if($Gtype != 'admin')
				$access = explode("~",$moduleid[2]);
		?>
		<div id="maincontainer_fullpannel" class="clearfix">
    		<div id="fullpannel">
        		<div class="f_top"><!-- space --></div>
            		<div class="f_mid">
				<div style=" height:auto; width:100%; overflow:auto;">
	<table width=100% cellpadding=1 cellspacing=1 border=0>			
		
<?php
echo "<font color=red><b>$cust_type_message<b></font>";
echo "<font color=red><b>$cust_type_message1<b></font>";
unset($_SESSION['type']);
unset($_SESSION['type1']);
?>
		<tr align=right valign="top">
			<td colspan=10> 
				<?php if($access[1][0] == "1" || $Gtype=='admin')
		        	{ ?>
				<div class="buttonwrapper" style="width:10%;margin:5px auto;">
<a class="squarebutton" href="#" id='' onclick="javascript:location.href='./customerAddUsers.php';"><span>Add User </span></a>
</div>

				<!--<INPUT type="button" value="Add User" name=btnSubmit  class="NormalButton">-->
				<?php } ?>
				<!--<INPUT type="button" value="Back" name="back" onclick="javascript:location.href='headings.php';" class="NormalButton">-->
			
			</td>
			</tr>
			<tr>
				<td width="100" class='stylefour'>First Name </td >
				<td width="100" class='stylefour'>Last Name </td >
				<td width="100" class='stylefour'>Login Name </td >
				<td width="50" class='stylefour'>Status </td >
				<?php 
					if($access[1][2] == "1" || $Gtype=='admin')
        			{ ?>
				<td width="50" class='stylefour'>Delete </td >
				<?php } ?>
				<td width="50" class='stylefour'>Change Password </td >
			</tr>
<?php
	$sql="select * from users where typeOfUser!='admin' order by fname;";
	$result=mysql_query($sql,$connection)or die(mysql_error());
	while($row = mysql_fetch_array($result)) 
	{
	   $FirstName = $row["fname"];
	   $FirstName=ucfirst($FirstName);
	   $LastName = $row["lname"];
	   $LastName=ucfirst($LastName);
	   $LoginName = $row["userName"];
	   $CustomerID = $row["customerId"];
	   $IPAddress = $row["ip"];
	   $GroupType = $row["typeOfUser"];
	   $UserId = $row["userId"];
	   $StAtUs = $row['status'];
	   $customerNameQuery = mysql_query("SELECT customerName FROM customerDetails WHERE customerId='".$row['customerId']."'");
	   $customerNameResult = mysql_fetch_row($customerNameQuery);
		if($access[1][1] == "1" || $Gtype=='admin')
        	{
			$href = "editCustomerUsersList.php?id=".$UserId;
		}
		else 
		{
			$href = "#";

		}
			
if($i%2==0 && $i!=0)
{
?>
<tr>
	<td width="100" class='stylefourtext'>
		<A style="COLOR: black; TEXT-DECORATION: none" href=""><?php echo "$FirstName";?></A>
		</td>	
	<td width="100" class='stylefourtext'>
		<A style="COLOR: black; TEXT-DECORATION: none" href="<?php echo $href ?>"><?php echo "$LastName";?></A>
		</td>
	<td width="100" class='stylefourtext'>
		<A style="COLOR: black; TEXT-DECORATION: none" href="<?php echo $href ?>"><?php echo "$LoginName";?></A>
		</td>
	<td width="100" class='stylefourtext'>
		<A style="COLOR: black; TEXT-DECORATION: none" href="<?php echo $href ?>"><?php echo "$StAtUs";?></A>
	</td>
		
	<td width="100" class='stylefourtext'>
		
		<?php 
		if($access[1][2] == "1" || $Gtype=='admin')
		{ ?>
		<A style="COLOR: blue; TEXT-DECORATION: none" name="Delete" href="javascript:ShowNext(id=<?php echo $UserId;?>);"onclick=""><b>Delete</b></A>
	<td width="180" class='stylefourtext'>
		<?php } ?>
		<A style="COLOR: blue; TEXT-DECORATION: none" name="ChangeCustomerUsersPassword" href="changeCustomerUsersPassword.php?id=<?php echo $UserId;?>"><b>Change Password</b></A>	
			</td>	
</tr>
<?php 
} 
else 
{ 
 ?>
<tr>
	<td width="100" class='stylefourtext1'>
		<A style="COLOR: black; TEXT-DECORATION: none" href=""><?php echo "$FirstName";?></A>
		</td>	
	<td width="100" class='stylefourtext1'>
		<A style="COLOR: black; TEXT-DECORATION: none" href="<?php echo $href ?>"><?php echo "$LastName";?></A>
		</td>
	<td width="100" class='stylefourtext1'>
		<A style="COLOR: black; TEXT-DECORATION: none" href="<?php echo $href ?>"><?php echo "$LoginName";?></A>
		</td>
	<td width="100" class='stylefourtext1'>
		<A style="COLOR: black; TEXT-DECORATION: none" href="<?php echo $href ?>"><?php echo "$StAtUs";?></A>
	</td>
	
	<td width="100" class='stylefourtext1'>
		<?php 
		if($access[1][2] == "1" || $Gtype=='admin')
		{ ?>
		<A style="COLOR: blue; TEXT-DECORATION: none" name="Delete" href="javascript:ShowNext(id=<?php echo $UserId;?>);"onclick=""><b>Delete</b></A>
	<td width="180" class='stylefourtext1'>
		<?php } ?>
		<A style="COLOR: blue; TEXT-DECORATION: none" name="ChangeCustomerUsersPassword" href="changeCustomerUsersPassword.php?id=<?php echo $UserId;?>"><b>Change Password</b></A>	
	</td>	
</tr>
<?php 
}
	$i++;
} ?>
</table>
</div>
<script language="javascript">
function ShowNext(a) {

	var b=a;
	var win = null;
        if (win == null || win.closed) {
            win = window.open("", "newwin", "width=200, height=150");
            win.document.write("<html><head><title>Confirm Dialog</title><\/head>");
            win.document.write("<body><center><b>Are You Sure You Want To DELETE?<br><br></b><input type='button' value='YES' onclick='window.close();window.opener.SaveTonextPadminage("+b+");'><\/input> ");
            win.document.write("<input type='button' value='NO' onclick='window.close();window.opener.nextPage();'><\/input></center><\/body><\/html>");
        }
       win.focus();
    }
//document.write(b);
function SaveTonextPadminage(a1)
{
	var c=a1;
	location.href="deleteCustomerUsersProcess.php?id="+c;
}
function nextPage()
{
	location.href="customerUsersList.php";
}
</script>
	 
			</div>
            <div class="f_bottom"></div>
        </div>
    </div>
<?php include("footer.php") ?>
</form>
</html>
