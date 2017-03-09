<?php
session_start();
//require_once("mysql_connection.php");
$message=$_SESSION['msg'];
$msg=$_SESSION['message'];
$custMess=$_SESSION['mis'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<title>PERI iSystem WebPortal</title>
<head>
<link href="./css/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="./css/style_new.css" rel="stylesheet" type="text/css" />
<script language="javascript">
	function ValidateMe()
	{
		if (document.Login.txtLoginName.value=="")
		{
			alert("Login Name Cannot be empty");
			document.Login.txtLoginName.focus();
		
			
		}
		else if (document.Login.txtPassWord.value=="")
		{
			
			alert("Password Cannot be empty");
			document.Login.txtPassWord.focus();
		}
		else
		{
			document.Login.method="post";
			document.Login.action = "./loginCheck.php";
			document.Login.submit();
		}
	}
	function SetFocus(e)
	{
		var n=e.keyCode;
		alert(n);
		if (n == 13) 
			ValidateMe();
	}

	
</script>
</head>
<body>
<div id="templatemo_container">
	<div id="templatemo_banner">
	    	<div id="logo"></div>
		<div id="project_name"></div>
	</div>
<div id="templatemo_content">
<div id="stylefour">
        <div class="cleaner"></div>	
    </div>
	<div style="float:left;padding:1px 20px;clear:both;">
            <div style="background:url(images/Login.jpg) no-repeat left  top; height:240px; width: 355px;">
              <div style="padding:40px 10px 10px 90px;width:240px;">
                    <form id="Login" name="Login" method="post" action="javascript:ValidateMe();">
                      <div style="height:25px;padding:10px 0px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;color:#444;text-align:right;">
                            <label >User Name&nbsp; :</label>&nbsp;<input type="text" name="txtLoginName" style="width:150px;border:1px solid #c6c6c6;" />
                      </div>
                        <div style="height:25px;padding:10px 0px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;color:#444;text-align:right;">
                            <label>Password :</label>&nbsp;<input type="password" name="txtPassWord" style="width:150px;border:1px solid #c6c6c6;" />
                      </div>
                         <div style="height:25px;padding:10px 55px 0px;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;color:#444;text-align:right;">
                            
				<div class='buttonwrapper' style='width:73%;'>
					<a class='squarebutton' href='#' id='Login' onclick='javascript:ValidateMe();'><span>Login </span></a>
				</div>
                        </div>
                        <div style="padding-top:30px; height: 25px; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: rgb(68, 68, 68); text-align: center; white-space: nowrap;">
                            <!--<label ><a href="#" style="text-decoration:none;color:#FF9900;">Lost Login/Password?</a></label><br />--><font color="red"> 
								<?php
									/*if($_COOKIE['mansession_id'] != ""){
										unset($_COOKIE['mansession_id']);
									}
									if($_SESSION['u_Customer_Id'] != ""){
										$userQuery="update users set loggedInStatus='loggedOut' where userId='".$_SESSION['u_Customer_Id']."'";
										$resultUsers=mysql_query($userQuery)or die(mysql_error());
									}
									if($_SESSION['userLoggedId'] != ""){
										$userLogQuery = "update userLogStatus set loggedOutTime= '".date("Y-m-d H:i:s")."' where logStatusId = '".$_SESSION['userLoggedId']."'";
										$resultUserLog=mysql_query($userLogQuery)or die(mysql_error());
									}
									if($_SESSION['group'] == 'Call Agent'){
										echo '<script language="javascript">window1 = window.open("","window1");window1.close();</script>';
									}*/
									echo "$message";
									@session_destroy();
									echo "$msg";
									@session_destroy();
									echo "$custMess";
									@session_destroy();
									unset($_SESSION);
								?>
								</font>
                        </div>
                    </form>
              </div>
    </div>
  </div>
                <p align="justify" class="content_txt" id="intro">PERI iSystem Portal is a  tool that allows you to manage your enterprise level communication system. iSystem is a cost effective solution for your enterprise communication needs.</p>
<?php include('footer.php');?>
</div>
    
    <!-- end of footer -->
</div>
<script language="javascript">
var charfield=document.getElementById("Login")
	charfield.onkeypress=function(e)
	{
		var e=window.event || e
		var keyunicode=e.charCode || e.keyCode
		if(keyunicode == 13)
			ValidateMe();
	}
</script>
</body>
</html>
								
