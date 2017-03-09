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

xlsWriteLabel(2,0,"S.No");
xlsWriteLabel(2,1,"Call Date");
xlsWriteLabel(2,2,"Source");
xlsWriteLabel(2,3,"Destination");
xlsWriteLabel(2,4,"Call Type");
xlsWriteLabel(2,5,"Duration");
xlsWriteLabel(2,6,"Disposition");

$xlsRow = 3;
$sql = urldecode($_REQUEST['q']);
$extarr = explode(",",$_REQUEST['extstr']);
$result=mysql_query($sql,$connection_cdr)or die(mysql_error());
$i=1;
while($row=mysql_fetch_array($result))
{
	in_array($extarr,$row['src'])?$calltype='OutGoing':$calltype='Incoming';

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
