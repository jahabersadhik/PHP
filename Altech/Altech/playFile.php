<?php
session_start();
include "connection.php";
if($_REQUEST['mode'] == 'v')
{
	$filename = str_replace(".txt",".gsm",$_REQUEST['filename']);
	$number = $_REQUEST['number'];
	$adminId = $_SESSION['userName'];
	$resource=@ssh2_connect($IPAddress);
	ssh2_auth_password($resource,$sshUsername,$sshPassword)  or die("error");
	ssh2_exec($resource,"rm -rf /var/www/html/Altech/voicemails/*.*");
	$ret1 =ssh2_exec($resource,"sox /var/spool/asterisk/voicemail/default/$number/INBOX/$filename -t wav -s - | lame - /var/www/html/Altech/voicemails/$number-$adminId.mp3");
	ssh2_exec($resource,"chmod 777 -R /var/www/html/Altech/voicemails");
}
if($_REQUEST['mode'] == 'd')
{
	$filename = $_REQUEST['filename'];
	$filename1 = str_replace(".txt",".wav",$_REQUEST['filename']);
	$filename2 = str_replace(".txt",".gsm",$_REQUEST['filename']);
	$filename3 = str_replace(".txt",".WAV",$_REQUEST['filename']);
	$number = $_REQUEST['number'];
	$adminId = $_SESSION['userName'];
	$resource=@ssh2_connect($IPAddress);
	ssh2_auth_password($resource,$sshUsername,$sshPassword)  or die("error");
	ssh2_exec($resource,"rm -rf /var/spool/asterisk/voicemail/default/$number/INBOX/$filename");
	ssh2_exec($resource,"rm -rf /var/spool/asterisk/voicemail/default/$number/INBOX/$filename1");
	ssh2_exec($resource,"rm -rf /var/spool/asterisk/voicemail/default/$number/INBOX/$filename2");
	ssh2_exec($resource,"rm -rf /var/spool/asterisk/voicemail/default/$number/INBOX/$filename3");
	header("location:home.php?page=vm&number=$number");
}

//echo '';
?>
<html>
<head>
<script type="text/javascript" src="scripts/swfobject.js"></script>
</head>
<body>
<table width="300" height="52" align="center">
<embed src= "audio_player_standard_gray.swf" quality="high" width="300" height="52" allowScriptAccess="always" wmode="transparent"  type="application/x-shockwave-flash" flashvars= "valid_sample_rate=true&external_url=<?php echo "voicemails/$number-$adminId.mp3";?>" pluginspage="http://www.macromedia.com/go/getflashplayer"> </embed>
</table>
</body>
</html>
