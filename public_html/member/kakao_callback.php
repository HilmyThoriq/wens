<?
if($state != $_SESSION[kakao_state]){
    error("비정상적인 접근입니다.");
    exit;
}
if($error=="access_denied"){
    url("/member/member.php?mode=login");
}else if(!$code){
    error("잘못된 접근입니다.");
}

$kakao_url = "https://kauth.kakao.com/oauth/token";
$data = array(
    "grant_type" => "authorization_code",
    "client_id" => KAKAO_CLIENT_ID,
    "redirect_uri" => KAKAO_CALLBACK_URL,
    "code" => $code
);
$data = http_build_query($data);
$curl_header = array('Content-Type: application/x-www-form-urlencoded;charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $kakao_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_header);
curl_setopt($ch, POST,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,30);
$response = curl_exec($ch);
curl_close ($ch);

$responseArr = json_decode($response, true);

if(!$responseArr['access_token']){
    alert("카카오톡 로그인이 원활하게 진행되지 않았습니다. 관리자에게 문의해주세요");
    url("/member/member.php?mode=login");
}


$access_token = $responseArr['access_token'];
$kakao_url = "https://kapi.kakao.com/v2/user/me";

$curl_header = array('Authorization: Bearer '.$access_token);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $kakao_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_header);
curl_setopt($ch, POST,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,30);
$response = curl_exec($ch);
curl_close ($ch);

$me_responseArr = json_decode($response, true);
if($me_responseArr['msg']){
    alert($me_responseArr['msg']);
    url("/member/member.php?mode=login");
}

$kakao_id = 'kakao_'.$me_responseArr['id'];
$kakao_name = $me_responseArr['kakao_account']['profile']['nickname'];
$kakao_email = $me_responseArr['kakao_account']['email'];
$kakao_access_key = $access_token;
$gender = "";
if($me_responseArr['kakao_account']['gender'] == "female"){
    $gender = "여성";
}
if($me_responseArr['kakao_account']['gender'] == "male"){
    $gender = "남성";
}
$type = "KAKAO";
$check_ = "SELECT * FROM koweb_member WHERE type='{$type}' AND id='$kakao_id'";
$result_ = mysql_query($check_);
$row_ = mysql_num_rows($result_);
//아이디있는지 확인 - 없다면...
if($row_ <= 0){
    if($site[use_member_okey] == "Y"){
        $level = "99";
    } else {
        $level = $site[member_level];
    }
    @mysql_query("INSERT INTO koweb_member VALUES('', '$type', '$kakao_name', '$dept', '$kakao_access_key', '$kakao_access_key', '$kakao_id', '$password_enc', '$id_enc', '$auth_token', '$level', '$phone', '$tel', '$kakao_email', '$birthday', '$gender', '$zip', '$address1', '$address2', '$is_admin', '$company', '$company_tel', '$company_zip', '$company_address1', '$company_address2', '$reg_date', 'Y', '$ip')");
} else {
    @mysql_query("UPDATE koweb_member SET CI='$kakao_access_key' WHERE type='kakao' AND id='$kakao_id'");
}
?>
