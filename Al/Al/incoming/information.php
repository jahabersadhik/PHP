<form method=post name="callCustomerAccounts"  action="agentManualCallAccountInformationProcess.php">
<table width=100% class=normalfont cellpadding=0 border=0>			
<tr align=right valign="top">
			<td colspan=2>&nbsp;</td>
</tr>
<tr> 
	 <td align=left> 
	 Name
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="name" id="name" />
  	 </td>
</tr> 
<tr> 
	 <td align=left> 
	 Address1
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="add1" id="add1" />
  	 </td>
</tr>
<tr> 
	 <td align=left> 
	 Address2
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="add2" id="add2" />
  	 </td>
</tr>
<tr> 
	 <td align=left> 
	 City
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="city" id="city" />
  	 </td>
</tr>
<tr> 
	 <td align=left> 
	 State
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="state" id="state" />
  	 </td>
</tr>
<tr> 
	 <td align=left> 
	  Phone
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="phone" id="phone" />
  	 </td>
</tr>
<tr> 
	 <td align=left> 
	  Email
  	 </td>
  	 <td class="NormalFont">
  	 <input type="text" name="email" id="email" />
  	 </td>
</tr> 
<tr> 
	 <td align=left> 
	  Query
  	 </td>
  	 <td class="NormalFont">
  	 	<textarea name="query" id="query"></textarea>
  	 </td>
</tr>
<tr> 
	 <td align=left> 
	  Comments
  	 </td>
  	 <td class="NormalFont">
      	 <textarea name="comments" id="comments"></textarea>
  	 </td>
		 <td width="100" class="NormalFont">
	 </td>
</tr>
<tr> 
	 <td align=left> 
	 After Submiting
  	 </td>
  	 <td>
  	 <select name=selAfterSubmitting  style="width:170Px;" class="NormalTextBox">
		<option value="0" >-Select After Submiting-</option>
		<option value="loggedIn">Logged In</option>
		<option value="Break">Break</option>
		<option value="loggedOut">Logged Out</option>
	 </select>
  	 </td>
</tr>
<tr height=30% align="center">								
	<td colspan=2 align=center>
	<input type=submit class="NormalButton" value="Submit " id=button6 name=button6 onclick="Check();">&nbsp;
	<input type=button class="NormalButton" value="Close " id=button6 name=button6 onclick="window.close();">
	</td>
</tr>	

</table>
</form>

<script language="javascript">
function Check()
{ 
	if (IsEmpty(callCustomerAccounts.txtADesc.value)){
		alert("Agent comments cannot be empty");
		callCustomerAccounts.txtADesc.focus();
	} else if(IsEmpty(callCustomerAccounts.txtCDesc.value)){
		alert("Client comments cannot be empty");
		callCustomerAccounts.txtCDesc.focus();
	} else{ 
		callCustomerAccounts.flag.value = "1";
		callCustomerAccounts.action = "./agentManualCallAccountInformationProcess.php";
		callCustomerAccounts.submit();
	}
}
function IsEmpty(MyValue)
{
	if (MyValue.replace(/\s+/g,"").length<=0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
</script>
