<?

 
$num = isnum($num) ;
$page = isnum($page); 




$tb = checkword($tb); 
$mode = checkword($mode);
$sw = checkword($sw) ; 
$sc = checkword($sc) ; 
$st = checkword($st) ; 
$sn = checkword($sn) ;  

 $qry = "select *    from `daegu_board_type`   where tb = '$tb' order by idx asc";
 $board_type_result =  dbarray( $qry  ) ;

if ($board_type_result){

 
 
  $tb					= checkword($board_type_result[tb]);
  $board_name	= checkword($board_type_result[board_name]);
  $top_file			= checkword($board_type_result[top_file]);
  $bottom_file		= checkword($board_type_result[bottom_file]);
  $board_skin		= checkword($board_type_result[board_skin]);
  $board_type1	= checkword($board_type_result[board_type1]);
  $board_type2	= checkword($board_type_result[board_type2]);
  $board_type3	= checkword($board_type_result[board_type3]);
  $board_type4	= checkword($board_type_result[board_type4]);
  $board_type5	= checkword($board_type_result[board_type5]);
  $board_type6	= checkword($board_type_result[board_type6]);
  $board_type7	= checkword($board_type_result[board_type7]);
  $board_type8	= checkword($board_type_result[board_type8]);
  $board_type9	= checkword($board_type_result[board_type9]);
  $board_write1 	= isnum($board_type_result[board_write1]); // 목록 보기
  $board_write2 	= isnum($board_type_result[board_write2]); // 글읽기 
  $board_write3 	=  isnum($board_type_result[board_write3]); // 글쓰기 
  $board_write4 	=  isnum($board_type_result[board_write4]); // 답변 
  $board_write5 	=  isnum($board_type_result[board_write5]);  //코멘트 쓰기   
   $title_array 	= explode("|", checkword( $board_type_result[title_array])); 
 



   if ( $board_type8 ==1 ) {    $secret_true =  1 ; $secret =1 ;  }
   if (!$board_skin) {$board_skin="skin_a";}

}
else{

echo "권한이 없습니다";
exit ; 

}



$sub_page_url = "?sub=".$sub ."&style=" ;



$board_bnt_write1  =  "<a href='".$sub_page_url."list' class='bbs_gray_btn'>목록보기</a>";
$board_bnt_write3_1  =  "<a href='".$sub_page_url."form' class='bbs_color_btn'>글쓰기</a>";
$board_bnt_write3_2  =  "<a href='".$sub_page_url."form&mode=edit&num=$num' class='bbs_gray_btn'>글수정</a>";
$board_bnt_write4  =  "<a href='".$sub_page_url."form&&mode=reply&num=$num' class='bbs_gray_btn'>답변</a>";

$board_bnt_write5  =  "<a href='".$sub_page_url."del&sw=$sw&sn=$sn&st=$st&sc=$sc&num=$num&page=$page' class='bbs_gray_btn'>삭제</a>";

$designs_sub =  $board_type5 ; 



 $board_bnt_form1  =  "<input type=\"button\" value=\"글쓰기\" class=\"button\" onclick=\"b_submit();\">";
 $board_bnt_form2  =  "<input type=\"button\" value=\"글수정\" class=\"button\" onclick=\"b_submit();\">";
 $board_bnt_form4  =  "<input type=\"button\" value=\"삭제\" class=\"button\" onclick=\"submit();\">";
 $board_bnt_form3  =  "<input type=\"button\" value=\"취소\" class=\"button\"   onclick=\"location.href='".$sub_page_url ."list'\">";
 
 $board_bnt_form4  =  "<input type=\"button\" value=\"삭제\" class=\"button\" onclick=\"submit_ok();\">";
 $board_bnt_form5  =  "<input type=\"button\" value=\"취소\" class=\"button\"  onclick=\"history.back();\" >";

 $board_bnt_form6  =  "<input type=\"button\" value=\"확인\" class=\"button\" onclick=\"submit_ok();\">";







function   acting_member( $board_write){

Global $board_write1;
Global $board_write2;
Global $board_write3;
Global $board_write4;
Global $board_write5;
Global $tb;
Global  $site_level	;  
if ( $board_write ==1 ){if  ($board_write1  <  $site_level ){refresh ("/?data=".time()); exit;} }
if ( $board_write ==2 ){if  ($board_write2  <  $site_level ){refresh ("/?data=".time()); exit;} }
if ( $board_write ==3 ){if  ($board_write3  <  $site_level ){refresh ("/?data=".time()); exit;} }
if ( $board_write ==4 ){if  ($board_write4  <  $site_level ){refresh ("/?data=".time()); exit;} }
if ( $board_write ==5 ){if  ($board_write5  <  $site_level ){refresh ("/?data=".time()); exit;} }




}


function betweennow($datetime )
{//2003-02-19 11:32:15
	$now = time();
	$timearr= explode(":",substr($datetime,11,8));
	$dayarr	= explode("-",substr($datetime,0,10));

	$mktime = mktime($timearr[0],$timearr[1],$timearr[2],$dayarr[1],$dayarr[2],$dayarr[0]);
 

    return  intval(($now  -$mktime) / 60 / 60/24 )  ;
}


 ?> 