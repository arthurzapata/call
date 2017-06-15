<%
dim sMsg
If Not request("inpNewFolderName")="" Then
  sFolder = request("inpCurrFolder") & "\" & request("inpNewFolderName")

  Set fso = Server.CreateObject("Scripting.FileSystemObject")
  If fso.FolderExists(sFolder) Then
    sMsg = "<script>document.write(getTxt('Folder already exists.'))</script>"
  Else
    on error resume next
    fso.CreateFolder(sFolder)
    if err then
      sMsg = "<script>document.write(getTxt('Invalid input.'))</script>"
    else
      sMsg = "<script>document.write(getTxt('Folder created.'))</script>"
    end if
  End If
  Set fso = Nothing
End If
%>
<base target="_self">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<script>
  var sLang=parent.oUtil.langDir;
  document.write("<scr"+"ipt src='language/"+sLang+"/foldernew.js'></scr"+"ipt>");
</script>
<script>writeTitle()</script>
<script>
function doPreSubmit()
  {
  var Form1 = document.forms.Form1;
  Form1.elements.inpCurrFolder.value=(opener?opener:openerWin).document.getElementById("selCurrFolder").value;

  if(Form1.elements.inpNewFolderName.value=="")
    {
    alert(getTxt("Invalid input."));
    return false;
    }
  return true;
  }
function doSubmit()
  {
  if(doPreSubmit())document.forms.Form1.submit()
  }
</script>
</head>
<body onload="loadTxt()" style="overflow:hidden;margin:0;">

<table width=100% height=100% align=center style="" cellpadding=0 cellspacing=0>
<tr>
<td valign=top style="padding-top:5px;padding-left:15px;padding-right:15px;padding-bottom:12px;height:100%">

<form method=post action="foldernew.asp" onsubmit="doPreSubmit()" name="Form1" id="Form1">
  <br>
  <input type="hidden" id="inpCurrFolder" name="inpCurrFolder">
  <span id="txtLang">New Folder Name</span>: <br>
  <input type="text" id="inpNewFolderName" name="inpNewFolderName" class="inpTxt" size=38>
  <div><b><%response.Write sMsg%>&nbsp;</b></div>
</form>

</td>
</tr>
<tr>
<td class="dialogFooter" align=right>
  <table cellpadding="1" cellspacing="0">
    <tr>
    <td>
      <input style="width:135" type="button" name="btnCloseAndRefresh" id="btnCloseAndRefresh" value="close & refresh" onclick="(opener?opener:openerWin).changeFolder();self.close();" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">&nbsp;<input name="btnCreate" id="btnCreate" type="button" onclick="doSubmit()" value="create" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">
    </td>
    </tr>
  </table>
</td>
</tr>
</table>

</body>
</html>