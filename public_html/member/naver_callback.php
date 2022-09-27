<?
	if($state != $_SESSION[naver_state]){
		error("비정상적인 접근입니다.");
		exit;
	}
	$naver_curl = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&redirect_uri=".urlencode(NAVER_CALLBACK_URL)."&code=".$code."&state=".$state;

	$is_post = false;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $naver_curl);
	curl_setopt($ch, CURLOPT_POST, $is_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec ($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);

	if($status_code == 200) {
		$responseArr = json_decode($response, true);
		$_SESSION['naver_access_token'] = $responseArr['access_token'];
		$_SESSION['naver_refresh_token'] = $responseArr['refresh_token'];

		$me_headers = array(
			'Content-Type: application/json',
			sprintf('Authorization: Bearer %s', $responseArr['access_token'])
		);


		$me_is_post = false;
		$me_ch = curl_init();
		curl_setopt($me_ch, CURLOPT_URL, "https://openapi.naver.com/v1/nid/me");
		curl_setopt($me_ch, CURLOPT_POST, $me_is_post);
		curl_setopt($me_ch, CURLOPT_HTTPHEADER, $me_headers);
		curl_setopt($me_ch, CURLOPT_RETURNTRANSFER, true);
		$me_response = curl_exec ($me_ch);
		$me_status_code = curl_getinfo($me_ch, CURLINFO_HTTP_CODE);
		curl_close ($me_ch);

		$me_responseArr = json_decode($me_response, true);

		if($me_status_code == 200){
			$naver_id = 'naver_'.$me_responseArr['response']['id'];
			$naver_name = $me_responseArr['response']['name'];
			$naver_email = $me_responseArr['response']['email'];
			$naver_access_key = $responseArr['access_token'];

			$type = "NAVER";
			$check_ = "SELECT * FROM koweb_member WHERE type='NAVER' AND id='$naver_id'";
			$result_ = mysql_query($check_);
			$row_ = mysql_num_rows($result_);

			//아이디있는지 확인 - 없다면...
			if($row_ <= 0){
				if($site[use_member_okey] == "Y"){
					$level = "99";
				} else {
					$level = $site[member_level];
				}
				@mysql_query("INSERT INTO koweb_member VALUES('', '$type', '$naver_name', '$dept', '$naver_access_key', '$naver_access_key', '$naver_id', '$password_enc', '$id_enc', '$auth_token', '$level', '$phone', '$tel', '$naver_email', '$birthday', '$gender', '$zip', '$address1', '$address2', '$is_admin', '$company', '$company_tel', '$company_zip', '$company_address1', '$company_address2', '$reg_date', 'Y', '$ip')");
			} else {
				@mysql_query("UPDATE koweb_member SET CI='$naver_access_key' WHERE type='NAVER' AND id='$naver_id'");
			}
		} else {
			error("회원정보를 가져오지 못했습니다.");
			exit;
		}
	} else {
		error("토큰값을 가져오지 못했습니다.");
		exit;
	}
?>
