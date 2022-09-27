<?
// 프로그램 ID 정리
$reg_date = date("Y-m-d H:i:s");

foreach($category_tmp as $value){
	if($value){
		$lang_list .= $value ."|";
	}
}

$lang_list = substr($lang_list, 0, -1);



if($mode == "write_proc"){
	// 프로그램 정보 입력
	@mysql_query("INSERT INTO koweb_site_language_list VALUES('', '$lang_list')");


	@mysql_query("INSERT INTO koweb_site_config VALUES('', '$lang', '$site_url', '$title', '$description', '$og_description', '$og_site_name', '$og_title', '$use_member', '$this_url', '$member_level', '$use_member_okey', '$use_namecheck', '$namecheck_key', '$use_naver_login', '$naver_key', '$naver_pass', '$naver_tag','$use_kakao_login','$kakao_key', '$use_sms', '$sms_id', '$sms_key', '$reg_date', '$state', '$page_id', '$facebook_access_token', '$member_agree_content', '$member_agree_content2')");

	$alert_txt = "등록";
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
?>
<script type="text/javascript">
alert("<?=$alert_txt?> 되었습니다.");
location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
</script>
