<?php 
session_start();
$sql_Queue = mysql_query("SELECT * FROM queues",$connection);

// Select Agent id form Queue Table
if($_REQUEST['Qn']!="")
{	$sql_Queue_Agent = mysql_query("SELECT members FROM queues WHERE queueName=".$_REQUEST['Qn']."",$connection);
	$agendRow = mysql_fetch_row($sql_Queue_Agent);
	$agendId = split(",",$agendRow[0]);
}

/*if($_GET['Qn']!="")
{
	$condition = "";//"AND dst=".$_GET['Qn'];	
}
if($_GET['ae']!="")
{
	$agentId = 'Agent/'.$_GET['ae'];
	$condition = " AND dstchannel = '".$agentId."'";	
}
else 
{
	$condition1 = "";
}
if($_GET['srcdst']!="")
{
	$condition .= " AND src ='".$_GET['srcdst']."'";	
}
if($_GET['dt']!="" || $_GET['edt']!="")
{
	$sdate = $_REQUEST['dt']." 00:00:00";
	$enddate = $_REQUEST['edt']." 23:59:59";
	$condition .= " AND UNIX_TIMESTAMP(calldate) >= UNIX_TIMESTAMP('$sdate') AND UNIX_TIMESTAMP(calldate)<=UNIX_TIMESTAMP('$enddate')";	
}
if($_GET['act']=="submit")
{
	$select_Cdr = mysql_query("SELECT * FROM cdr $condition1) WHERE 1 AND lastapp='Queue' $condition",$connection_cdr);
	while($val = mysql_fetch_array($select_Cdr))
	{
		$dt = explode(" ",$val['calldate']);
		$date = trim(str_replace("-","",$dt[0]));
		$time = str_replace(":","",$dt[1]);
		$cdrfile[] = $_REQUEST['Qn']."-".$val['src']."-".$date;
	
	}
}*/
if($_REQUEST['fileName']!='' && $_REQUEST['hextn']!='')
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://".$IPAddress."/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$curlOutput = curl_exec($ch);
	$expOutput = explode(";",$curlOutput);
	$expCookeInfo = explode("mansession_id=",$expOutput[1]);
	$cookieValue = str_replace('"','',$expCookeInfo[1]);
	setcookie("mansession_id",$cookieValue,time()+3600,"/".$strToAdd,$_SERVER['SERVER_ADDR']);
	curl_close($ch);
	
	$fileName = $_GET['fileName'];
	
	if(file_exists("/var/spool/asterisk/monitor/$fileName"))
	{
		if(strstr($fileName,".gsm"))
		$filePathToPlay = "/var/spool/asterisk/monitor/".str_replace(".gsm","",$fileName);	
		if(strstr($fileName,".wav"))
		$filePathToPlay = "/var/spool/asterisk/monitor/".str_replace(".wav","",$fileName);	
	}
	
	if(strlen($_REQUEST['hextn'])==4)
		$url = "https://".$IPAddress."/asterisk/rawman?action=originate&channel=Local/".$_REQUEST['hextn']."&context=asterisk_guitools&exten=play_file&priority=1&Variable=var1=$filePathToPlay";
	else
	 $url = "https://".$IPAddress."/asterisk/rawman?action=originate&channel==DAHDI/G1/".$_REQUEST['hextn']."&context=asterisk_guitools&exten=play_file_out&priority=1&Variable=var1=$filePathToPlay&Variable=var2=DAHDI/G1/".$_REQUEST['hextn']."";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$mansession_id = $_COOKIE['mansession_id'];
	@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput = curl_exec($ch);
	curl_close($ch);
}
?>
<script type="text/javascript">
	var calendar1, calendar2, calendar3; /*must be declared in global scope*/ 
	
	function loadcal() 
	{ 
		 calendar2 = new Epoch('cal2','popup',document.getElementById('txtSdate'),false); 
		 calendar3 = new Epoch('cal2','popup',document.getElementById('txtEdate'),false); 
	} 
	function changeQueueName()
	{
		var mode1 = document.getElementById('mode').options[document.getElementById('mode').selectedIndex].value;
		location.href = "home.php?page=record&Qn="+mode1;
	}
	function changeAgentid()
	{
		var extn = document.getElementById('agentextn').options[document.getElementById('agentextn').selectedIndex].value;
		var mode1 = document.getElementById('mode').options[document.getElementById('mode').selectedIndex].value;
		location.href = "home.php?page=record&Qn="+mode1+"&ae="+extn;
	}
	function changeDate()
	{
		var stdt = document.getElementById('txtSdate').value;
		var eddt = document.getElementById('txtEdate').value;
		var extn = document.getElementById('agentextn').options[document.getElementById('agentextn').selectedIndex].value;
		var mode1 = document.getElementById('mode').options[document.getElementById('mode').selectedIndex].value;
		if(stdt=='')
		{
			alert("Please Select Start Date");
			return false;
		}
		if(eddt=='')
		{
			alert("Please Select End Date");
			return false;
		}
		location.href = "home.php?page=record&Qn="+mode1+"&ae="+extn+"&dt="+stdt+"&edt="+eddt;
	}
	function displayFiles()
	{
		var extn = document.getElementById('agentextn').options[document.getElementById('agentextn').selectedIndex].value;
		var srcdst = document.getElementById('srcdst').options[document.getElementById('srcdst').selectedIndex].value;
		var mode1 = document.getElementById('mode').options[document.getElementById('mode').selectedIndex].value;
		var dt = document.getElementById('txtSdate').value;
		var edt = document.getElementById('txtEdate').value;
		location.href = "home.php?page=record&Qn="+mode1+"&ae="+extn+"&srcdst="+srcdst+"&dt="+dt+"&edt="+edt;
	}
	function frmSubmit()
	{
		var extn = document.getElementById('agentextn').options[document.getElementById('agentextn').selectedIndex].value;
		var srcdst = document.getElementById('srcdst').options[document.getElementById('srcdst').selectedIndex].value;
		var mode1 = document.getElementById('mode').options[document.getElementById('mode').selectedIndex].value;
		var dt = document.getElementById('txtSdate').value;
		var edt = document.getElementById('txtEdate').value;
		if(mode1=='-1')
		{
			alert('Please select the Queue name').
			i = i + 0; 		
		}
		else if(extn=='-1')
		{
			alert('Please select the Agent id').
			i = i + 0;
		}
		else if(dt=='')
		{
			alert('Please select the Start date').
			i = i + 0;
		}
		else if(edt=='')
		{
			alert('Please select the End date').
			i = i + 0;
		}
		else if(srcdst=='-1')
		{
			alert('Please select the SRC').
			i = i + 0;
		}
		else 
		{	
			location.href = "home.php?page=record&Qn="+mode1+"&ae="+extn+"&srcdst="+srcdst+"&dt="+dt+"&edt="+edt+"&act=submit";
		}
	}
	function playFile(fileName)
	{
		if(document.getElementById('hextn').value=='')
		{
			alert("Provide extension to Hear");
			document.getElementById('hextn').focus();
			return false;
		}
		else
		{
			var extn = document.getElementById('agentextn').options[document.getElementById('agentextn').selectedIndex].value;
			var srcdst = document.getElementById('srcdst').options[document.getElementById('srcdst').selectedIndex].value;
			var mode1 = document.getElementById('mode').options[document.getElementById('mode').selectedIndex].value;
			var dt = document.getElementById('txtSdate').value;
			var edt = document.getElementById('txtEdate').value;
			var hextn = document.getElementById('hextn').value;
			location.href = "home.php?page=record&Qn="+mode1+"&ae="+extn+"&srcdst="+srcdst+"&dt="+dt+"&edt="+edt+"&fileName="+fileName+"&hextn="+hextn+"&act=submit";
			
		}
	}
