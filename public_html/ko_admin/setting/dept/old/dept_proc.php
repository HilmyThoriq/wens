<?
//  ID 정리
$reg_date = date("Y-m-d H:i:s");
//$birthday = $birthday1."-".$birthday2."-".$birthday3;


if($mode == "write_proc"){
//	if(isblank($password)) error("비밀번호를 입력해주세요");
//	if(isblank($id)) error("아이디를 입력해주세요");

//	$id_enc = hash("sha256", $id);
//	$auth_token = hash("sha256", $id.$password);
//	$password = hash("sha256", $password);

	//  ID 중복 체크
//	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE id='$id'"));
//	if ($check[0])	error("중복된  ID 입니다.");

	// 정보 입력
	@mysql_query("INSERT INTO koweb_dept VALUES('', '$dept_code', '$dept', '$memo', '$reg_date', '$state', '$ip')");

	$alert_txt = "등록";

} else if ($mode == "modify_proc"){

//	if(isblank($password)) error("비밀번호를 입력해주세요");
//	if(isblank($id)) error("아이디를 입력해주세요");

	@mysql_query("UPDATE koweb_dept SET dept_code='$dept_code', dept='$dept', memo='$memo', reg_date='$reg_date', state='$state', ip='$ip' WHERE no='$no'");

	$alert_txt = "수정";

} else {
	@mysql_query("DELETE FROM koweb_dept WHERE no = '$no' LIMIT 1");
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