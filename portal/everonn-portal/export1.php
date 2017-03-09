<?php

include "mysql_connection.php";
// Functions for export to excel.
function xlsBOF() 
{
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}
function xlsEOF() 
{
echo pack("ss", 0x0A, 0x00);
return;
}
function xlsWriteNumber($Row, $Col, $Value) 
{
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}
function xlsWriteLabel($Row, $Col, $Value ) 
{
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=callReports.xls ");
header("Content-Transfer-Encoding: binary "); 

xlsBOF();

xlsWriteLabel(0,3,"Call Report");

/*
// Make column labels. (at line 3)
xlsWriteLabel(2,0,"S.No");
xlsWriteLabel(2,1,"Call Date");
xlsWriteLabel(2,2,"Source");
xlsWriteLabel(2,3,"Destination");
xlsWriteLabel(2,4,"Agent Id");
xlsWriteLabel(2,5,"Duration");
xlsWriteLabel(2,6,"Wrap Time");
xlsWriteLabel(2,7,"Disposition");

$xlsRow = 3;
$sql = urldecode($_REQUEST['q']);
$result=mysql_query($sql,$connection_cdr)or die(mysql_error());

$sql1 = urldecode($_REQUEST['an']);
$result1=mysql_query($sql1,$connection)or die(mysql_error());
$totalbr = mysql_fetch_row($result1);
// Put data records from mysql by while loop.
$recordStatus = "";
$i=1;
$duration = 0;
$count_new=0;
while($row=mysql_fetch_array($result))
{
	$dur = strtotime($row['calldate'])+$row['duration'];
	$res = mysql_query("SELECT * FROM callsList WHERE UNIX_TIMESTAMP(callDate)>=UNIX_TIMESTAMP('".$row['calldate']."') AND UNIX_TIMESTAMP(callDate)<=$dur AND agentId='".$row['dstchannel']."'",$connection_cdr);
        if(mysql_num_rows($res)>0)
        {
            		$row1 = mysql_fetch_array($res);
			$callduration = $row['duration']-( strtotime($row1['callDate']) - strtotime($row['calldate']) );
			$wraptime = (strtotime($row1['nextcallDate'])-strtotime($row1['callDate']))-$callduration;
			if($wraptime>0 && $wraptime<=120)
				$wraptime = $wraptime;
			else
				$wraptime = 0;
			xlsWriteLabel($xlsRow,0,$i);
			xlsWriteLabel($xlsRow,1,$row['calldate']);
			xlsWriteLabel($xlsRow,2,$row1['src']);
			xlsWriteLabel($xlsRow,3,$row1['dst']);
			xlsWriteLabel($xlsRow,4,str_replace("Agent/"," ",$row['dstchannel']));
			xlsWriteLabel($xlsRow,5,$callduration);
			xlsWriteLabel($xlsRow,6,$wraptime);
			xlsWriteLabel($xlsRow,7,$row['disposition']);
			$xlsRow++;
			$i++;
			$duration = $duration + $callduration;
			$billsec  = $billsec + $row['billsec'];
			$warp = $warp + $wraptime;
			$count_new++;
	}
}
if($_REQUEST['ag']!='0')
{
$avgDuration = ($duration/$count_new);
$avgBillsec = ($billsec/$count_new);
$avgWarp = ($warp/$count_new);
$avg = 1;
}
else
{
$avgDuration = ($duration/$totalagent);
$avgBillsec = ($billsec/$totalagent);
$avgWarp = ($warp/$totalagent);
$avg = round(($count_new/$totalagent),2); 
}
$xlsRow = $xlsRow +3;

xlsWriteLabel($xlsRow,0,"Total Calls");
xlsWriteLabel($xlsRow,1,"Total Duration");
xlsWriteLabel($xlsRow,2,"Total Wrap Time");
xlsWriteLabel($xlsRow,3,"Total Break Time");


xlsWriteLabel($xlsRow+1,0,$count_new);
xlsWriteLabel($xlsRow+1,1,converthours($duration));
xlsWriteLabel($xlsRow+1,2,converthours($warp));
xlsWriteLabel($xlsRow+1,3,converthours($totalbr[0]));

xlsWriteLabel($xlsRow+2,0,"Average Per Each Call");
xlsWriteLabel($xlsRow+2,1,"Average Duration");
xlsWriteLabel($xlsRow+2,2,"Average Wrap Time");
//xlsWriteLabel($xlsRow+2,3,"Average Break Time");

xlsWriteLabel($xlsRow+3,0,$avg);
xlsWriteLabel($xlsRow+3,1,converthours($avgDuration));
xlsWriteLabel($xlsRow+3,2,converthours($avgWarp));
//xlsWriteLabel($xlsRow+3,3,$totalBreakTime);
xlsEOF();
*/

xlsWriteLabel(2,0,"S.No");
xlsWriteLabel(2,1,"Call Date");
xlsWriteLabel(2,2,"Source");
xlsWriteLabel(2,3,"Destination");
xlsWriteLabel(2,4,"Duration");
xlsWriteLabel(2,5,"Disposition");

$xlsRow = 3;
$sql = urldecode($_REQUEST['q']);
$result=mysql_query($sql,$connection_cdr)or die(mysql_error());
$i=1;
while($row=mysql_fetch_array($result))
{

	xlsWriteLabel($xlsRow,0,$i);
	xlsWriteLabel($xlsRow,1,$row['calldate']);
	xlsWriteLabel($xlsRow,2,$row['src']);
	xlsWriteLabel($xlsRow,3,$row['dst']);
	xlsWriteLabel($xlsRow,4,$row['duration']);
	xlsWriteLabel($xlsRow,5,$row['disposition']);
	$xlsRow++;
	$i++;

}
xlsEOF();
exit();
?>
