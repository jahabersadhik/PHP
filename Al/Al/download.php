<?php
if($_REQUEST['mode'] == 'r')
{
	$filename=$_REQUEST['filename'];
	//$file_extension = strtolower(substr(strrchr("$filename","."),1));
	chmod("/var/spool/asterisk/monitor/$filename",0777);

	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-type: application/force-download");
	    header("Content-type: audio/x-wav");
	     if($fp=@fopen("/var/spool/asterisk/monitor/$filename",'r'))
	     {
		    header("Content-type: audio/x-wav");
		    header("Content-Disposition: attachment; filename=download.gsm");
		    fpassthru($fp);
		    fclose($fp);
		    exit;
	     }

	    //Force the download
	    header("Content-Disposition: attachment; filename=download.gsm");
}
if($_REQUEST['mode'] == 'v')
{
	$filename=$_REQUEST['filename'];
	$number = $_REQUEST['number'];
	//$file_extension = strtolower(substr(strrchr("$filename","."),1));
	chmod("/var/spool/asterisk/voicemail/default/$number/INBOX/$filename",0777);

	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-type: application/force-download");
	    header("Content-type: audio/x-wav");
	     if($fp=@fopen("/var/spool/asterisk/voicemail/default/$number/INBOX/$filename",'r'))
	     {
		    header("Content-type: audio/x-wav");
		    header("Content-Disposition: attachment; filename=vdownload.gsm");
		    fpassthru($fp);
		    fclose($fp);
		    exit;
	     }

	    //Force the download
	    header("Content-Disposition: attachment; filename=vdownload.gsm");
}
?>


