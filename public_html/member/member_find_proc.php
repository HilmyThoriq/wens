<?
//  ID 정리
$reg_date = date("Y-m-d H:i:s");
$birthday = $birthday1."-".$birthday2."-".$birthday3;

//비밀번호 찾기
if($return_mode == "find_pw"){
	
	//본인인증
	if($site[use_namecheck] == "Y"){
		//TODO
		$check = mysql_fetch_array(mysql_query("SELECT *  FROM koweb_member WHERE CI='$_SESSION[CI]' AND DI = '$_SESSION[DI]' AND state='Y'  LIMIT 1"));
		$check_where = "WHERE CI='$_SESSION[CI]' AND DI='$_SESSION[DI]' AND state = 'Y'"; 

		if (!$check[id])	error("등록된 회원 정보가 없습니다.");
		if (!$check[no])	error("등록된 회원 정보가 없습니다.");

	} else {
		//if (!$find_id)	error("아이디를 입력해 주세요.");
		//if (!$find_name)	error("이름을 입력해 주세요.");
		//if (!$find_phone1)	error("연락처를 입력해 주세요.");
		//if (!$find_phone2)	error("연락처를 입력해 주세요.");
		//if (!$find_phone3)	error("연락처를 입력해 주세요.");
		//if (!$find_email)	error("이메일을 입력해 주세요.");

		//휴대폰
		$find_phone = $find_phone1."-".$find_phone2."-".$find_phone3;
		$id_enc = hash("sha256", $find_id);

		$check = mysql_fetch_array(mysql_query("SELECT *  FROM koweb_member WHERE id='$find_id' AND id_enc = '$id_enc' AND name='$find_name' AND phone = '$find_phone' AND email='$find_email' AND state='Y'  LIMIT 1"));
		$check_where = "WHERE id='$find_id' AND id_enc = '$id_enc' AND name='$find_name' AND phone = '$find_phone' AND email = '$find_email' AND state='Y'"; 

		if (!$check[id])	error("등록된 회원 정보가 없습니다.");
		if (!$check[no])	error("등록된 회원 정보가 없습니다.");
	}

	//임시 비밀번호 생성
	$temp_passwd_word = array("a","b","c","d","e","f","g","h","i","j","k","l","n","m","o","p","q","r","s","t","u","v","w","x","y","z",
						 "A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","T","U","V","W","X","Y","Z",
						 "1","2","3","4","5","6","7","8","9","0",
						 "~","!","@","#","$","%","^","&","*","(",")","_","+","?"
						 );

	for($i = 0; $i < 8; $i++) {
		$rand_num  = array_rand($temp_passwd_word);
		$rand_su .= $temp_passwd_word[$rand_num];
	}

	$temp_password = hash("sha256", $rand_su);
	$temp_token = hash("sha256", $find_id.$rand_su);

	$update = "UPDATE koweb_member SET  password = '$temp_password', auth_token = '$temp_token' $check_where";
	mysql_query($update);

	//result
?>
	<div id="wrap">
		<div id="find_password">
			<form method='get' action="<?=$PHP_SELF?>" enctype='multipart/form-data'>
			<input type="hidden" name="mode" value="login" />
			<input type="hidden" name="return_mode" value="" />
				<div class="join_area">
					<h2><i>임시비밀번호 발급</i></h2>
					<div class="box find">
						<h3>임시비밀번호 발급</h3>
						<table class="bbsView">
							<caption>임시비밀번호 발급</caption>
							<colgroup>
								<col data-member-form="th" style="width:30%;"/>
								<col data-member-form="td" style="width:70%;"/>
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="temp_passwd">임시 비밀번호</label></th>
									<td data-member-form="temp_passwd">
										<?=$rand_su?>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="tac">
						<input type="submit" class="button black" value="로그인" />
						</div>
						<p>
							임시 비밀번호를 꼭 기억하시고 로그인 후 비밀번호를 변경하시기 바랍니다.<br/>
						</p>
					</div>
				</div>
			</form>
		</div>
	</div>
<?
} else if ($return_mode == "find_id") {
	//if (!$find_name)	error("이름을 입력해 주세요.");
	//if (!$find_phone1)	error("연락처를 입력해 주세요.");
	//if (!$find_phone2)	error("연락처를 입력해 주세요.");
	//if (!$find_phone3)	error("연락처를 입력해 주세요.");
	//if (!$find_email)	error("이메일을 입력해 주세요.");

	if($site[use_namecheck] == "Y"){
		//TODO
		$check = mysql_fetch_array(mysql_query("SELECT *  FROM koweb_member WHERE CI='$_SESSION[CI]' AND DI = '$_SESSION[DI]' AND state='Y'  LIMIT 1"));
		$check_where = "WHERE CI='$_SESSION[CI]' AND DI='$_SESSION[DI]' AND state = 'Y'"; 

	
		if (!$check[id])	error("등록된 회원 정보가 없습니다.");
		if (!$check[no])	error("등록된 회원 정보가 없습니다.");

	} else {

		//휴대폰
		$find_phone = $find_phone1."-".$find_phone2."-".$find_phone3;
		$check = mysql_fetch_array(mysql_query("SELECT * FROM koweb_member WHERE name='$find_name' AND phone='$find_phone' AND email='$find_email' AND birthday='$find_birth' AND state='Y'  LIMIT 1"));

		if (!$check[id])	error("등록된 회원 정보가 없습니다.");
		if (!$check[no])	error("등록된 회원 정보가 없습니다.");
	}
?>
	<div id="wrap">
		<div id="find_id">
			<div class="join_area">
				<h2><i>아이디 찾기 결과</i></h2>
				<div class="box find">
					<h3>아이디 찾기 결과</h3>
					<table class="bbsView">
						<caption>아이디 찾기 결과</caption>
						<colgroup>
							<col data-member-form="th" style="width:30%;"/>
							<col data-member-form="td" style="width:70%;"/>
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label for="find_result">아이디</label></th>
								<td data-member-form="find_result">
									<?=$check[id]?>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="tac">
						<a href="<?=$PHP_SELF?>?mode=login" class="button black"> 로그인</a>
						<a href="<?=$PHP_SELF?>?mode=find_pw" class="button black"> 비밀번호찾기</a>
					</div>
					<p>
						비밀번호를 찾으시려면 비밀번호 찾기를 이용해주시기 바랍니다.<br/>
					</p>
				</div>
			</div>
		</div>
	</div>
<?
} else {
	error("올바른 접속경로를 이용해주시기 바랍니다.");
	exit;
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/

?>
