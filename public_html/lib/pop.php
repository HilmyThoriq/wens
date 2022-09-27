<SCRIPT LANGUAGE="JavaScript">
<!--

	$(document).ready(function(){

 

  


<?
//=======       popup 창 설정 ==========================================================

$popup_qry="select *from daegu_popup where bpopup='y'   and  sday <= '".date("Ymd")."'  and  eday >=  '".date("Ymd")."'    ";
 
$popup_result=dbquery($popup_qry);
$to_day=date("ymd"); //오늘날짜 
while($popup_row=mysql_fetch_array($popup_result))
{
 	if(${popup.$popup_row[idx]}!="no" ){ //쿠기값없을경우
 		 
 
			$popup_top =	isnum($popup_row[popup_top] );
			$popup_left = isnum(	$popup_row[popup_left] ); 
			$popup_height	=$popup_row[height] ; 
			$popup_width	=$popup_row[width];
			$part =isnum( $popup_row[code]);

 
		if($part ==0 ){
			if($popup_row[app]  ){
			  echo" window.open(\"popup.php?idx=$popup_row[idx]\",\"popuppage$popup_row[idx]\",\"scrollbars=no,left=$popup_left,top=$popup_top,width=$popup_width,height=$popup_height\"); ";
			}else{ 
			  echo " window.open(\"popup.php?idx=$popup_row[idx]\",\"popuppage$popup_row[idx]\",\"scrollbars=yes,left=$popup_left,top=$popup_top,width=$popup_width,height=$popup_height\"); ";
			} 
		}else{

			echo  'popLayer('.$popup_row[idx] .');  ' ; 

				

		
		
		
		}
	 
	}
}

//=====================================================================================
?>


 


 	}); 
	 


//-->
</SCRIPT>