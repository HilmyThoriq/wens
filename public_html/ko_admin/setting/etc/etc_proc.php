<? 
// 프로그램 ID 정리
$reg_date = date("Y-m-d H:i:s");

if($mode == "write_proc"){

	// 프로그램 ID 중복 체크
	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_etc_config WHERE id='$id'"));
	if ($check[0])	error("중복된 프로그램 ID 입니다.");

	// 관리자 폴더 생성
	mkdir($_SERVER[DOCUMENT_ROOT] . "/ko_admin/etc/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/ko_admin/etc/" . $id, 0711);

	mkdir($_SERVER[DOCUMENT_ROOT] . "/upload/etc/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/upload/etc/" . $id, 0711);

	// 프로그램 정보 입력
	@mysql_query("INSERT INTO koweb_etc_config VALUES('', '$id', '$title', '$table', '$user_url', '$admin_url', '$is_member', '$is_admin', '$reg_date', '$state')");
	
	$alert_txt = "등록";

} else if ($mode == "modify_proc"){

	@mysql_query("UPDATE koweb_etc_config  SET title='$title', user_url='$user_url', admin_url= '$admin_url' WHERE no='$no'");

	$alert_txt = "수정";
} else {
	@mysql_query("DELETE FROM koweb_etc_config WHERE no = '$no' LIMIT 1");
	$alert_txt = "삭제";
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
?>
<script type="text/javascript">
alert("<?=$alert_txt?> 되었습니다.");
location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
</script>