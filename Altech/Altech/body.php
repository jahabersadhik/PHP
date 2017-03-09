<?php
	include "check.php";
	if($_REQUEST['page']=='teams')
	{
		include "createEmployees.php";
	}
	if($_REQUEST['page']=='queues' && $_REQUEST['q'] == '')
	{
		include "createQueues.php";
	}
	if($_REQUEST['page']=='queues' && $_REQUEST['q'] != '')
	{
		include "editQueues.php";
	}
	if($_REQUEST['page']=='hierarchy')
	{
		include "createHierarchy.php";
	}
	if($_REQUEST['page']=='privileges')
	{
		include "privileges.php";
	}
	if($_REQUEST['page']=='process')
	{
		include "process.php";
	}
	if($_REQUEST['page']=='snoop')
	{
		include "callsnooping/snoop.php";
	}
	if($_REQUEST['page']=='barge')
	{
		include "callbarging/barge.php";
	}
	if($_REQUEST['page']=='map')
	{
		include "activeChannels.php";
	}
	if($_REQUEST['page']=='users')
	{
		include "users.php";
	}
	if($_REQUEST['page']=="activityreport")
	{
		include "activityReport.php";
	}
	if($_REQUEST['page']=="activityreportout")
	{
		include "activityReportOut.php";
	}
	if($_REQUEST['page']=="sp")
	{
		include "top.php";
	}
    if($_REQUEST['page']=="record")
	{
        include "record.php";
    }
	if($_REQUEST['page']=="vm")
	{
        include "voicemail.php";
    }

	//manual dialing Admin Steps
	if($_REQUEST['page']=='manualAdmin')
	{
		include "callAdmin.php";
	}
	if($_REQUEST['page']=='manualmain2')
	{
		include "manualdialer/manualDialingMain.php";
	}
	
	//Manual Dialing Agent Steps
	if($_REQUEST['page']=='manualagent')
	{
		include "manualdialeragent/agentAccountInformation.php";
	}
	//End of Manual Dialing Agent Steps
	
	//Manual Dialing Report Steps
	if($_REQUEST['page']=='manualreport')
	{
		include "manualdialer/adminReport.php";
	}
	//End of Manual Dialing Report Steps
	
	
	
	//preview dialing Admin Steps
	if($_REQUEST['page']=='previewAdmin')
	{
		include "callAdmin.php";
	}
	if($_REQUEST['page']=='previewmain2')
	{
		include "previewdialer/previewDialingStart.php";
	}
	//end of preview dialing Admin Steps
	//Preview Dialing Agent Steps
	if($_REQUEST['page']=='previewagent')
	{
		include "previewdialeragent/agentAccountInformation.php";
	}
	//End of Preview Dialing Agent Steps
	//Preview Dialing Report Steps
	if($_REQUEST['page']=='previewreport')
	{
		include "previewdialer/adminReport.php";
	}
	//End of Preview Dialing Report Steps
	
	//Predictive Dialing Admin Steps
	if($_REQUEST['page']=='predictiveAdmin')
	{
		include "callAdmin.php";
	}
	if($_REQUEST['page']=='startdialing')
	{
		include "predictivedialer/predictiveDialingStart.php";
	}
	if($_REQUEST['page']=='predictivemain2')
	{
		include "predictivedialer/predictiveDialingStart.php";
	}
	//end of PredictiveDialing Steps
	
	//Predictive Dialing Agent Steps
	if($_REQUEST['page']=='predictiveagent')
	{
		include "predictivedialeragent/agentAccountInformation.php";
	}
	//End of Predictive Dialing Agent Steps
	//Predictive Dialing Report Steps
	if($_REQUEST['page']=='predictivereport')
	{
		include "predictivedialer/adminReport.php";
	}
	//End of Predictive Dialing Report Steps

	if($_REQUEST['page']=='incoming')
	{
		include "incoming/information.php";
	}
	if($_SESSION['dst']=="AGENT")
	{
		//include("breaktime.php");
	}

	

?>
