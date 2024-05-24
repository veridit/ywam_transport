<?php
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	$sSQL	=	"UPDATE tbl_log SET logout_datetime = '".date('Y-m-d H:i:s')."' WHERE user_id = ".$_SESSION['User_ID']." AND logout_datetime IS NULL";
	
	$rsLOG	=	mysql_query($sSQL) or die(mysql_error());
	
	
	unset($_SESSION['User_ID']);
	unset($_SESSION['User_Name']);
	unset($_SESSION['User_Group']);
	unset($_SESSION['load_counter']);
	
	$iMemberLogout=session_destroy();
	
	if($iMemberLogout)
		header('location:login.php');
	else
		echo "Unable To Logged You Out";
		
		
?>
