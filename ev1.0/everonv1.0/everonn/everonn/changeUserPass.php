<?php
include("mysql_connection.php");

$uid=$_SESSION['hidden'];
$password=$_SESSION['pass'];

$loginname=$_SESSION['hid']; 
$name=$_SESSION['hidd'];
$cust_cId=$_SESSION['u_Customer_Id'];
$cust_uId=$_SESSION['c_Customer_Id'];

if(!isset($_SESSION['hid']))
{
	header("Location: index.php");
	die();
}

$sql_lname="SELECT userName,passWord,status FROM users WHERE userId='$cust_cId';";
$result_lname=mysql_query($sql_lname,$connection)or die(mysql_error());
$row_lname= mysql_fetch_array($result_lname);
$Login_Name=$row_lname['userName'];
$pass_word=$row_lname['passWord'];
$status=$row_lname['status'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>PERI iSystem Web Portal</title>
<link href="./css/templatemo_style.css" rel="stylesheet" type="text/css" />
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
<form method=post name="changeUserPass" >
<div id="container">
		<div id="templatemo_container">
    <div id="templatemo_banner">
    	<div id="logo"></div>
        <div id="project_name"></div>
    </div>
    <div id="templatemo_menu_search">
        <div id="templatemo_menu">
	<?php include('menu.php'); ?>
	<div id="maincontainer_fullpannel" class="clearfix">
    		<div id="fullpannel">
    			<div class="f_top"><!--&nbsp;--></div>
        		<div class="f_mid">     
			<div style=" height:auto; width:100%; overflow:auto;">
<table width="100%" height="335" border="0" cellpadding="0" cellspacing="0">
     <tr>
       <td align=center>
       <table border="0"  align=center>
         <tr>
         <td>
            <table cellspacing="10" cellpadding="0" border="0" width="100%" class=headings>
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>Login Name</LABEL>
          	  </td>
          	  <td><LABEL class=sublinks><?php echo $_SESSION['username'];?></LABEL>
               </tr>  
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>Old Password</LABEL>
          	  </td>
          	  <td><input type="password" name=txtold class=MandatoryTextbox maxlength=15 value="<?php echo $pass_word;?>" readonly>
               </tr>
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>New Password</LABEL>
        	   
          	  </td>
          	  <td><input type="password" name=txtnew class=MandatoryTextbox maxlength=15>
               </tr>
	       <tr> 
        	  <td> 
        	  <LABEL class=sublinks>Confirm Password</LABEL>
          	  </td>
          	  <td><input type="password" name=txtconfirm class=MandatoryTextbox maxlength=15>
           </tr>                              
           <tr height=30%> 
        	  <td  align="center" width="100%" colspan=2> 
          	   
				<div class='buttonwrapper' style='width:70%;'>
					
					<a class='squarebutton' href='#' id='Sava' onClick="gosubmit()"><span>Save </span></a>
					<a class='squarebutton' href='#' id='Delete'  onClick="javascript:location.href = './headings.php'" style='margin-left:10px;'><span>Back </span></a>
				</div>
  </td>
         		<td>
          	      
          	  </td>
               </tr>

            </table>
                       <table>
                          <tr>
                              <td>
               <LABEL class=sublinks>* Password value should be more than 8  
               <br>characters and less than 16 characters in length<br><br>Password should contain atleast one number and one special character</LABEL>
               </td>
               </tr>
           </table>
             </td>
           </tr>
           </table>

           
       </td>
     </tr>
   </table>
   </div>

</div>
	<div class="f_bottom"></div>
	</div>
    </div>
<?php include('footer.php');?>
<input type ="hidden" name="flag" value="0">
<input type ="hidden" name="oldpass" value="<?php echo $password;?>">
</form>
</body>
<script language="javascript">
var pwd_url = window.location.search.substring(1);

function gosubmit()
  {	
  	var rxPassword = new RegExp(/(([\d].*[^\w])|([^\w].*[\d])|([^\w].*[\d]))+/);
	var val = /[A-Z]/;
	var sPassword = new String();	
	sPassword = document.changeUserPass.txtnew.value; // Should be false, missing special character
	if(document.changeUserPass.txtnew.value =="")
	{
		alert("Enter PassWord");
		document.changeUserPass.txtnew.focus();
	}
	else if(document.changeUserPass.txtconfirm.value =="")
	{
		alert("Enter Confirm Password");
		document.changeUserPass.txtconfirm.focus();
	}
	else if(document.changeUserPass.txtnew.value.length <= 8)
	{
		//alert(changePassWord.txtnew.value.length);
		alert("Password value should be more than 8 characters and less than 16 characters in length");
		document.changeUserPass.txtnew.focus();
	}
	else if(!val.test(document.changeUserPass.txtnew.value))
	{
		//alert(changePassWord.txtnew.value.length);
		alert("Password should have atleast one capital letter");
		document.changeUserPass.txtnew.focus();
	}
 	else if(document.changeUserPass.txtconfirm.value.length <= 8)
	{
		alert("Password value should be more than 8 characters and less than 16 characters in length");
		document.changeUserPass.txtconfirm.focus();
	}	 
	else if(document.changeUserPass.txtnew.value != document.changeUserPass.txtconfirm.value )
	 {
		alert("Mismatch of New password and Confirm password!");
		document.changeUserPass.txtnew.focus();
	 }else if(!rxPassword.test(sPassword)){
	 	alert("Password should contain atleast one number and one special character");
		document.changeUserPass.txtnew.focus();
	 }
	else
	 {
		document.changeUserPass.flag.value = 1;
		document.changeUserPass.action = "./changeUserPassProcess.php?"+pwd_url;
		document.changeUserPass.submit();     
     }
  }
</script>

</html>
