<?php
	session_start();
	if(isset($_REQUEST['submit']))
	{
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$exten = $_REQUEST['extensionno'];
		$connection = mysql_connect("localhost","root","PeRi");
		mysql_select_db("altech",$connection);
		
		/*$sql_select_extn = mysql_query("SELECT extension FROM loginLog WHERE empId='$username'");
		$extn_row = mysql_fetch_row($sql_select_extn);*/
		if($username!='admin')
		{
			/*$loginResult = mysql_query("SELECT * FROM userData WHERE empId='$username' AND secret='$password'");
			$row =  mysql_fetch_array($loginResult);
			
			if(mysql_num_rows($loginResult)>0)
			{
				session_regenerate_id();
				$sid = session_id();	
				mysql_query("UPDATE userData SET SID='$sid' WHERE empId='$username'");
				$_SESSION['uName'] = $sid;
				$_SESSION['userName'] = $username;
				$_SESSION['dst'] = $row['designation'];
				$_SESSION['extn'] = $extn_row[0];
				if($username == 'admin')
				$_SESSION['admin'] = 'yes';
				header("location:home.php");
			}*/
			$res = mysql_query("SELECT *FROM userData WHERE empId='$username' AND secret='$password'");
			if(mysql_num_rows($res)>0)
			{
				$row = mysql_fetch_array($res);
				$asteriskUsername = 'admin';
				$asteriskPassword = 'peri123';
				$IPAddress = '115.113.233.146';
			//	$IPAddress = '192.168.2.115';
				$loginDate = date('Y-m-d');
				$loginTime = date('G:i:s');
				
				mysql_query("INSERT INTO loginLog VALUES(NULL,'$loginDate','$loginTime','','','$username','1','$exten','','0')") or die(mysql_error());
				$sid = session_id();
				$_SESSION['uName'] = $sid;
				$_SESSION['userName'] = $username;
				$_SESSION['dst'] = $row['designation'];
				$_SESSION['extn'] = $exten;
				$_SESSION['loginTime'] = $loginTime;
				$_SESSION['loginDate'] = $loginDate;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
				curl_setopt($ch, CURLOPT_HEADER, 1);
				@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				$curlOutput = curl_exec($ch);
				$expOutput = explode(";",$curlOutput);
				$expCookeInfo = explode("mansession_id=",$expOutput[1]);
				$cookieValue = str_replace('"','',$expCookeInfo[1]);
				$expPhpSelf = explode("/",$_SERVER['PHP_SELF']);
				$strToAdd = "";
				if(!strstr($expPhpSelf[1],"."))
				{
					$strToAdd = $expPhpSelf[1]."/";
				}
				setcookie("mansession_id",$cookieValue,time()+3600,"/".$strToAdd,$_SERVER['SERVER_ADDR']);
				curl_close($ch);
				
				$actionid = rand(11111,99999);
				
				$ch = curl_init();
				$testurl = "https://$IPAddress/asterisk/rawman?action=AgentCallBackLogin&agent=$username&exten=$exten&Context=default&AckCall=false&WrapupTime=30%20&ActionID=$actionid";
				curl_setopt($ch, CURLOPT_URL, $testurl);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				$mansession_id = $_COOKIE['mansession_id'];
				@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
				$curlOutput = curl_exec($ch);
				curl_close($ch);
				
				header("location:home.php");
			}
			else 
			{
				$_SESSION['err'] = "Wrong Username/Password";	
				header("location:index.php");
			}
		}
		else
		{
			$loginResult = mysql_query("SELECT * FROM login WHERE username='$username' AND password='$password'");
			$row =  mysql_fetch_array($loginResult); 
			if(mysql_num_rows($loginResult)>0)
			{
				session_regenerate_id();
				$sid = session_id();	
				mysql_query("UPDATE login SET sessionid='$sid'");
				$_SESSION['uName'] = $sid;
				$_SESSION['admin'] = 'yes';
				$_SESSION['dst'] = $row['designation'];
				$_SESSION['extn'] = $extn_row[0];
				header("location:home.php");
			}
			else 
			{
				$_SESSION['err'] = "Wrong Username/Password";	
				header("location:index.php");
			}
		}
	}
?>
