<html>
<title><?=$data['header'];?></title>
<style>
 body,html {background:white;text-align:center;color:#666;font:14px;font-family: arial,Helvetica;}
 .email-body {width:600px;border: 1px solid #999999;margin:30px auto;}
 h1 {font-size:20px;font-weight:bold;color:#f7831e;text-align:center;}
 a {text-decoration:none;font-weight:bold;color:#f7831e;}
 .header {width:100%;background:black;}
 .header .tab-header {color:white;width:100%;}
 .msg {padding:20px;}
 .footer {padding:10px 20px;border-top:1px solid black;}
</style>
<body>
  <table class="email-body" cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td class="header">	
	  <table class="tab-header" cellspacing="10" cellpadding="10">
	   <tr>
	     <td>
		 jakieś dane
		 </td>
	     <td align="right">
		   <img src="cid:<?=$data['cid_logo'];?>" alt="" />
		 </td>
	   </tr>
	  </table>
	</td>
   </tr>
   <tr>
    <td class="msg">   
	<?php
	echo $data['msg'];
	?>
	</td>
	</tr>
	<tr>
    <td class="footer">   
       stopka
	</td>
	</tr>
</table>
</body>
</html>