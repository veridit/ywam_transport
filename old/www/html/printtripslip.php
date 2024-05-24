<?
	include('inc_connection.php');
	include('inc_function.php');
	
	$sMessage		=	"";
	$iRESERVATION_ID	=	0;
	$sPRINT_SLIP_TEXT	=	"";
	$sPageAction		=	"";
	$bRE_PRINT			=	0;
	
	if(isset($_REQUEST["a"]))	$sPageAction		=	$_REQUEST["a"];
	if(isset($_REQUEST["rep"]) && $_REQUEST["rep"]=="1")	$bRE_PRINT			=	1;
	
	if ($sPageAction	==	"print"){
			if(isset($_REQUEST["id"]))	$iRESERVATION_ID	=	$_REQUEST["id"];
			
			$sPRINT_SLIP_TEXT		=	fn_PRINT_TRIP_SLIP($iRESERVATION_ID, $bRE_PRINT);
		
			if($sPRINT_SLIP_TEXT==""){$sMessage		=	fn_Print_MSG_BOX("please select a valid Trip to print", "C_ERROR");}
	}
	
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/style.css">

</head>
<body style="margin: 0px;background-color:#fff;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		
        <td>
        	<table border="0" cellspacing="0" cellpadding="0" width="700">
				
               
                <tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
						
				<tr><td><p style="text-align:justify;"><? echo $sPRINT_SLIP_TEXT;?></p></td></tr>
								
			</table>
		</td>

     </table>
    </td>
   </tr>
  </table>
 </div>
 <script language="javascript">window.print();</script>
</body>
</html>
