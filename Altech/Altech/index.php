<?php
	session_start();
?>
<html>
<head>
	<title>iSystem-Call Center</title>
</head>
<style type="text/css">
table
{
	font-family:Arial;
	font-size:12px;
}
</style>
<body>
<form method="post" action="login.php">
<div style="width:400px;height:100px;margin:auto;margin-top:250px;">
	<table width="300" cellpadding="2" cellspacing="2" style="border:1px solid #ff9900;">
	<tr><td colspan="2" style="height:25px;background:#ff9900;" align="center"><strong><i>iSystem</i></strong> Portal Login</td></tr>
	<tr>
		<td style="height:25px;">Username</td>
		<td><input type="text" name="username" id="username" /></td>
	</tr>
	<tr>
		<td style="height:25px;">Password</td>
		<td><input type="password" name="password" id="password" /></td>
	</tr>
	<tr>
		<td style="height:25px;">Exten. no</td>
		<td><input type="text" name="extensionno" id="extensionno" maxlength="4" /></td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Login" /></td></tr>
	<tr><td colspan="2" align="center">
		<?php
			echo $_SESSION['err1']."&nbsp;";
			echo $_SESSION['err2']."&nbsp;";
			echo $_SESSION['err3']."&nbsp;";
			echo $_SESSION['err'];
			unset($_SESSION['uName']);
			unset($_SESSION['admin']);
			unset($_SESSION['err']);
		?>
	</td></tr>
	</table>
</div>	
</form>
</body>
</html>
