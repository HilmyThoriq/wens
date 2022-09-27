<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

if(!$_SESSION[member_id]){
	error("비정상");
	exit;
}


$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/koweb_member/";

if($_FILES["file_1"][name]){
	$file_1 = upload_file($dir, $_FILES["file_1"][tmp_name], $_FILES["file_1"][name]);
}

//  ID 정리
$reg_date = date("Y-m-d H:i:s");
//$phone = $phone1."-".$phone2."-".$phone3;
//$email = $email1."@".$email3;
//$birthday = $birthday1."-".$birthday2."-".$birthday3;

//핸드폰 번호 자르기
function format_phone($phone){
    $phone = preg_replace("/[^0-9]/", "", $phone);
    $length = strlen($phone);

    switch($length){
      case 11 :
          return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
          break;
      case 10:
          return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
          break;
      default :
          return $phone;
          break;
    }
}
$phone = format_phone($phone);
// 체크박스 분류
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

if($mode == "write_proc"){
	if(isblank($password)) error("비밀번호를 입력해주세요");
	if(isblank($id)) error("아이디를 입력해주세요");

	$id_enc = hash("sha256", $id);
	$auth_token = hash("sha256", $id.$password);
	$password = hash("sha256", $password);

	//  ID 중복 체크
	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE id='$id'"));
	if ($check[0])	error("중복된  ID 입니다.");

	// 정보 입력

	/*$insert_query = "INSERT INTO koweb_member SET 
									depth='$depth', 
									CI='$_SESSION[CI]',
									DI='$_SESSION[DI]',
									name='$name',
									id='$id',
									password='$password',
									id_enc='$id_enc',
									phone='$phone',
									email='$email',
									firt_name='$firt_name',
									last_name='$last_name',
									Rol='$Rol',
									tel='$tel',
									auth_token='$auth_token',
									auth_level='$level',
									birthday='$birthday',
									gender='$gender',
									zip='$zip',
									address1='$address1',
									address2='$address2',
									is_admin='$is_admin',
									category='$category',
									title='$title',
									tag_type='$tag_type',
									comments='$comments',
									etc='$etc',
									company='$company',
									company_tel='$company_tel',
									company_zip='$company_zip',
									comapany_address1='$comapany_address1',
									comapany_address2='$comapany_address2',
									notice='$notice',
									secret='$secret',
									file_type='$file_type',
									file_1='$file_1',
									reg_date = '$reg_date',
									ip='$ip'";

	echo $insert_query;
	exit;

	mysql_query($insert_query);*/


	@mysql_query("INSERT INTO koweb_member VALUES('', '', '$name', '$dept', '', '$dept', '$id', '$password', '$id_enc', '$auth_token', '$level', '$phone', '$tel', '$email', '$birthday', '$gender', '$zip', '$address1', '$address2', '$is_admin', '$company', '$company_tel', '$company_zip', '$company_address1', '$company_address2', '$reg_date', 'Y', '$ip', '$first_name', '$last_name', '$Rol', '$group_professors', '$group_time_researchers', '$group_part_researchers', '$group_alumnis', '$office', '$last_company', '$graduate_date', '$Interest', '$last_education', '$last_job', '$file_1', '$comments', '$join_date')");


	$alert_txt = "등록";

	setHistory("관리자 회원관리", $id, "$id 회원정보 신규등록");


} else if ($mode == "modify_proc"){

//	if(isblank($password)) error("비밀번호를 입력해주세요");
	if(isblank($id)) error("아이디를 입력해주세요");

	if($password){
		$password = hash("sha256", $password);
		$auth_token = hash("sha256", $id.$password);
		$add_query = "password='$password', auth_token='$auth_token',";
	}

	$query = "SELECT * FROM koweb_member WHERE no='$no'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if(!$row[0]) {
		error("비정상적인 접근입니다.");
		exit;
	}

	//@mysql_query("UPDATE koweb_member  SET name='$name', dept='$dept', $add_query auth_level='$level', phone='$phone', tel='$tel', email='$email', birthday='$birthday', gender= '$gender' , zip='$zip', address1='$address1', address2='$address2', is_admin='$is_admin', company='$company', company_tel='$company_tel', company_zip='$company_zip', company_address1 = '$company_address1', company_address2 = '$company_address2', state='$state', reg_date='$reg_date' WHERE no='$no'");

	if($file_1){
		$file_query = " file_1='$file_1', ";
	}


	$update_query = "UPDATE 
									koweb_member 
								SET 
									name='$name', 
									dept='$dept', 
									$add_query auth_level='$level', 
									phone='$phone', 
									tel='$tel', 
									email='$email', 
									birthday='$birthday', 
									gender= '$gender' , 
									zip='$zip', 
									address1='$address1', 
									address2='$address2', 
									is_admin='$is_admin', 
									company='$company', 
									company_tel='$company_tel', 
									company_zip='$company_zip',
									company_address1 = '$company_address1', 
									company_address2 = '$company_address2', 
									state='$state', 
									reg_date='$reg_date',
									first_name='$first_name',
									last_name='$last_name',
									Rol='$Rol',
									group_professor='$group_professor',
									group_time_researcher='$group_time_researcher',
									group_part_researcher='$group_part_researcher',
									group_alumni='$group_alumni',
									office='$office',
									last_company='$last_company',
									graduate_date='$graduate_date',
									Interest='$Interest',
									last_education='$last_education',
									last_job='$last_job',
									$file_query
									comments='$comments',
									join_date='$join_date' 
								WHERE 
									no='$no'";


	mysql_query($update_query);
	

	$alert_txt = "수정";

	setHistory("관리자 회원관리", $row[id], "$row[id] 회원정보 수정");

} else if ($mode == "apply_proc"){

	foreach($check_apply as $value){

		@mysql_query("UPDATE koweb_member  SET auth_level = '$site[member_level]' WHERE no='$value'");
	} 

	$alert_txt = "승인";

} else if ($mode == "delete") {
	if($_SESSION[auth_level] != "1"){
		error("비정상적인 접근입니다. 관리자만 회원삭제가 가능합니다.");
		exit;
	} 

	
	$query = "SELECT * FROM koweb_member WHERE no='$no'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if(!$row[0]) {
		error("비정상적인 접근입니다.");
		exit;
	}

	setHistory("관리자 회원관리", $row[id], "$row[id] 회원정보 삭제");

	@mysql_query("UPDATE koweb_member SET state = 'N' WHERE no='$no'");
	//@mysql_query("DELETE FROM koweb_member WHERE no = '$no' LIMIT 1");
	$alert_txt = "삭제";
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
if($return_no != ""){
?>
	<script type="text/javascript">
	alert("<?=$alert_txt?> 되었습니다.");
	location.href = "?type=setting&core_id=setting&core=manager_setting&manager_type=dept&mode=view&no=<?=$return_no?>";
	</script>
<? } else { ?>
	<script type="text/javascript">
	alert("<?=$alert_txt?> 되었습니다.");
	location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
	</script>
<? } ?>