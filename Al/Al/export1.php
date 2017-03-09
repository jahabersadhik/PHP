<?php
include "connection.php";

mysql_select_db("cdr",$connection);

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


// Make column labels. (at line 3)
xlsWriteLabel(2,0,"S.No");
xlsWriteLabel(2,1,"Call Date");
xlsWriteLabel(2,2,"Source");
xlsWriteLabel(2,3,"Destination");
xlsWriteLabel(2,4,"Agent Id");
xlsWriteLabel(2,5,"Duration");
xlsWriteLabel(2,6,"BillSec");
xlsWriteLabel(2,7,"Disposition");

$xlsRow = 3;
$sql = $_REQUEST['q'];
$result=mysql_query($sql)or die(mysql_error());

//$sql1 = $_REQUEST['an'];
//$result1=mysql_query($sql1,$connection)or die(mysql_error());
//$totalbr = mysql_fetch_row($result1);

// Put data records from mysql by while loop.
$recordStatus = "";
$i=1;
$duration = 0;
while($row=mysql_fetch_array($result))
{
	//	$res = mysql_query("SELECT * FROM cdr WHERE accountcode='".$row['accountcode']."' AND src='".$row['src']."' AND accountcode!='' AND lastapp='Queue'",$connection_cdr);
        if(trim($row['dstchannel'])!="")
	{
		xlsWriteLabel($xlsRow,0,$i);
		xlsWriteLabel($xlsRow,1,$row['calldate']);
		xlsWriteLabel($xlsRow,2,$row['src']);
		xlsWriteLabel($xlsRow,3,$row['dst']);
		xlsWriteLabel($xlsRow,4,$row['dstchannel']);
		xlsWriteLabel($xlsRow,5,$row['duration']);
		xlsWriteLabel($xlsRow,6,$row['billsec']);
		xlsWriteLabel($xlsRow,7,$row['disposition']);
		$xlsRow++;
		$i++;
	}
}

$xlsRow = $xlsRow +3;

xlsEOF();
exit();
?>
