// JavaScript Document
function fn_draw_ErrMsg(txtErrMsg){
	
	//document.getElementById('Message').style.display='block';
	document.getElementById('Message').innerHTML="<table width='100%'><tr><td class='Err'>"+txtErrMsg+"</td></tr></table>";
}
function validate_field(frmElement, objregExp){
	for (var j=0;j<frmElement.value.length;j++)
		if (!objregExp.test(frmElement.value.charAt(j)))
			return false;
	return true;
}
function Trim(s){											  
		while ((s.substring(0,1) == ' ') || (s.substring(0,1) == '\n') || (s.substring(0,1) == '\r'))
		{
				s = s.substring(1,s.length);
		}
		while ((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\n') || (s.substring(s.length-1,s.length) == '\r'))
		{
				s = s.substring(0,s.length-1);
		}
		return s;
}
function fn_char_Counter (field, countfield, maxlimit)
{

	if (field.value.length > maxlimit)
	
	field.value = field.value.substring(0, maxlimit);
	
	else
	
	countfield.value = maxlimit - field.value.length;

}

function fn_CHANGE_TEXT_BOX_COLOR(fld, sBGColor, sFGColor){

	fld.style.backgroundColor	=	sBGColor;
	fld.style.color				=	sFGColor;
	
}

function fn_SHOW_POPUP(sPOPUP_MSG){
		centerPopup();
		loadPopup();
		document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'notice_heading>Error!</h1><br /><br />"+sPOPUP_MSG;
		
}

function fn_PRINT_TRIP_SLIP(iResID, bRepeat){
	if(bRepeat==1)
	var url="printtripslip.php?a=print&rep=1&id="+iResID;
	else
	var url="printtripslip.php?a=print&id="+iResID;
	var myWindow	=	window.open(url,"_blank","height=600, width=800, resizable=no, scrollbars=yes");
}

function fn_LOAD_SUB_REPORT(sRptName){
		$('#list_sub_reports').html("<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>");
		$('#list_sub_reports').html("<iframe id='myFrame' src='"+sRptName+".php' width='949' frameborder='0' onload='adjustMyFrameSize();'>Browser not supportive</iframe>");
		if(sRptName=='list_closed_trips')				fn_CHANGE_PG_NAME('CLOSED TRIPS');
		if(sRptName=='list_pending_report')				fn_CHANGE_PG_NAME('OPEN TRIPS (pending)');
		if(sRptName=='list_abandon_trips')				fn_CHANGE_PG_NAME('ABANDONED TRIPS');
		if(sRptName=='list_deleted_trips')				fn_CHANGE_PG_NAME('DELETED TRIPS');
		if(sRptName=='list_driver_deleted_trips')		fn_CHANGE_PG_NAME('DRIVER CANCELLED TRIPS');
}
function fn_CHANGE_PG_NAME(sPageName){
	parent.document.getElementById('page-heading').innerHTML = sPageName;
	parent.document.title = sPageName;
	$('#page-heading').html(sPageName);
}
function getElement(aID){
        return (document.getElementById) ?       document.getElementById(aID) : document.all[aID];
}

function getIFrameDocument(aID){ 
        var rv = null; 
        var frame=getElement(aID);
        // if contentDocument exists, W3C compliant (e.g. Mozilla) 
        if (frame.contentDocument)
            rv = frame.contentDocument;
        else // bad Internet Explorer  ;)
            rv = document.frames[aID].document;
        return rv;
}

function adjustMyFrameSize(){
        var frame = getElement("myFrame");
        var frameDoc = getIFrameDocument("myFrame");
        frame.height = frameDoc.body.offsetHeight+50;
		//alert(frame.height);
}

function fn_RPT_DT_SEARCH(){
	if(document.frm1.txtstartdate.value!="" && document.frm1.txtenddate.value!=""){
		if(fn_COMPARE_DATES(document.frm1.txtstartdate.value, document.frm1.txtenddate.value)){
			document.frm1.action.value='search';
			document.frm1.pg.value	=	'1';
			document.frm1.submit();
		}
	}else{
		document.frm1.action.value='search';
		document.frm1.pg.value	=	'1';
		document.frm1.submit();
	}
}

function fn_COMPARE_DATES(sDATE_1, sDATE_2){
	var sErrMessage='';
	var iErrCounter=0;
	
	var dt1  = parseInt(sDATE_1.substring(3,5),10);
    var mon1 = parseInt(sDATE_1.substring(0,2),10);
    var yr1  = parseInt(sDATE_1.substring(6,10),10);
	var first = new Date(yr1, mon1, dt1);
	
	var dt2  = parseInt(sDATE_2.substring(3,5),10);
    var mon2 = parseInt(sDATE_2.substring(0,2),10);
    var yr2  = parseInt(sDATE_2.substring(6,10),10);
	
	var second = new Date(yr2, mon2, dt2);
	


	if( (first.getTime() > second.getTime()))
	{
		sErrMessage=sErrMessage+'<li>From Date must be less than or equal to To Date';
		iErrCounter++;
	}
	
	if (iErrCounter >0){
		fn_draw_ErrMsg(sErrMessage);
		return false;
	}
	else
		return true;
}

function validateNumber(event) {
	var key = window.event ? event.keyCode : event.which;
//alert(key);
	if(event.keyCode == 13){	
		return true;
	}else if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || (event.keyCode > 95 && event.keyCode < 106)) {
		return true;
	}else if ( key < 48 || key > 57 ) {
		return false;
	}else return true;
}