</script>

		<table width=100% class=normalfont cellpadding=0 border=0>	
		<tr>
			<td align="left" width="15%"><LABEL >Process Name <font color="Red">*</font></LABEL></td>
			<td align=left width="35%">
				<select name="mode" id="mode" onchange="changeQueueName()">
					<option value="-1">-Process Name-</option>
					<?php while($QueuRow = mysql_fetch_array($sql_Queue)) 
					      { 
					      	$que = mysql_query("select *from process where queueName like '%".$QueuRow['queueName']."%'",$connection) or die(mysql_error());
					      	$QueuRow1 = mysql_fetch_array($que);
							if($_REQUEST['Qn']==$QueuRow['queueName'])
							{ 	
					?>
							<option value="<?php echo $QueuRow['queueName']; ?>" selected ><?php echo $QueuRow1['processName']; ?></option>
					<?php 	
							}
							else 
							{	 
					?>
							<option value="<?php echo $QueuRow['queueName']; ?>" ><?php echo $QueuRow1['processName']; ?></option>
					<?php   
							} 
						  } 
					?>
				</select>
			</td>
			<td align="left" width="15%"><LABEL >Agent Id <font color="Red">*</font></LABEL></td>
			<td width="35%">
				<select name="agentextn" id="agentextn"  onchange="changeAgentid()">
					<option value="-1">-Agent Id-</option>
					<?php for($i=0; $i<count($agendId); $i++)
					      { 
							if($_REQUEST['ae']==$agendId[$i])
							{ ?>
							<option value="<?php echo $agendId[$i]; ?>" selected ><?php echo $agendId[$i]; ?></option>
					<?php 		}
							else
							{ ?>
							<option value="<?php echo $agendId[$i]; ?>"><?php echo $agendId[$i]; ?></option>
					<?php } } ?>
				</select>
			<td>
		</tr>
		<tr>	
			<td align="left"><LABEL>Start Date <font color="Red">*</font></LABEL></td>
			<td align=left>
				<input type="text" name="txtSdate" id="txtSdate" size="8" value="<?php echo $_GET['dt'];?>">

			</td>
			<td align="left"><LABEL>End Date <font color="Red">*</font></LABEL></td>
			<td>
				<input type="text" name="txtEdate" id="txtEdate" size="8" value="<?php echo $_GET['edt'];?>">
			</td>
		</tr>
		<tr>	
			<td align="left"><LABEL >Select SRC <font color="Red">*</font></LABEL></td>
			<td align=left>
				<?php 	
					$extn = "Agent/".trim($_REQUEST['ae']);
					$stdt = $_REQUEST['dt']." 00:00:00";
					$eddt = $_REQUEST['edt']." 23:59:59";
					/*$res = mysql_query("select DISTINCT src from cdr where dst='$extn' AND UNIX_TIMESTAMP(calldate)>=UNIX_TIMESTAMP('$stdt"." 00:00:00"."') AND UNIX_TIMESTAMP(calldate)<=UNIX_TIMESTAMP('$eddt"." 23:59:59"."')",$connection_cdr) or die();*/
					//echo "SELECT src FROM cdr WHERE 1 AND dstchannel = '$extn' AND lastapp = 'Queue' AND (UNIX_TIMESTAMP(calldate) >= '".strtotime($stdt)."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($eddt)."') GROUP BY src";
					mysql_select_db('cdr');
					$res = mysql_query("SELECT src FROM cdr WHERE 1 AND dstchannel = '$extn' AND lastapp = 'Queue' AND (UNIX_TIMESTAMP(calldate) >= '".strtotime($stdt)."' AND UNIX_TIMESTAMP(calldate) <= '".strtotime($eddt)."') GROUP BY src");
				?>
				<select name=srcdst id="srcdst" onchange="displayFiles()"  style="width:120Px;" class=NormalFont>
					<option value="-1">-Select Number-</option>
				<?php while($row = mysql_fetch_array($res))
				      {		
						if(trim($row['src'])!='')
						{
							if($_REQUEST['srcdst'] == $row['src'])
								echo "<option value='".$row['src']."' selected>".$row['src']."</option>";
							else 
								echo "<option value='".$row['src']."'>".$row['src']."</option>";
						}
				      } ?>
				</select>
			</td>
			<td><input type='button' id='Search' value='Search' class=NormalButton onclick="javascript:frmSubmit();"></td>
			<td>&nbsp;</td>
		</tr>
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr>
			<td align="left"><b>Extn To Hear:</b></td>
			<td align="left"><input type="text" name="hextn" id="hextn" value="<?php echo $_REQUEST['hextn'];?>" maxlength="12" class="NormalFont" style="width:60px;" /></td>
			<td colspan="2">&nbsp;</td>
		</tr>
		</table>
			<div id="extensionsNew" name="extensionsNew" style=" height:auto; width:100%; overflow:auto;margin:auto;padding-top:10px; ">
			<table border="0" width="98%" name="results" id="results" align="center">
				<thead>			
					<tr align="left" class="headings" bgcolor="#ff9900">
						<th width="50%" align="center">Files</th>
						<!--<th width="25%" align="center">Record Length</th>-->
						<th width="25%" align="center">Download</th>
					</tr>
				</thead>
				<tbody>
					<tr align="left" class="NormalFontings">
						<td style="font-size:12px;font-weight:bold;"> 
												
						</td>
					</tr>
					<?php 	
						if($_GET['act']=="submit")
						{
						//	$sshConnection = ssh2_connect("202")
							//$filesArr = @scandir("/var/spool/asterisk/monitor");
							for($i=strtotime($stdt);$i<=strtotime($eddt);$i=$i+86400)
							{
								$searchStr = $_REQUEST['Qn']."-".$_REQUEST['srcdst']."-".date('Ymd',$i)."-*";
								$var = exec("ls /var/spool/asterisk/monitor/$searchStr",$varArr);
								for($m=0;$m<count($varArr);$m++)
								{
									$fileName = str_replace('/var/spool/asterisk/monitor/','',$varArr[$m]);
									echo "<table width='98%'>
										  <tr>
											<td width='50%' align='center'><a href='#' onclick=\"playFile('$fileName')\">$fileName</a></td>";
								
									echo "  <!--<td width='25%' align='center'>".$length."</td>-->
											<td width='25%' align='center'><a href='download.php?filename=$fileName&mode=r'>Click Here!</a></td>
										  </tr>	
									      </table>";
								}
								$searchStr = "";
								$varArr = array();
							}
								
							/*for($i=2;$i<count($filesArr);$i++)
							{
								$finddate = explode("-",$filesArr[$i]);
								$nowsearchdate = substr($finddate[2],0,4)."-".substr($finddate[2],4,2)."-".substr($finddate[2],6,2)." ".substr($finddate[3],0,2).":".substr($finddate[3],2,2)/*.":".substr($finddate[3],4,2)*/;
								//echo "select billsec from cdr WHERE calldate like '%$nowsearchdate%' AND dstchannel='Agent/".$_REQUEST['ae']."' AND src='".$_REQUEST['srcdst']."'";
								/*$billsec = mysql_query("select billsec from cdr WHERE calldate like '%$nowsearchdate%' AND dstchannel='Agent/".$_REQUEST['ae']."' AND src='".$_REQUEST['srcdst']."'",$connection_cdr);
								$billArr = mysql_fetch_row($billsec);
								$length = converthours($billArr[0]);
								$file = $finddate[0]."-".$finddate[1]."-".$finddate[2]/*."-".$finddate[3]*/;
								/*$count = "0";
								if(in_array($file,$cdrfile))
								{
									$count = $count  +1;									
									echo "<table width='98%'>
										  <tr>
											<td width='50%' align='center'><a href='#' onclick=\"playFile('$filesArr[$i]')\">$filesArr[$i]</a></td>";
								
									echo "  <!--<td width='25%' align='center'>".$length."</td>-->
											<td width='25%' align='center'><a href='download.php?filename=$filesArr[$i]&mode=r'>Click Here!</a></td>
										  </tr>	
									      </table>";	
								}
							}*/ 
							/*if($count=="0")
							{
								echo "<table width='98%'>
										  <tr>
											<td colspan=4 align='center'>No record match.</td>
										</tr>	
									      </table>";
							}*/
						}
					?>
				</tbody>
			</table>
		</div>

<script>
loadcal();
</script>



