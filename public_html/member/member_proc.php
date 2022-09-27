<?
//  ID 정리
$reg_date = date("Y-m-d H:i:s");
$phone = $phone1."-".$phone2."-".$phone3;
if($email2) $email3 = $email2;
$email = $email1."@".$email3;
$email = preg_replace("/\s+/", "", $email);
$id_enc = hash("sha256", $id);

if($password){
	$password_enc = hash("sha256", $password);
	$auth_token = hash("sha256", $id.$password);
}
$group_professor_=$group_professor;
	$group_professor = "";
	foreach($group_professor_ as $value){
		$group_professor .= $value."^";
	}

$group_professors=substr($group_professor, 0, -1);

$group_time_researcher_=$group_time_researcher;
	$group_time_researcher = "";
	foreach($group_time_researcher_ as $value){
		$group_time_researcher .= $value."^";
	}

$group_time_researchers=substr($group_time_researcher, 0, -1);

$group_part_researcher_=$group_part_researcher;
	$group_part_researcher = "";
	foreach($group_part_researcher_ as $value){
		$group_part_researcher .= $value."^";
	}

$group_part_researchers=substr($group_part_researcher, 0, -1);

$group_alumni_=$group_alumni;
	$group_alumni = "";
	foreach($group_alumni_ as $value){
		$group_alumni .= $value."^";
	}

$group_alumnis=substr($group_alumni, 0, -1);
//회원가입
if($mode == "join_proc"){
	if($site[use_member_okey] == "Y"){
		$level = "99";
	} else {
		$level = $site[member_level];
	}
	//  ID 중복 체크
	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE id='$id'"));
	if ($check[0])	error("Duplicate ID.");

	$check2 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE email='$email'"));
	if ($check2[0])	error("Duplicate Email.");

	$check3 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE phone='$phone'"));
	if ($check3[0])	error("Duplicate Phone number.");

	$type = "web";

	// 정보 입력
	@mysql_query("INSERT INTO koweb_member VALUES('','$type', '$name', '$dept', '$_SESSION[CI]', '$_SESSION[DI]', '$id', '$password_enc', '$id_enc', '$auth_token', '$level', '$phone', '$tel', '$email', '$birthday', '$gender', '$zip', '$address1', '$address2', '$is_admin', '$company', '$company_tel', '$company_zip', '$company_address1', '$company_address2', '$reg_date', 'Y', '$ip'  )");

	$alert_txt = "Submit";

} else if ($mode == "modify_proc"){

	if($password){
		$auth_token = hash("sha256", $_SESSION[member_id].$password);
		$add_query = "password='$password_enc', auth_token='$auth_token',";
	}

	if($change_mail == "Y"){
		$check2 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE email='$email',"));
		if ($check2[0])	error("Duplicate Email.");
		else $add_query_email = "email='$email',";
	}
	if($change_phone == "Y"){
		$check3 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE phone='$phone',"));
		if ($check3[0])	error("Duplicate Phone number.");
		else $add_query_phone = "phone='$phone',";
	}


	@mysql_query("UPDATE koweb_member  SET name='$name', dept='$dept', $add_query $add_query_phone tel='$tel', $add_query_email birthday='$birthday', gender= '$gender' , zip='$zip', address1='$address1', address2='$address2', company='$company', company_tel='$company_tel', company_zip='$company_zip', company_address1 = '$company_address1', company_address2 = '$company_address2', reg_date='$reg_date', state='Y', ip='$ip', first_name='$first_name', last_name='$last_name', Rol='$Rol', group_professor='$group_professor', group_time_researcher='$group_time_researcher', group_part_researcher='$group_part_researcher', group_alumni='$group_alumni', office='$office', last_company='$last_company', graduate_date='$graduate_date', Interest='$Interest', last_education='$last_education', last_job='$last_job', file_1='$file_1', comments='$comments' WHERE id='$_SESSION[member_id]'");

	$alert_txt = "modify";


			if($return_url){
				$return_url = rawurldecode($return_url);
			} else {
				$return_url = "/";
			}

			?>
			<script type="text/javascript">
				alert("<?=$alert_txt?>");
				location.href = "<?=$return_url?>";
				</script>
			<?

//로그인
} else if ($mode == "login_proc") {
	if(!$password|| !$id){
		error("Enter Account / passwords.");
		exit;
	}
	$query = "SELECT * FROM koweb_member WHERE id='$id' AND id_enc = '$id_enc' AND password='$password_enc' LIMIT 1";
	//echo $query;
	//exit;
	$result = mysql_query($query);
	$check = mysql_num_rows($result);

	if($check < 1){
		error("Check the Account / passwords.");
		exit;
	} else {
		$row = mysql_fetch_array($result);
		if($row[state] != "Y"){
			error("The ID that has been suspended. Please contact the administrator.");
			exit;
		}
		$_SESSION['auth_admin_ci'] = $row[id];
		$_SESSION['auth_admin_di'] = $row[password];
		$_SESSION['auth_token'] = hash("sha256", $id.$password);
		$_SESSION['auth_level'] = $row[auth_level];
		$_SESSION['member_id'] = $row[id];
		$_SESSION['member_name'] = $row[name];
		$_SESSION['CI'] = $row[CI];
		$_SESSION['DI'] = $row[DI];

	}
	$alert_txt = "Log in";
	?>
		<script type="text/javascript">
			alert("<?=$alert_txt?>");
			location.href = "/";
		</script>
	<?
//회원탈퇴
} else if($mode == "secession") {

	if(isblank($_SESSION[member_id])) error("Please. login");
	@mysql_query("UPDATE koweb_member SET state='N' WHERE id='$_SESSION[member_id]' LIMIT 1");

	setHistory("Membership Withdrawal", "", "$_SESSION[member_id] Membership Withdrawal");


	$alert_txt = "Withdrawal";
	@session_destroy();


} else if($mode == "naver" || $mode == "kakao") {
	include_once $_SERVER['DOCUMENT_ROOT']."/member/{$mode}_callback.php";

	$alert_txt = "Log in";

	//한번더 가져오기
	$result_ = mysql_query($check_);
	$row = mysql_fetch_array($result_);
	$_SESSION['auth_admin_ci'] = $row[id];
	$_SESSION['auth_admin_di'] = $row[password];
	$_SESSION['auth_token'] = hash("sha256", $id.$password);
	$_SESSION['auth_level'] = $row[auth_level];
	$_SESSION['member_id'] = $row[id];
	$_SESSION['member_name'] = $row[name];
	$_SESSION['CI'] = $row[CI];
	$_SESSION['DI'] = $row[DI];
	$_SESSION['login_type'] = $mode;

	if($return_url){
		$return_url = rawurldecode($return_url);
	} else {
		$return_url = "/";
	}

	?>
		<script type="text/javascript">
			alert("<?=$alert_txt?>");
			location.href = "<?=$return_url?>";
		</script>
	<?

} else {
	error("Please use the correct connection path.");
	exit;
}



/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
?>

<script type="text/javascript">
alert("<?=$alert_txt?>");
location.href = "<?=$PHP_SELF?>?mode=login";
</script>
