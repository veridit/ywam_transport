<?
		include("inc_cnt_02.php");
		
function fn_GET_MYSQL_TIMEZONE(){
		$rsTIME	=	mysql_query("SELECT @@global.time_zone, @@session.time_zone");
		$rowTIME	=	mysql_fetch_array($rsTIME);
		return $rowTIME['@@session.time_zone'];
		//print("GLOBAL=".$rowTIME['@@global.time_zone']."<br />");
		//print("SESSION=".$rowTIME['@@session.time_zone']."<br />");
}

function fn_SET_MYSQL_TIMEZONE(){
	mysql_query("set time_zone = '-10:00'");
}

if(fn_GET_MYSQL_TIMEZONE()=="SYSTEM") {fn_SET_MYSQL_TIMEZONE();}
?>
