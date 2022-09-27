<?
define(__include_function_php,"true");

if (!defined(__only_num)){					//숫자입력체크 스크립트
	define(__only_num, " onKeyPress=\"if ((event.keyCode<48)||(event.keyCode>57)) event.returnValue=false;\" ");
}
//인스타그램
function load_instagram(){







}

function boardAttachRemove($dir, $board_id, $no){
	
	$chk_query = "SELECT * FROM $board_id WHERE no = '$no' LIMIT 1";
	$chk_result = mysql_query($chk_query);
	$chk = mysql_fetch_array($chk_result);
	for($i = 1; $i <= 10; $i++){
		if($chk['file_'.$i]){
			@unlink($dir . $chk['file_'.$i]);
			@unlink($dir . "thumb_".$chk['file_'.$i]);
		}
	}

}


function setHistory($menu_title, $target, $comment){
	
	if($target){
		$ADD_QUERY = "ref_id = '$target',";
	}
	$reg_date = date("Y-m-d H:i:s");
	$historyInsert = mysql_query("INSERT INTO memberLog SET 
												id = '$_SESSION[member_id]',
												$ADD_QUERY 
												idAuthLevel = '$_SESSION[auth_level]',
												workType = '$menu_title',
												comment = '$comment',
												reg_date = '$reg_date',
												ip = '$_SERVER[REMOTE_ADDR]'");
	if(!$historyInsert){
	
	}
}


//게시판 불러오기
function call_board(){
	GLOBAL $mode;
	GLOBAL $search_key;
	GLOBAL $keyword;
	GLOBAL $start;
	GLOBAL $board_id;
	GLOBAL $no;
	GLOBAL $return_mode;
	GLOBAL $reply;
	GLOBAL $ref_no;
	GLOBAL $password;
	GLOBAL $cate;

	include_once $_SERVER['DOCUMENT_ROOT'] . "/board/board.php";
}

//게시판 아이디, 뽑을 갯수, 정렬값, 정렬순서
function call_board_data($board, $limit=0, $order, $order_type = "DESC"){

	if($limit > 0){
		$add_limit = "LIMIT ".$limit;
	}
	$query = "SELECT * FROM $board ORDER BY $order $order_type $add_limit";
	$result = mysql_query($query);
	$check = mysql_num_rows($result);
	if($check > 0){
        while ($columns = mysql_fetch_array($result)){
            $row[] = $columns;
        }
    }
    return $row;
}


/*
	최석용
	양력 -> 음력 구하기 함수.

	음력 값 뽑아 낼때.
	SolaToLunar($yyyymmdd); ex) 양력 20191004 <- 이 형식으로 들어가야함.
	date("Y-m-d", $lunar_date[time]) <- 날짜 데이터 형식.

*/
// 음력 달력의 달형태를 저장한다.
// 각 해는 13월로 표현되고,  1 작은달, 2 큰달, 3 작은 윤달, 4 큰 윤달 이다. 0 은 윤달이 없는 해에 자리를 채우는 것이다.
// 1881년 1월 30일은 음력 1881년 1월 1일 임으로 이를 기준으로 계산한다.
function sunlunar_data() {
return
"1212122322121-1212121221220-1121121222120-2112132122122-2112112121220-2121211212120-2212321121212-2122121121210-2122121212120-1232122121212-1212121221220-1121123221222-1121121212220-1212112121220-2121231212121-2221211212120-1221212121210-2123221212121-2121212212120-1211212232212-1211212122210-2121121212220-1212132112212-2212112112210-2212211212120-1221412121212-1212122121210-2112212122120-1231212122212-1211212122210-2121123122122-2121121122120-2212112112120-2212231212112-2122121212120-1212122121210-2132122122121-2112121222120-1211212322122-1211211221220-2121121121220-2122132112122-1221212121120-2121221212110-2122321221212-1121212212210-2112121221220-1231211221222-1211211212220-1221123121221-2221121121210-2221212112120-1221241212112-1212212212120-1121212212210-2114121212221-2112112122210-2211211412212-2211211212120-2212121121210-2212214112121-2122122121120-1212122122120-1121412122122-1121121222120-2112112122120-2231211212122-2121211212120-2212121321212-2122121121210-2122121212120-1212142121212-1211221221220-1121121221220-2114112121222-1212112121220-2121211232122-1221211212120-1221212121210-2121223212121-2121212212120-1211212212210-2121321212221-2121121212220-1212112112210-2223211211221-2212211212120-1221212321212-1212122121210-2112212122120-1211232122212-1211212122210-2121121122210-2212312112212-2212112112120-2212121232112-2122121212110-2212122121210-2112124122121-2112121221220-1211211221220-2121321122122-2121121121220-2122112112322-1221212112120-1221221212110-2122123221212-1121212212210-2112121221220-1211231212222-1211211212220-1221121121220-1223212112121-2221212112120-1221221232112-1212212122120-1121212212210-2112132212221-2112112122210-2211211212210-2221321121212-2212121121210-2212212112120-1232212122112-1212122122120-1121212322122-1121121222120-2112112122120-2211231212122-2121211212120-2122121121210-2124212112121-2122121212120-1212121223212-1211212221220-1121121221220-2112132121222-1212112121220-2121211212120-2122321121212-1221212121210-2121221212120-1232121221212-1211212212210-2121123212221-2121121212220-1212112112220-1221231211221-2212211211220-1212212121210-2123212212121-2112122122120-1211212322212-1211212122210-2121121122120-2212114112122-2212112112120-2212121211210-2212232121211-2122122121210-2112122122120-1231212122212-1211211221220-2121121321222-2121121121220-2122112112120-2122141211212-1221221212110-2121221221210-2114121221221";

}


function SolaToLunar($yyyymmdd) {
$getYEAR = substr($yyyymmdd,0,4);
$getMONTH = substr($yyyymmdd,4,2);
$getDAY = substr($yyyymmdd,6,2);

$arrayDATASTR = sunlunar_data();
$arrayDATA = explode("-",$arrayDATASTR);
$arrayLDAYSTR="31-0-31-30-31-30-31-31-30-31-30-31";
$arrayLDAY = explode("-",$arrayLDAYSTR);
$dt = $arrayDATA;



for ($i=0;$i<=168;$i++) {
  $dt[$i] = 0;
  for ($j=0;$j<12;$j++) {
    switch (substr($arrayDATA[$i],$j,1)) {

    case 1:
      $dt[$i] += 29;
      break;

    case 3:
      $dt[$i] += 29;
      break;

    case 2:
      $dt[$i] += 30;
      break;

    case 4:
      $dt[$i] += 30;
      break;
    }
  }

  switch (substr($arrayDATA[$i],12,1)) {

  case 0:
    break;

  case 1:
    $dt[$i] += 29;
    break;

  case 3:
    $dt[$i] += 29;
    break;

  case 2:
    $dt[$i] += 30;
    break;

  case 4:
    $dt[$i] += 30;
    break;
  }
}

$td1 = 1880 * 365 + (int)(1880/4) - (int)(1880/100) + (int)(1880/400) + 30;
$k11 = $getYEAR - 1;
$td2 = $k11 * 365 + (int)($k11/4) - (int)($k11/100) + (int)($k11/400);

if ($getYEAR % 400 == 0 || $getYEAR % 100 != 0 && $getYEAR % 4 == 0) {
  $arrayLDAY[1] = 29;

} else {
  $arrayLDAY[1] = 28;
}

if ($getMONTH > 13) {
  $gf_sol2lun = 0;
}

if ($getDAY > $arrayLDAY[$getMONTH-1]) {
  $gf_sol2lun = 0;
}

for ($i=0;$i<=$getMONTH-2;$i++) {
  $td2 += $arrayLDAY[$i];
}

$td2 += $getDAY;
$td = $td2 - $td1 + 1;
$td0 = $dt[0];

for ($i=0;$i<=168;$i++) {
  if ($td <= $td0) {
    break;
  }
  $td0 += $dt[$i+1];
}

$ryear = $i + 1881;
$td0 -= $dt[$i];
$td -= $td0;

if (substr($arrayDATA[$i], 12, 1) == 0) {
  $jcount = 11;

} else {
  $jcount = 12;
}

$m2 = 0;

for ($j=0;$j<=$jcount;$j++) { // 달수 check, 윤달 > 2 (by harcoon)
  if (substr($arrayDATA[$i],$j,1) <= 2) {
    $m2++;
    $m1 = substr($arrayDATA[$i],$j,1) + 28;
    $gf_yun = 0;
  } else {
    $m1 = substr($arrayDATA[$i],$j,1) + 26;
    $gf_yun = 1;
  }
  if ($td <= $m1) {
    break;
  }
  $td = $td - $m1;
}

$k1=($ryear+6) % 10;
$syuk = $arrayYUK[$k1];
$k2=($ryear+8) % 12;
$sgap = $arrayGAP[$k2];
$sddi = $arrayDDI[$k2];
$gf_sol2lun = 1;

if($m2<10) $m2="0".$m2;
if($sday<10) $td="0".$td;

$Ary[year]=$ryear;
$Ary[month]=$m2;
$Ary[day]=$td;
$Ary[time]=mktime(0,0,0,$Ary[month],$Ary[day],$Ary[year]);

return $Ary;

}
/*
	양력 -> 음력 구하기 함수.
*/


/*
 * 서세윤
 * 멤버 정보가져오기
 * 예외처리는 함수안에서 진행
 */


 function get_license(){

	$license_server = 'http://komaster.kowebpro.co.kr/_system/license.koweb';
	$HOST_ = explode(".",$_SERVER["HTTP_HOST"]);
	if(count($HOST_) >= 4){
		$HOST = $HOST_[count($HOST_)-3].".".$HOST_[count($HOST_)-2].".".$HOST_[count($HOST_)-1];
	}else{
		$HOST = $HOST_[count($HOST_)-2].".".$HOST_[count($HOST_)-1];
	}
	$HOST = str_replace("www.","",$HOST);

	$params = http_build_query(array(
	  'ksystem_host' => $HOST
	));
	$opts = array(
	  CURLOPT_URL => $license_server . '?' . $params,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_SSLVERSION => 1,
	  CURLOPT_HEADER => false,
	  CURLOPT_HTTPHEADER => $headers
	);

	$curl_session = curl_init();
	curl_setopt_array($curl_session, $opts);
	$return_data = curl_exec($curl_session);
	$res = json_decode($return_data,true);

	
	if($res[$HOST] != "true"){
		echo "KOWEB License is invalid";
		exit;
	}
 }

 /* form전송할때 accept-charset="UTF-8" 필수 추가요망 */
 function send_Mail_file($nameFrom, $mailFrom, $to_name, $to_mail, $title, $content, $dir="", $file=false) {
 	$nameFrom = $nameFrom;
 	$mailFrom = $mailFrom;
 	$nameTo = $to_name;
 	$mailTo = $to_mail;


     /* 기본 셋팅 */
     $charset = "UTF-8";
     $nameFrom = "=?$charset?B?".base64_encode($nameFrom)."?=";
     $nameTo = "=?$charset?B?".base64_encode($nameTo)."?=";
     $subject = "=?$charset?B?".base64_encode($title)."?=";


     $header = "Return-Path: <". $mailFrom .">\r\n";
     $header .= "From: ". $nameFrom ." <". $mailFrom .">\r\n";
     $header .= "Reply-To: <". $mailFrom .">\r\n";
     /* 기본셋팅 끝 */

     $boundary = "----" . uniqid("part"); // 구분자 생성


     if($file){ /* 첨부파일이 있을시 */


         $header .= "MIME-Version: 1.0\r\n";                                  // MIME 버전 표시
         $header .= "Content-Type: Multipart/mixed; boundary=\"$boundary\"";  // 구분자 설정, Multipart/mixed 일 경우 첨부화일

         // --- 이메일 본문 생성 --- //
         $mailbody = "This is a multi-part message in MIME format.\r\n\r\n";
         $mailbody .= "--$boundary\r\n";
         $mailbody .= "Content-Type: text/html; charset=utf-8\r\n";
         $mailbody .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
         $mailbody .= nl2br(addslashes($content)) . "\r\n";
         $mailbody .= "--$boundary\r\n";

         // --- 파일 첨부 ---//
 		$upfile = $_SERVER['DOCUMENT_ROOT'].$dir . "/" . $file;
 		$filename=basename($file); // 파일명 추출
 		$file_size = filesize($upfile);
 		$fp = fopen($upfile,"r"); // 파일 열기
 		$file = fread($fp,$file_size); // 파일 읽기
 		fclose($fp);  // 파일 닫기

 		$filename = iconv('utf-8','euc-kr',$filename);
 		$upfile_type = "application/octet-stream";
 		$mailbody .= "Content-Type: ".$upfile_type."; name=\"".$filename.";\"\r\n"; // 내용
 		$mailbody .= "Content-Transfer-Encoding: base64\r\n"; // 암호화 방식
 		$mailbody .= "Content-Disposition: attachment;Content-Length:".$file['size']."; filename=\"".$filename."\"\r\n\r\n"; // 첨부파일인 것을 알림
 		$mailbody .= base64_encode($file)."\r\n\r\n";
         $mailbody .= "\r\n--$boundary--\r\n";

     } else{ /* 첨부파일이 없을시 */
         // --- 헤더작성 --- //
     	$header .= "Content-Type: text/html; charset=utf-8\r\n";
     	$header .= "MIME-Version: 1.0\r\n";
 		// --- 이메일 본문 생성 --- //
 		$mailbody = nl2br($content) . "\r\n";
     }

 	$result = mail($mailTo, $subject, $mailbody, $header);
 }


function get_member($target="*",$member_id=""){
	if($member_id == ""){
		$query = "select {$target} from koweb_member where id='{$_SESSION['member_id']}'";
	}else{
		$query = "select {$target} from koweb_member where id='{$member_id}'";
	}
	$result = mysql_query($query);
	$row =  mysql_fetch_array($result);
	if(!$row)
		return false;
	else
		return $row;
}
/*
 * 서세윤
 * 사용법 : 해당 테이블의 필드명과 request로 넘어온 값을 비교해 쿼리를 만들어줌
 * ==> no = 'no값' , id = 'id값'
 */
function make_set_query_str($table , $escape_field = array()){
    $query ="SHOW COLUMNS FROM {$table}";
    $result = mysql_query($query);
    $temp_array = array();
    while($row =  mysql_fetch_array($result)){
        $target = $row['Field'];

        if(in_array($target,$escape_field) || $row['Key'] == "PRI"){
            continue;
        }
        global ${$target};
		if(is_array(${$target})){ ${$target} = join("|",${$target}); }
        $temp_array[] = $target." = '".${$target}."'";

    };

    return join(",",$temp_array);
}

function make_query_data(){
	$arr = func_get_args();
	//$data = array();

	foreach($arr as $v){
		echo $v;
		$data[${$v}] = $v;
	}
	return $data;
}

//query_string($table, $mode, $no, $data)
//$data는 배열
function board_query_string($table, $mode, $no, $data){
	$make_query = "";
}

//계층형 네비게이션 (쿼리수정)
function category_navigator($ref_group, $ref_no, $depth = 1, $no, $v){
	$query = "SELECT * FROM koweb_dept WHERE state = 'Y' AND depth = '$depth' AND ref_group='$ref_group'";
	$result = mysql_query($query);

	while($data = mysql_fetch_array($result)){

		$v .= $data[dept];

		if($depth != $data[depth]){
			$v .= " > ";
			$depth = $data[depth] + 1;
			category_navigator($data[ref_group], $data[ref_no], $depth, $v);
		}
	}
	return $v;
}


//s = 보내는사람, r = 받는사람, message = 내용
function sms_send($sms_id, $key, $s, $r, $message){
	$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
	// $sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	$sms['user_id'] = base64_encode($sms_id); //SMS 아이디.
	$sms['secure'] = base64_encode($key) ;//인증키
	$smsType = "S";

	$send_info = explode("-", $s);
	$reserve_info = explode("-", $r);


	//장문은 L , 단문은 S
	if( $_POST['smsType'] == "L"){
		  $sms['subject'] =  base64_encode($_POST['subject']);
	}

	//받는번호
	$sms['rphone'] = base64_encode($r);

	//보내는번호
	$sms['sphone1'] = base64_encode($send_info[0]);
	$sms['sphone2'] = base64_encode($send_info[1]);
	$sms['sphone3'] = base64_encode($send_info[2]);

	//메세지 90바이트까지. (단문)
	$sms['msg'] = base64_encode(stripslashes($message));

	//예약일자 * Ymd * 예)20180113
	$sms['rdate'] = base64_encode($_POST['rdate']);

	//예약시간 * His * 예)173000
	$sms['rtime'] = base64_encode($_POST['rtime']);
	$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.

	//전송후 이동할 페이지
	$sms['returnurl'] = base64_encode($_POST['returnurl']);
	$returnurl = $_POST['returnurl'];

	//테스트 용
	$sms['testflag'] = base64_encode($_POST['testflag']);

	//이름삽입번호 ( 메시지에 받는사람 이름넣을때 )
	//<input type="type" name="destination" value="010-000-0000|홍길동,010-000-0000|김영희">
	//<input type="type" name="msg" value="{name}님, 주문하신 물품이 배송되었습니다.">
	$sms['destination'] = strtr(base64_encode($_POST['destination']), '+/=', '-,');


	//반복 설정 ( 원하는경우 Y )
	$sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);

	//반본 횟수
	$sms['repeatNum'] = base64_encode($_POST['repeatNum']);

	//반복시간 15분이상 부터 가능
	$sms['repeatTime'] = base64_encode($_POST['repeatTime']);

	$sms['smsType'] = base64_encode("S"); // LMS일경우 L
	$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

	$host_info = explode("/", $sms_url);
	$host = $host_info[2];
	$path = $host_info[3]."/".$host_info[4];

	srand((double)microtime()*1000000);
	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	//print_r($sms);

	// 헤더 생성
	$header = "POST /".$path ." HTTP/1.0\r\n";
	$header .= "Host: ".$host."\r\n";
	$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

	// 본문 생성
	foreach($sms AS $index => $value){
		$data .="--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
		$data .= "\r\n".$value."\r\n";
		$data .="--$boundary\r\n";
	}
	$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

	$oCurl = curl_init();
	$url =  "https://sslsms.cafe24.com/smsSenderPhone.php";
	$aPostData['userId'] = $sms_id; // SMS 아이디
	$aPostData['passwd'] = $key; // 인증키
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPostData);
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
	$ret = curl_exec($oCurl);
	//echo $ret;
	curl_close($oCurl);


	$fp = fsockopen($host, 80);

	if ($fp) {
		fputs($fp, $header.$data);
		$rsp = '';
		while(!feof($fp)) {
			$rsp .= fgets($fp,8192);
		}
		fclose($fp);
		$msg = explode("\r\n\r\n",trim($rsp));
		$rMsg = explode(",", $msg[1]);
		$Result= $rMsg[0]; //발송결과
		$Count= $rMsg[1]; //잔여건수
	} else {
		$alert = "Connection Failed";
	}


	return $Result;
}

//온라인폼 생성 insert function
 function insert_variable($mode, $database, $ref_no, $name, $type, $order, $view_order) {

	if(!$mode) $result = "insert_variable not mode";
	if(!$database) $result = "insert_variable not database";
	if(!$ref_no) $result = "insert_variable not ref_no";
	if(!$name) $result = "insert_variable not name";
	if(!$type) $result = "insert_variable not type";
	if(!$order) $result = "insert_variable not order";
	if(!$view_order) $result = "insert_variable not view_order";

	if($mode == "update"){
		$function = mysql_query("UPDATE $database SET ref_no = '$ref_no', variable_name = '$name', variable_type='$type', state='Y', order='$order', view_order='$view_order', order='$order' WHERE no='$no'");
	} else if($mode == "insert"){
		$function = mysql_query("INSERT INTO $database VALUES('', '$ref_no', '$name', '$type', 'Y', '$order', '$view_order', '$order')");
	}

	return $result;

}

//URL 도메인 구하기 정규식
 function getDomainName($url) {
	$value = strtolower(trim($url));
	$url_patten = '/^(?:(?:[a-z]+):\/\/)?((?:[a-z\d\-]{2,}\.)+[a-z]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i';
	$domain_patten = '/([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw|daeguweb.gethompy.com)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i';

	if (preg_match($url_patten, $value)){
		preg_match($domain_patten, $value, $matches);
		$host = (!$matches[1]) ? $value : $matches[1];
	}
	return $host;
}

//두날짜 사이 구하기
function getDatesFromRange($a,$b,$x=0,$dates=array()){
    while(end($dates)!=$b && $x=array_push($dates,date("Y-m-d",strtotime("$a +$x day"))));
    return $dates;

	//getDatesFromRange( '2010-10-01', '2010-10-05' );

}

//유효성 검사
function sanitizeString($var) {

		$var = trim($var);
		// $var = htmlspecialchars($var, ENT_QUOTES); // 한글 인코딩(EUC-KR) 지원
		$var = htmlspecialchars($var); // 한글 인코딩(EUC-KR) 지원
		// $var = htmlentities($var); // 한글 인코딩(EUC-KR) 지원 안됨
		$var = stripslashes($var);    // '/' 삭제함
		$var = addslashes($var);   //   '/' 추가함
		$var = strip_tags($var);   //   내용중 태그 삭제
		// $var = escapeshellcmd($var);

	return $var;
}

// 휴대폰으로 업로드시 회전
function rotation($imageName){
	$exif = exif_read_data($imageName);

	
	if (!empty($exif['Orientation'])) {
		   switch ($exif['Orientation']) {
		   case 3:
			   $angle = 180 ;
			   break;

		   case 6:
			   $angle = -90;
			   break;

		   case 8:
			   $angle = 90; 
			   break;
		   default:
			   $angle = 0;
			   break;
		   }   
	}   

	if (preg_match("/(jpg|jpeg)$/i", $imageName)){
		   $source = imagecreatefromjpeg($imageName);
		  $source = imagerotate($source, $angle, 0);
		imagejpeg($source, $imageName);
	}else{
		   $source = imagecreatefrompng($imageName);
		   	$source = imagerotate($source, $angle, 0);
		imagepng($source, $imageName);
	}


}
// 첨부 파일
function upload_file($dir, $tmp_name, $name) {
	// 확장자 체크
	$ext = end(explode(".", strtolower($name)));
	$ext_able = array("gif", "jpg", "png", "jpeg", "zip", "alz", "rar", "txt", "doc", "docx", "hwp", "psd", "xls", "xlsx", "csv", "ppt", "pptx", "bmp", "asf", "wmv", "wma", "pdf", "flv", "swf", "mp4", "mp3");
	if (!in_array($ext, $ext_able)) error("등록 가능한 파일 형식이 아닙니다.");

	// 파일명 인증
	$name = str_replace("\'", "", $name);
	$name = str_replace("\"", "", $name);
	$name = str_replace("..", "", $name);
	while (file_exists($dir . $name)) {
		$name =  rand("10000", "99999")."_".$name;
	}
	
	move_uploaded_file($tmp_name, $dir . $name);

	$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";

	if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
		// 휴대폰으로 업로드시 회전
		rotation($dir . $name);
	}else{
		//echo "PC";
	}

	// 썸네일 만들기
	if ($ext == "gif" || $ext == "jpg" || $ext == "png"|| $ext == "jpeg") {
		// 사용법 : thumnail(원본파일명, 저장파일명, 저장위치, 가로크기, 세로크기)
		@thumnail($dir . $name,  "thumb_" . $name, $dir, 500, 500);
	}
	return $name;
}

// 썸네일
// 사용법 : thumnail(원본파일명, 저장파일명, 저장위치, 가로크기, 세로크기)
function thumnail($file, $save_filename, $save_path, $max_width, $max_height) {

	// 전송받은 이미지 정보를 받는다
	$img_info = @getImageSize($file);

	// 전송받은 이미지의 포맷값 얻기 (gif, jpg, png)
	if ($img_info[2] == 1) {
		$src_img = @ImageCreateFromGif($file);
	} else if ($img_info[2] == 2) {
		$src_img = @ImageCreateFromJPEG($file);
	} else if($img_info[2] == 3) {
		$src_img = @ImageCreateFromPNG($file);
	} else {
		return 0;
	}


	// 전송받은 이미지의 실제 사이즈 값얻기
	$img_width = $img_info[0];
	$img_height = $img_info[1];

	if ($img_width <= $max_width) {
		$max_width = $img_width;
		$max_height = $img_height;
	}

	if ($img_width > $max_width) {
		$max_height = ceil(($max_width / $img_width) * $img_height);
	}

	// 새로운 트루타입 이미지를 생성
	$dst_img = @imagecreatetruecolor($max_width, $max_height);

	// R255, G255, B255 값의 색상 인덱스를 만든다
	@ImageColorAllocate($dst_img, 255, 255, 255);

	// 이미지를 비율별로 만든후 새로운 이미지 생성
	@ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $max_width, $max_height, ImageSX($src_img), ImageSY($src_img));

	// 알맞는 포맷으로 저장
	if ($img_info[2] == 1) {
		@ImageInterlace($dst_img);
		@ImageGif($dst_img, $save_path.$save_filename);
	} else if ($img_info[2] == 2) {
		@ImageInterlace($dst_img);
		@ImageJPEG($dst_img, $save_path.$save_filename);
	} else if($img_info[2] == 3) {
		@ImagePNG($dst_img, $save_path.$save_filename);
	}

	// 임시 이미지 삭제
	@ImageDestroy($dst_img);
	@ImageDestroy($src_img);
}



// 에러 표시
function error($message) {
	echo "
			<script type='text/javascript'>
				alert(\"$message\");
				history.go(-1);
			</script>
		";

	exit;
}

// 공백검사
function isblank($str) {
	$temp = str_replace("　", "", $str);
	$temp = str_replace("\n", "", $temp);
	$temp = strip_tags($temp);
	$temp = str_replace("&nbsp;", "", $temp);
	$temp = str_replace(" ", "", $temp);
	if (ereg("[^[:space:]]", $temp)) return false;
	return true;
}

// 경고창 표시
function alert($message) {
	echo "
			<script type='text/javascript'>
				alert(\"$message\");
			</script>
		";
}

function alert_to_admin($message) {
	echo "
			<script type='text/javascript'>
				alert(\"$message\");
				location.href=\"/ko_admin/login.html\";
			</script>
		";
}

// 이동
function url($url) {
	echo "<meta http-equiv='refresh' content='0;url=$url' >";
	exit;
}


function car_input( $name , $val, $txt ='' , $type=''  , $arr =''  , $str  ='' ){

?>

 <TR>


	<th><?=$txt?></th>
	<TD><?if($type =='text' ){?><INPUT TYPE="text" NAME="<?=$name?>" value="<?=$val?>" >



	<?

	} elseif($type =='checkbox' ){?>





<?

if($arr =='' ){
?>

<INPUT TYPE="checkbox" NAME="<?=$name?>" value="1" <?if(  1== $val ){echo 'checked'  ;}?>>

<?

}else{
for ( $i=0 ; $i<count($arr) ; $i++){
$arr[$i] = trim($arr[$i]) ;
?>
<INPUT TYPE="checkbox" NAME="<?=$name?>" value="<?=$arr[$i]?>" <?if( $arr[$i] == $val ){echo 'checked'  ;}?>><?=$arr[$i]?>

<?



}
}
?>

<?} elseif($type =='radio' ){?>



<?for ( $i=0 ; $i<count($arr) ; $i++){
$arr[$i] = trim($arr[$i]) ;

?>
<INPUT TYPE="radio" NAME="<?=$name?>" value="<?=$arr[$i]?>" <?if( $arr[$i] == $val ){echo 'checked'  ;}?>><?=$arr[$i]?>

<?
}
?>

<?} elseif($type =='select' ){?>


<select NAME="<?=$name?>" >


<?for ( $i=0 ; $i<count($arr) ; $i++){
$arr[$i] = trim($arr[$i]) ;

?>
<option  NAME="<?=$name?>" value="<?=$arr[$i]?>" <?if( $arr[$i] == $val ){echo 'selected'  ;}?>><?=$arr[$i]?></option>

<?
}
?>
</select>
<?}?>

	<?=$str ?>
	</TD>
 	</TR>
<?
}



 function  cate_array(){
	 return Array (  "" ) ;
 }



 function  cate_array_href($str){

switch( $str)
{

default :
	return s06_1;
break;
}

 }

function   member_page ( $array ){
// 	return "/";

	global  $SSl_host_n  ;

$member_page[0] = $SSl_host_n . "/html/sub.php?sub=s07_1&" ;  // 로그인
$member_page[1] = $SSl_host_n . "/html/sub.php?sub=s07_2&" ;  //회원가입
$member_page[2] =  $SSl_host_n ."/html/sub.php?sub=s07_3&" ;  //아이디패스찾기
$member_page[4] = $SSl_host_n .  "/html/sub.php?sub=s07_4&" ;  //회원정보수정
return $member_page[$array]  ;

}



function pNavi($page_url   , $page ,  $tPage   ,   $pagenels =1  ){

 $page =  intval($page)  ;

$pagenel .=  "<p class=\"pg1\">";
if($page <= 4 ){

 	$sPage =  1 ;
	$ePage =10  ;

}else if($tPage <= $page +5  ){
	$sPage =$tPage  -10  ;
	$ePage =$tPage ;
}else{
	$sPage =$page -4  ;
	$ePage =$page  + 5 ;
}


 if($tPage <= $ePage  ){ $ePage = $tPage ;  }

 if( 1 >= $sPage  ){ $sPage = 1 ;  }




if($page ==1 ){
	$pPage =1 ;
	$nPage =1 ;
}elseif($ePage <=$page) {
	$pPage =$page - 1  ;
	$nPage =$ePage ;
}else{
	$pPage =$page - 1  ;
	$nPage =$page +1 ;

}


$pagenel.= " <a  href=\"".$page_url .$pPage . "\"  class=\"bt4 bt4pv\"  >◀</a>" ;

for ( $i = $sPage  ; $i  <=    $ePage   ; $i ++ ){
	if ($i ==  $page   ){
		$pagenel.= " <a  href=\"".$page_url .$i . "\"  class=\"on\" >$i</a>" ;
	}else{
		$pagenel.= " <a  href=\"".$page_url .$i . "\"  >$i</a>" ;
	}
}

$pagenel.= " <a  href=\"".$page_url .$nPage  . "\"  class=\"bt4 bt4nx\"  >▶</a>" ;

$pagenel.= "</p>" ;
	if  ( $pagenels==1 ) { echo $pagenel ;}
	else{return $pagenel ; }
}




function   sauce_get( $fileanme ){
$body = "" ;
 		$fd = fopen (  $fileanme  , "r");
		$i = 0;
		while (!feof ($fd)) {
			$data = fgets($fd, 4096);
			$data = trim($data);
			if($data) {
				$body.= $data;
			}
		}
fclose ($fd);
return $body  ;

}







function here_setcookie($name, $value, $expire, $path='/')
{
    if (headers_sent()) {
		$expire  =  date("Y-m-d",   $expire );



        echo "<script language=javascript> setcookie( '$name' , '$value', '$expire' )</script>";
    } else {
        setcookie($name, $value, $expire, $path);
    }

}

  function b_total_size($path){
    $tital_size = 0;    //0으로 초기화
    if ($handle = opendir($path)) {  //$path 디렉토리를 열고 핸들러를 얻습니다.
      while (false !== ($file = readdir($handle))) {  //그 핸들러에서 파일/디렉토리 목록을 뽑아옵니다.
          if ($file != "." && $file != "..") {  // .(현재디렉토리) 과 ..(상위디렉토리)는 제외
            if(is_dir("$path/$file")){  //포함된것이 하위디렉토리이면,
              $tital_size += b_total_size("$path/$file");  //이부분에서 하위 디렉토리로 다시 재귀적으로 함수를 호출해서 그 디렉토리의 용량을 알아옵니다.
            }else{ //디렉토리가 아니면 파일입니다.
              $tital_size += filesize("$path/$file");  //그냥 파일이면 용량을 더합니다.
            }
          }
      }
    closedir($handle);
    return $tital_size;
    }else{
      return 0;
    }
  }


//현재페이지
$__this_page_name = array_pop(explode("/",$PHP_SELF));

//이전페이지
$__pre_page_name = array_pop(explode("/",$HTTP_REFERER));





function check_email($email, $check_dns = true  ){



		if($check_dns){
			$host = explode('@', $email);
			if( checkdnsrr($host[1], 'MX') ) return 1; // Check for MX record
//			if( checkdnsrr($host[1], 'A') ) return true;  // Check for A record
			if( checkdnsrr($host[1], 'CNAME') ) return 1;  // Check for CNAME record
		}else{
			return 1;
		}

		return 0 ;

}





function sendemil(  $email     , $MANAGEMENT_MAIL_ADDRESS  ,$shopname    ,   $subject  , $mail_body    ){
	global  $SENDMAIL_PATH  ;





	$mail_body = mailrecheckword($mail_body) ;

	if  ( $email  && $MANAGEMENT_MAIL_ADDRESS ){
  		$mail_body = chunk_split(base64_encode($mail_body));
		$mime_type="text/html";

		$date=date("D, d M Y H:i:s +0900");

		$pp=popen(escapeshellcmd("$SENDMAIL_PATH -t -f $MANAGEMENT_MAIL_ADDRESS"),"w");
		fputs($pp,"Date: $date\r\n");
		fputs($pp,"From: $shopname  <$MANAGEMENT_MAIL_ADDRESS>\r\n");
		fputs($pp,"Subject: $subject\r\n");
		fputs($pp,"Sender: $MANAGEMENT_MAIL_ADDRESS\r\n");
		fputs($pp,"To: $email\r\n");
		fputs($pp,"Reply-To: $MANAGEMENT_MAIL_ADDRESS\r\n");
		fputs($pp,"MIME-Version: 1.0\r\n");
		fputs($pp,"Content-type: text/html; charset=euc-kr\r\n");
		fputs($pp,"Content-Transfer-Encoding: base64\r\n");
		fputs($pp,$mail_body);
		pclose($pp);
	}

$headers = "From:$shopname  <$MANAGEMENT_MAIL_ADDRESS>\r\n";
 $headers  .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=euc-kr\r\n";
$headers .= "Content-Transfer-Encoding: base64\r\n";


  $result_mail=mail($email, $subject, $mail_body, $headers);



 return $result_mail;
}
function pagenevi($page_url   , $page, $totalcount , $pagecount     ,  $pagenels=1    ){

	$gsize = 10	;// 한꺼번에 보여줄 페이지 링크의 갯수
	$spage = $page - ($page-1)  %  $gsize ;

	$epage = $spage + $gsize - 1;
	if ($epage > $pagecount )   $epage = $pagecount;
	$pagenel = ""  ;
		$blockpage = intval(($page-1)/$gsize)  *$gsize+1    ;//  blockpage변수에 값을 입력해준다.
	/**************************** 이전 10 구문 시작 ***************************/
	if ($blockpage == 1){
		$pagenel.=  " <img  src=\"/images/board/btn_prev.gif\"   style=\"filter:gray()\" > " ;
	}
	else{
		$pagenel.= "<a  class=sub  href=\"$page_url\"><img  src=\"/images/board/btn_prev.gif\"   border=0 ></a>" ;
	}

	 $pagenel.= "&nbsp;";
	if (intval($page)  > 1  ){
			$pageurl =$page - 1 ;
			$pagenel.= "<a  href=\"".$page_url. $pageurl."\"><img src=\"/images/board/btn_prev_1.gif\"  border=0 ></a>"	 ;
	}else{
			$pagenel.= "<img src=\"/images/board/btn_prev_1.gif\"  border=0  style=\"filter:gray()\" >"	 ;
	}

	// $pagenel.= " <img  src=\"/img/bottom_table_line_v.gif\" width=\"3\" height=\"11\" hspace=4   > "	 ;

	for ( $i = $spage  ; $i  <=    $epage -1 ; $i ++ ){
		if ($i ==   intval($page)  ){
			$pagenel.= " <a  href=\"".$page_url .$i . "\"><b>$i</b></a> <img src=\"/img/dot.gif\" width=2 height=4   border=0 > "	 ;
		}else{

			$pagenel.= " <a  href=\"".$page_url .$i . "\">$i</a> <img src=\"/img/dot.gif\" width=2 height=4     border=0 > "	 ;
		}
		$blockpage = $blockpage+1	 ;
	}

	if ($i ==   intval($page)  ){
		$pagenel.= " <a  href=\"".$page_url .$i . "\"><b>$i</b></a> "	 ;
	}else{
		$pagenel.= " <a  href=\"".$page_url .$i . "\">$i</a> "	 ;
	}


 	// $pagenel.= " <img  src=\"/img/bottom_table_line_v.gif\" width=\"3\" height=\"11\" hspace=4 border=\"0\"  > " 	 ;

	if   (intval($page) <>  intval($pagecount) ){
			$pageurl =$page + 1 ;
			$pagenel.= "<a  href=\"".$page_url. $pageurl."\"><img src=\"/images/board/btn_next_1.gif\"    border=0 ></a>"	 ;
	}else{
			$pagenel.= "<img src=\"/images/board/btn_next_1.gif\"  border=0    style=\"filter:gray()\"  >"	 ;
	}

	$pagenel.= "&nbsp;"	;

	$blockpage = $blockpage+1	;

	//'************************ 다음 10  구문 시작   ***************************//
	if   (intval($blockpage)  >   intval($pagecount) ){
			$pagenel.= "<img   src=\"/images/board/btn_next.gif\"  border=\"0\"   style=\"filter:gray()\"  >"	 ;
	}else{
		$pagenel.= "<a  href=\"".$page_url .$blockpage  . "\"><img   src=\"/images/board/btn_next.gif\" border=0    ></a>"	 ;
	}
	if  ( $pagenels==1 ) { echo $pagenel ;}
	else{return $pagenel ; }


}


 /*

	<a href="">◀</a>
	<a href="">1</a>
	<a href="">2</a>
	<a href="">3</a>
	<a href="">▶</a>

*/



function pagenevi_new($page_url   , $page, $totalcount , $pagecount     ,  $pagenels=1    ){

	$gsize = 10	;// 한꺼번에 보여줄 페이지 링크의 갯수
	$spage = $page - ($page-1)  %  $gsize ;

	$epage = $spage + $gsize - 1;
	if ($epage > $pagecount )   $epage = $pagecount;
	$pagenel = ""  ;
		$blockpage = intval(($page-1)/$gsize)  *$gsize+1    ;//  blockpage변수에 값을 입력해준다.
	/**************************** 이전 10 구문 시작 ***************************/


	if ($blockpage == 1){

		$pagenel.=  " <a href='#' class='btn_first'>맨처음</a>  " ;
	}
	else{
		$pagenel.= "<a href='$page_url' class='btn_first'>맨처음</a> " ;
	}



	 //$pagenel.= "&nbsp;";
	if (intval($page)  > 1  ){
			$pageurl =$page - 1 ;
			$pagenel.= "<a  href=\"".$page_url. $pageurl."\" class='btn_prev'>이전</a>"	 ;
	}else{
			$pagenel.= "<a  href='#' class='btn_prev' >이전</a>"	 ;
	}



	for ( $i = $spage  ; $i  <=    $epage -1 ; $i ++ ){
		if ($i ==   intval($page)  ){
			$pagenel.= " <a  href=\"".$page_url .$i . "\"><b>$i</b></a>"	 ;
		}else{

			$pagenel.= " <a  href=\"".$page_url .$i . "\">$i</a>"	 ;
		}
		$blockpage = $blockpage+1	 ;
	}

	if ($i ==   intval($page)  ){
		$pagenel.= " <span>$i</span> "	 ;

	}else{
		$pagenel.= " <a  href=\"".$page_url .$i . "\">$i</a> "	 ;
	}



	if   (intval($page) <>  intval($pagecount) ){
			$pageurl =$page + 1 ;
			$pagenel.= "<a  href=\"".$page_url. $pageurl."\" class='btn_next'>다음</a>"	 ;

	}else{
		$pagenel.= "<a href='#' class='btn_next'>다음</a>"	 ;
	}


	$blockpage = $blockpage+1	;








	//'************************ 다음 10  구문 시작   ***************************//
	if   (intval($blockpage)  >   intval($pagecount) ){
			$pagenel.= "<a href='#' class='btn_last'>맨마지막</a>"	 ;
	}else{
		$pagenel.= "<a  href=\"".$page_url .$blockpage  . "\" class='btn_last'>맨마지막</a>"	 ;
	}
	if  ( $pagenels==1 ) { echo $pagenel ;}
	else{return $pagenel ; }

}





function javapage($a,$b){
return "JavaScript:void($a($b))";
}




function  fileurl($filename){



$filename  =  str_ireplace("%2F", "/", urlencode($filename));
$filename  =  str_ireplace("+", "%20", $filename);

return $filename ;
}


  function checkfile($checkvalue){


	if ($$checkvalue) {
		$i =1 ;
		while ($i){
			$checkvalue2 = $checkvalue ;
			$checkvalue =str_ireplace(  chr(46).chr(46)  , "" , $checkvalue);
			$checkvalue =str_ireplace(  chr(47)  , "" , $checkvalue);
			$checkvalue =str_ireplace(  chr(92)  , "" , $checkvalue);
			if  ($checkvalue == $checkvalue2) $i=0 ;
		}
	}else{


	return "";

	}
 return  $checkvalue ;

}





function javapagenevi($page_url   , $page, $totalcount , $pagecount     ,  $pagenels=1    ){

	$gsize = 10	;// 한꺼번에 보여줄 페이지 링크의 갯수
	$spage = $page - ($page-1)  %  $gsize ;

	$epage = $spage + $gsize - 1;
	if ($epage > $pagecount )   $epage = $pagecount;
	$pagenel = ""  ;
		$blockpage = intval(($page-1)/$gsize)  *$gsize+1    ;//  blockpage변수에 값을 입력해준다.
	/**************************** 이전 10 구문 시작 ***************************/
	if ($blockpage == 1){
		$pagenel.=  "<img  src=\'/img/but_prev.gif\' width=\'17\' height=\'17\' align=absmiddle style=\'filter:gray()\' >" ;
	}
	else{
		$pagenel.= "<a  class=sub  href=\'".javapage($page_url,1) ."\'><img  src=\'/img/but_prev.gif\' width=\'17\' height=\'17\' align=absmiddle  border=0 ></a>" ;
	}

	 $pagenel.= "&nbsp;";
	if (intval($page)  > 1  ){
			$pageurl =$page - 1 ;
			$pagenel.= "<a  href=\'".javapage($page_url,$pageurl) ."\'><img src=\'/img/but_prev_1.gif\' width=\'40\' height=\'17\' hspace=4  border=0 align=absmiddle></a>"	 ;
	}else{
			$pagenel.= "<img src=\'/img/but_prev_1.gif\' width=\'40\' height=\'17\' hspace=4  border=0 align=absmiddle style=\'filter:gray()\' >"	 ;
	}

	// $pagenel.= "<img  src=\'/img/bottom_table_line_v.gif\' width=\'3\' height=\'11\' hspace=4 align=absmiddle  >"	 ;

	for ( $i = $spage  ; $i  <=    $epage -1 ; $i ++ ){
		if ($i ==   intval($page)  ){
			$pagenel.= "<a  href=\'". javapage($page_url,$i)   . "\'><b>$i</b></a> <img src=\'/img/dot.gif\' width=2 height=4 hspace=2 align=absmiddle  border=0 > "	 ;
		}else{

			$pagenel.= "<a  href=\'". javapage($page_url,$i)  . "\'>$i</a> <img src=\'/img/dot.gif\' width=2 height=4 hspace=2 align=absmiddle  border=0 > "	 ;
		}
		$blockpage = $blockpage+1	 ;
	}

	if ($i ==   intval($page)  ){
		$pagenel.= "<a  href=\'". javapage($page_url,$i)   . "\'><b>$i</b></a>"	 ;
	}else{
		$pagenel.= "<a  href=\'".javapage($page_url,$i)  . "\'>$i</a>"	 ;
	}


 	$pagenel.= "<img  src=\'/img/bottom_table_line_v.gif\' width=\'3\' height=\'11\' hspace=4 border=\'0\'  align=absmiddle>" 	 ;

	if   (intval($page) <>  intval($pagecount) ){
			$pageurl =$page + 1 ;
			$pagenel.= "<a  href=\'".javapage($page_url,$pageurl) ."\'><img src=\'/img/but_next_1.gif\' width=\'40\' height=\'17\' hspace=4 align=absmiddle  border=0 ></a>"	 ;
	}else{
			$pagenel.= "<img src=\'/img/but_next_1.gif\' width=\'40\' height=\'17\'  border=0  hspace=4 align=absmiddle style=\'filter:gray()\'  >"	 ;
	}

	$pagenel.= "&nbsp;"	;

	$blockpage = $blockpage+1	;

	//'************************ 다음 10  구문 시작   ***************************//
	if   (intval($blockpage)  >   intval($pagecount) ){
			$pagenel.= "<img   src=\'/img/but_next.gif\' width=\'17\' height=\'17\' border=\'0\'  align=\'absmiddle\' style=\'filter:gray()\'  >"	 ;
	}else{
		$pagenel.= "<a  href=\'". javapage($page_url,$blockpage)  . "\'><img   src=\'/img/but_next.gif\' width=\'17\' height=\'17\' border=0  align=absmiddle></a>"	 ;
	}

	return $pagenel ;


}




function checkword($checkvalue) {
 if ($checkvalue) {
global $HTTP_HOST ;


$host =  "http://" .  $HTTP_HOST ;



	$checkvalue =str_ireplace("<script", "<xscript", $checkvalue);
	$checkvalue =str_ireplace("</script", "</xscript", $checkvalue);


	$checkvalue =str_ireplace("\\" . chr(39) ,"&#39;", $checkvalue);
	$checkvalue =str_ireplace("\\" . chr(34) ,"&#34;", $checkvalue);

	$checkvalue =str_ireplace(chr(39),"&#39;", $checkvalue);
	$checkvalue =str_ireplace(chr(34),"&#34;", $checkvalue);

	$checkvalue =str_ireplace('"',"&quot;", $checkvalue);
	$checkvalue =str_ireplace("<", "&lt;", $checkvalue);
	$checkvalue =str_ireplace(">", "&gt;", $checkvalue);

	$checkvalue =str_ireplace("\\'","&#39;", $checkvalue);

	$checkvalue =str_ireplace("\'","&#39;", $checkvalue);
	$checkvalue =str_ireplace("%","&#37;", $checkvalue);
	$checkvalue =str_ireplace("\"","&quot;", $checkvalue);
	$checkvalue =str_ireplace("%","&quot;", $checkvalue);
	$checkvalue =str_ireplace(   $host ,"", $checkvalue);










 }
 return  $checkvalue ;
}

 function mailcheckword ($checkvalue) {
 if ($checkvalue) {




	$checkvalue =str_ireplace( "&#39;" , chr(39) , $checkvalue);
	$checkvalue =str_ireplace( "&#34;" , chr(34) , $checkvalue);


	$checkvalue =str_ireplace("'","&#39;", $checkvalue);

	$checkvalue =str_ireplace('"',"&quot;", $checkvalue);
	$checkvalue =str_ireplace("<", "&lt;", $checkvalue);
	$checkvalue =str_ireplace(">", "&gt;", $checkvalue);
	$checkvalue =str_ireplace("\'","&#39;", $checkvalue);
	$checkvalue =str_ireplace("%","&#37;", $checkvalue);
	$checkvalue =str_ireplace("\"","&quot;", $checkvalue);
	$checkvalue =str_ireplace("%","&quot;", $checkvalue);
 }
 return  $checkvalue ;
}



 function mailrecheckword($checkvalue) {
 if ($checkvalue) {


	global $HTTP_HOST ;
	$host =  "http://" .  $HTTP_HOST ;

	$checkvalue =str_ireplace( "&#39;" , chr(39) , $checkvalue);
	$checkvalue =str_ireplace( "&#34;" , chr(34) , $checkvalue);

	$checkvalue =str_ireplace( "&lt;", "<",  $checkvalue);
	$checkvalue =str_ireplace( "&gt;", ">",  $checkvalue);
	$checkvalue =str_ireplace("&#39;", "'",  $checkvalue);
	$checkvalue =str_ireplace("&quot;", "\"",  $checkvalue);

	$checkvalue =str_ireplace("&#37;","%", $checkvalue);


	$checkvalue =str_ireplace("href='/","href='".$host."/" , $checkvalue);
	$checkvalue =str_ireplace("src='/","href='".$host."/" , $checkvalue);

	$checkvalue =str_ireplace("href=\"/","href=\"".$host."/" , $checkvalue);
	$checkvalue =str_ireplace("src=\"/","src=\"".$host."/" , $checkvalue);

	$checkvalue =str_ireplace("href=/","href=".$host."/" , $checkvalue);
	$checkvalue =str_ireplace("src=/","src=".$host."/" , $checkvalue);







 }
 return  $checkvalue ;
}









function jarecheckword($checkvalue) {
 if ($checkvalue) {

	$checkvalue =str_ireplace( "&#39;" , chr(39) , $checkvalue);
	$checkvalue =str_ireplace( "&#34;" , chr(34) , $checkvalue);
	$checkvalue =str_ireplace( "&lt;", "<",  $checkvalue);
	$checkvalue =str_ireplace( "&gt;", ">",  $checkvalue);
	$checkvalue =str_ireplace("&#39;", "'",  $checkvalue);
	$checkvalue =str_ireplace("&quot;", "\"",  $checkvalue);


	$checkvalue =str_ireplace("&#34;", "\"",  $checkvalue);


	$checkvalue =str_ireplace("&#37;","%", $checkvalue);

 	$checkvalue =str_ireplace("<xscript", "<script", $checkvalue);
	$checkvalue =str_ireplace("</xscript", "</script", $checkvalue);
 }
 return  $checkvalue ;
}



function jacheckword($checkvalue) {
 if ($checkvalue) {

	$checkvalue =str_ireplace( "&#39;" , chr(39) , $checkvalue);
	$checkvalue =str_ireplace( "&#34;" , chr(34) , $checkvalue);

	$checkvalue =str_ireplace( "&lt;", "<",  $checkvalue);
	$checkvalue =str_ireplace( "&gt;", ">",  $checkvalue);
	$checkvalue =str_ireplace("&#39;", "\\'",  $checkvalue);
	$checkvalue =str_ireplace("&quot;", "\\\"",  $checkvalue);

	$checkvalue =str_ireplace("&#37;","%", $checkvalue);
 	$checkvalue =str_ireplace("<xscript", "<script", $checkvalue);
	$checkvalue =str_ireplace("</xscript", "</script", $checkvalue);
 }
 return  $checkvalue ;
}




function recheckword($checkvalue) {
 if ($checkvalue) {

	$checkvalue =str_ireplace( "&#39;" , chr(39) , $checkvalue);
	$checkvalue =str_ireplace( "&#34;" , chr(34) , $checkvalue);
	$checkvalue =str_ireplace( "&lt;", "<",  $checkvalue);
	$checkvalue =str_ireplace( "&gt;", ">",  $checkvalue);
	$checkvalue =str_ireplace("&#39;", "'",  $checkvalue);
	$checkvalue =str_ireplace("&quot;", "\"",  $checkvalue);


	$checkvalue =str_ireplace("&#34;", "\"",  $checkvalue);


	$checkvalue =str_ireplace("&#37;","%", $checkvalue);
 	$checkvalue =str_ireplace("<script", "<xscript", $checkvalue);
	$checkvalue =str_ireplace("</script", "</xscript", $checkvalue);

	$checkvalue =str_ireplace( '<xscript type="text/javascript" src="//e.issuu.com/embed.js" async="true"></xscript>', '<script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>', $checkvalue);





 }
 return  $checkvalue ;
}



function isnum($checkvalue){

 	$checkvalue =str_ireplace(",", "", $checkvalue);
 	if(empty($checkvalue))	$checkvalue=0;
	if (!is_numeric ( $checkvalue))	 $checkvalue = 0 ;
	return  $checkvalue ;
}



//상태바 표시 함수
function status($status)
{
	echo" onmouseover=\"javascript:window.status='$status';return true;\"";
}

// 자바스크립트 메시지 출력 함수
function msgview($msg,$go)
{
	  echo"
		  <script language='javascript'>
		     alert('$msg');
	         history.go($go);
		  </script>
		  ";
		  exit;
		  return true;
}

function msgview1($go)
{
	  echo"
		  <script language='javascript'>

	         history.go($go);
		  </script>
		  ";
		  return true;
}


function onlymsgview($msg)
{
	  echo"
		  <script language='javascript'>
		     alert('$msg');
		  </script>
		  ";
}
function msgviewparent($msg,$href)
{
	echo"
		<script language='javascript'>
		   alert('$msg');
	       parent.location.href='$href';
		</script>
		";
		return true;
		  exit ;
}
function msgviewhref($msg,$href)
{
	  echo"
		  <script language='javascript'>
		     alert('$msg');
	         location.href='$href';
          </script>
		   ";
		  return true;
		  exit ;
}



function openermsgviewhref($msg,$href)
{
	  echo"
		  <script language='javascript'>
	         opener.location.href='$href';
				window.close()
          </script>
		   ";
		  return true;
		  exit ;
}



function msgviewclose($msg)
{
	  echo"
		  <script language='javascript'>
		     alert('$msg');
	         window.close();
          </script>
		   ";
		  exit ;
}





function errmsg($msg)
{
	  $msg= addslashes($msg);
	  $msg= "err. \\n\\n".$msg;
	  echo"
		  <script language='javascript'>
		     alert('$msg');
		  </script>
		  ";
}

//$n 개의 문자열과 '...' 붙이기 함수 함수
function stringcut($string,$n)  //$n : cutting string number
{
	if($n%2)
		$n++;
	$len=strlen($string);   //string length
	if($len<$n)
		return $string;
	else
	{
		$onenextn=$n+1;
        $newstring=substr($string,0,$n);
        $total=0;
        for($i=0;$i<$n;$i++)
		{
			$asc=ord(substr($string,$i,1));
   	        if($asc>128)
				$total++;
    	}
  	    if($total%2)
		{
			$newstring=substr($string,0,$onenextn);
		}

		$newstring.="..";
		return $newstring;
	}
}



function strcut_utf8($str, $len, $checkmb=false, $tail='...') {
    preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);

    $m    = $match[0];
    $slen = strlen($str);  // length of source string
    $tlen = strlen($tail); // length of tail string
    $mlen = count($m); // length of matched characters

    if ($slen <= $len) return $str;
    if (!$checkmb && $mlen <= $len) return $str;

    $ret   = array();
    $count = 0;

    for ($i=0; $i < $len; $i++) {
        $count += ($checkmb && strlen($m[$i]) > 1)?2:1;

        if ($count + $tlen > $len) break;
        $ret[] = $m[$i];
    }

    return join('', $ret).$tail;
}



//$n 개의 문자열과 '...' 붙이기 함수 함수
function HANstringcut($string   ,$n )  //$n : cutting string number
{


	$len=strlen($string);   //string length
       for($i=0;$i < $len;$i++)
		{
			$asc=ord(substr($string,$i,1));
			if($asc>128){
				$a = 1 ;
    		}else{
				$a = 0 ;

			}




	if ($n<=$i){
	$newstring .=  "○" ;
	}else{
  	    if($a )
		{
			$newstring .=substr($string,$i,2 );
		}else{
			$newstring .=substr($string,$i,1 );

		}



	}

		if($asc>128)	$i++  ;


	}

		return $newstring;

}






//가격 출력 함수
// 1234566 -> 1,234,567
function priceformat($price)
{
   return number_format($price, 0, "chicken", ",");
}

function refresh($href)
{
   echo("<script> location.replace('$href')</script> ");
   exit() ;
}

function istable($table_name)
{
	   global $dataname;
	   $result = mysql_list_tables ($dataname);
	   $returnvalue=0;
       while( $tables = mysql_fetch_array($result) )
       {
		  if($table_name==$tables[0])
			  $returnvalue=1;
       }
	   return $returnvalue;
}

//현재시간과 특정일 사이의 기간
function betweenperiod($datetime,$periodday)
{//2003-02-19 11:32:15
	$now = time();
	$timearr= explode(":",substr($datetime,11,8));
	$dayarr	= explode("-",substr($datetime,0,10));


	$dayarr[0] =isnum($dayarr[0]) ;
	$dayarr[1] =isnum($dayarr[1]) ;
	$dayarr[2] =isnum($dayarr[2]) ;

	$timearr[0] =isnum($timearr[0]) ;
	$timearr[1] =isnum($timearr[1]) ;
	$timearr[2] =isnum($timearr[2]) ;


	$mktime = mktime($timearr[0],$timearr[1],$timearr[2],$dayarr[1],$dayarr[2],$dayarr[0]);
	$period	= $periodday*24*60*60;		//기간계산

	if($now >$mktime && $now < ($mktime+$period))
		return 1;
	else if( ($mktime-$period) <$now && $now <$mktime)
		return -1;
	else
		return 0;
}

function mktimes($datetime){


	$timearr= explode(":",substr($datetime,11,8));
	$dayarr	= explode("-",substr($datetime,0,10));

	$dayarr[0] =isnum($dayarr[0]) ;
	$dayarr[1] =isnum($dayarr[1]) ;
	$dayarr[2] =isnum($dayarr[2]) ;

	$timearr[0] =isnum($timearr[0]) ;
	$timearr[1] =isnum($timearr[1]) ;
	$timearr[2] =isnum($timearr[2]) ;




	$mktime = mktime($timearr[0],$timearr[1],$timearr[2],$dayarr[1],$dayarr[2],$dayarr[0]);
	return $mktime ;
}


function mk_times ($datetime){

	$datetimes = explode(" ", $datetime );
	$timearr= explode(":", $datetimes[1] );
	$dayarr	= explode("-", $datetimes[0] );


	$dayarr[0] =isnum($dayarr[0]) ;
	$dayarr[1] =isnum($dayarr[1]) ;
	$dayarr[2] =isnum($dayarr[2]) ;

	$timearr[0] =isnum($timearr[0]) ;
	$timearr[1] =isnum($timearr[1]) ;
	$timearr[2] =isnum($timearr[2]) ;



	$mktime = mktime($timearr[0],$timearr[1],$timearr[2],$dayarr[1],$dayarr[2],$dayarr[0]);
	return $mktime ;
}


//디자인관리 :다지인 이미지 정보
function imageinfo($img)
{
	$img_info=@getimagesize($img);
	return $img_info[2];
}

//배열 함수
function arrayselectpush($arr,$val)
{
	if(!count($arr)){
		return $arr;
	}else{
		$j=0;
		for($i=0;$i<count($arr);$i++){
			if($arr[$i]!=$val){
				$temp[$j]=$val;
				$j++;
			}
		}
		return $temp;
	}
}

function str_limit($string,$limit_length,$add_string){
	$full_length=strlen($string);
	for($k=0; $k<$limit_length-1; $k++){
		if(ord(substr($string, $k, 1))>127) $k++;
	}
	if ($full_length > $limit_length){
		$final_string=substr($string, 0, $k).$add_string;
	} else {
		$final_string=$string;
	}
	return $final_string;
}





function f_commonid( $commonid ="" ,  $commonname =""   ){

$return =  "<INPUT TYPE='hidden' name= 'commonid'   value='".$commonid."'>" ;
$return .=   "<INPUT TYPE='hidden'  name= 'commonname'  value='".$commonname."' >" ;

$return .=  "<span id='commonnamespan'   name='commonnamespan' class='array_input'>&nbsp;</span>" ;




$return .=  " <select name='commonset'   class='input1'  >&nbsp;</span>" ;

$result=dbquery(" select  *  from   daegu_member  where  1= 1   order by idx asc  ");

   $return .=   "<option value=''>선택</opton>" ;
while($row=mysql_fetch_array($result))
 {
   $return .=   "<option value='".$row["userid"]."'>".$row["name"]."</opton>" ;

 }

$return .=  " <INPUT TYPE='button'  class='button' value='추가' onclick='Array2_add( document.all.commonid , document.all.commonname  ,  \"commonnamespan\"  , document.all.commonset[document.all.commonset.selectedIndex].text   , document.all.commonset.value )' > " ;



$return .=  " \n <SCRIPT  > Array2_display(document.all.commonid , document.all.commonname  , \"commonnamespan\") ; </SCRIPT> " ;





return   $return ;
}






function f_Array2(   $name="",  $value =""  , $db =""      ){



$return =  "<INPUT TYPE='hidden' name= '".$name."'   value='".$value."'>" ;
if ( $db ==""  ) return ;
$return .=  "<span id='".$name."span'  name='".$name."span'   class='array_input'>&nbsp;</span>" ;



$return .=  " <select name='".$name."set' >" ;



$result=dbquery(" select  *  from    ".$db."   where  1= 1   and   name !=  ''   order by  vat asc  ,  idx   asc  ");


   $return .=   "<option value=''>선택</opton>" ;


while($row=mysql_fetch_array($result))
 {
   $return .=   "<option value='".$row["name"]."'>".$row["name"]."</opton>" ;
 }
$return .=   "</select>" ;
$return .=  " <INPUT TYPE='button'  class='button'  value='추가' onclick='Array1_add( document.all.".$name."   ,  \"".$name."span\"  ,  document.all.".$name."set.value )' > " ;
$return .=  "<script language='javascript'  type='text/javascript'  >Array1_display(document.all.".$name." ,  '".$name."span'  )</script> ";



return   $return ;
}


function f_Array1(   $name="",  $value =""  , $db =""    ,   $onchange =""    ){




$return = "<select name='".$name."'  onchange= '".$onchange ."'>" ;

   $return .=   "<option value=''>선택</opton>" ;
$result=dbquery(" select  *  from   ".$db."  where  1= 1   and name !=  ''   order by  vat asc  ,  idx   asc  ");

while($row=mysql_fetch_array($result))
 {

	if  ($row["name"] == $value) {$return .=  "<option value='".$row["name"]."' selected>".$row["name"]."<opton>"  ;}
	else{$return .=  "<option value='".$row["name"]."'>".$row["name"]."<opton>" ;}

 }
 $return .=  "</select>" ;
return   $return ;
}





function f_Array3(   $name="",  $value =""     ){




$return = "<select name='".$name."'   >" ;

   $return .=   "<option value=''>선택</opton>" ;
$result=dbquery("select  DISTINCT  team   from  daegu_member      order by team   asc    ");

while($row=mysql_fetch_array($result))
 {

	if  ($row["team"] == $value) {$return .=  "<option value='".$row["team"]."' selected>".$row["team"]."<opton>"  ;}
	else{$return .=  "<option value='".$row["team"]."'>".$row["team"]."<opton>" ;}

 }
 $return .=  "</select>" ;
return   $return ;
}






function f_Array4(   $name="",  $value ="" ,  $array =""     , $select =1   ){




$return = "<select name='".$name."'   >" ;
if ($select ){
   $return .=   "<option value=''>선택</opton>" ;
}
 for($i=$select  ; $i<  count($array );$i++)
 {

	if  ($i== $value) {$return .=  "<option value='". $i   ."' selected>". $array[$i]  ."<opton>"  ;}
	else{$return .=  "<option value='". $i   ."'>". $array[$i]  ."<opton>" ;}

 }
 $return .=  "</select>" ;
return   $return ;
}







function f_input( $name="",  $value =""  , $size =20     ){
	$return =   "<INPUT TYPE='text' name= '".$name."'   value='".$value."'  size='".$size."' class='input1' >" ;
	return  $return  ;
}




function f_text( $name="",  $value =""  , $width =300   , $height =100     ){
	$return =   "<textarea  name= '".$name."'      style='width:". $width."px;height:". $height."px;'  class='textarea' >".$value."</textarea>" ;
	return  $return  ;

}



function f_hidden( $name="",  $value =""  , $size =20     ){
	$return =   "<INPUT TYPE='hidden' name= '".$name."'   value='".$value."'  size='".$size."' >" ;
	$return .=    $value  ;
	return  $return  ;

}



function f_hiddenk( $name="",  $value =""  , $size =20     ){
	$return =   "<INPUT TYPE='hidden' name= '".$name."'   value='".$value."'  size='".$size."' >" ;
	return  $return  ;

}



function f_datetime( $name="",  $value =""  , $size =0     ){

	if ($value  =="") $value  =date("Y-m-d G");
	$value = mktimes($value)  ;
   $Y =  date("Y" ) ;
   $value_Y	=  isnum(date("Y" ,  $value)) ;
	$value_m	=  isnum(date("m" ,  $value )) ;
	$value_d	=  isnum(date("d" ,  $value )) ;
	$value_G	=  isnum(date("H" ,  $value )) ;
	$return =   "" ;

	$return .=   f_select2($name."_Y"   ,  $Y -20    ,  $Y    ,  $value_Y          )   ;
	$return .=   "년" ;
	$return .=   f_select(  $name."_m" ,  1   ,  12  ,  $value_m       )  ;
	$return .=   "월" ;
	$return .=   f_select( $name."_d" ,   1  ,  31  ,  $value_d       )  ;
	$return .=   "일" ;
	$return .=   f_select( $name."_G",  0  ,  23  ,  $value_G       )  ;
	$return .=   "시간" ;



	return  $return  ;

}






function f_date_time( $name="",  $value =""   , $select = 0      ){

	if ($value  =="") $value  =date("Y-m-d H:i");

	if ($value  =="0000-00-00 00:00:00") {

	if  ($select == 0){$value  =date("Y-m-d H:i");}

	}


   $Y =  isnum(date("Y" )) ;
		$return =   "" ;
	if  (!($value  =="0000-00-00 00:00:00" &&$select = 1 )){
	$value = mktimes($value)  ;
   $value_Y	=  isnum(date("Y" ,  $value)) ;
	$value_m	=  isnum(date("m" ,  $value )) ;
	$value_d	=  isnum(date("d" ,  $value )) ;
	$value_G	=  isnum(date("H" ,  $value )) ;
	$value_i		=  isnum(date("i" ,  $value )) ;

	}
	$return .=   f_select2($name."_Y"   ,  $Y -20    ,  $Y  ,  $value_Y	)   ;
	$return .=   "년" ;
	$return .=   f_select(  $name."_m" ,  1   ,  12  ,  $value_m	)  ;
	$return .=   "월" ;
	$return .=   f_select( $name."_d" ,   1  ,  31  ,  $value_d	)  ;
	$return .=   "일" ;
	$return .=   f_select( $name."_G",  0  ,  23  ,  $value_G	 )  ;
	$return .=   "시간" ;
	$return .=   f_select( $name."_i",  0  ,  59  ,  $value_i	 )  ;
	$return .=   "분" ;



	return  $return  ;

}





function f_date ( $name="",  $value =""  , $size =0     ){

	if ($value  =="") $value  =date("Y-m-d");

	$value = mktimes($value)  ;
   $Y =  date("Y" ) ;
   $value_Y	=  isnum(date("Y" ,  $value)) ;
	$value_m	=  isnum(date("m" ,  $value )) ;
	$value_d	=  isnum(date("d" ,  $value )) ;
	$return =   "" ;

	$return .=   f_select2($name."_Y"   ,  $Y -20    ,  $Y    ,  $value_Y          )   ;
	$return .=   "년" ;
	$return .=   f_select(  $name."_m" ,  1   ,  12  ,  $value_m       )  ;
	$return .=   "월" ;
	$return .=   f_select( $name."_d" ,   1  ,  31  ,  $value_d       )  ;
	$return .=   "일" ;



	return  $return  ;

}




function f_date_s  ( $name="",  $value =""  , $size =0     ){

   $Y =  date("Y" ) ;


 if($value  =="--" || $value  ==""  ){

   $value_Y=   '' ;
	$value_m =  '' ;
	$value_d = '' ;


 }else{

	$value = mktimes($value)  ;

   $value_Y=  date("Y" ,  $value) ;
	$value_m =  date("m" ,  $value ) ;
	$value_d =  date("d" ,  $value ) ;
 }


	$return =   "" ;

	$return .=   f_select2($name."_Y"   ,  $Y -20    ,  $Y    ,  $value_Y          )   ;
	$return .=   "년" ;
	$return .=   f_select(  $name."_m" ,  1   ,  12  ,  $value_m       )  ;
	$return .=   "월" ;
	$return .=   f_select( $name."_d" ,   1  ,  31  ,  $value_d       )  ;
	$return .=   "일" ;



	return  $return  ;

}





function f_select2( $name="",  $end = 0   ,  $start  = 0   ,  $value = 0        ){

     if ($value =="") $value =-1 ;
   $value = isnum(  $value) ;


	$return = "<select name='".$name."' >" ;


	 $return .= "<option value='' >선택</option>" ;

	 for ($i=$start  ;$i  >=$end  ; $i --){

		if  ($i == $value ) {$return .= "<option value='".$i."' selected  >" ;}
		else{	$return .= "<option value='".$i."' >" ; 		}
		$return .=  $i  ;
		$return .= "</option>" ;
	 }
	$return .= "</select>" ;



	return  $return  ;

}





function f_select( $name="",  $start = 0   ,  $end  = 0   ,  $value = 0        ){

     if ($value =="") $value =-1 ;
   $value = isnum(  $value) ;


	$return = "<select name='".$name."' >" ;


	 $return .= "<option value='' >선택</option>" ;

	 for ($i=$start  ;$i <=$end  ; $i++){

		if  ($i == $value ) {$return .= "<option value='".$i."' selected  >" ;}
		else{	$return .= "<option value='".$i."' >" ; 		}
		$return .=  $i  ;
		$return .= "</option>" ;
	 }
	$return .= "</select>" ;



	return  $return  ;

}




function admin_cucul($qry , $tb , $cucul ){		//게시판 추출	?>

	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?

	$result=dbquery($qry);
	while($board_row=mysql_fetch_array($result)){
?>
		<tr>
		  <td height="25" background="/images/sub_05/board_dot.gif">
			<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
				  <td width="3"><img src="/images/sub_05/board_list_bullet.gif" width="3" height="3"></td>
				  <td width="5"></td>
				  <td><a href="ad_content.php?tb=<?=$tb?>&num=<?=$board_row["num"]?>"><?= strcut_utf8($board_row[title] ,$cucul , false , '..' )  ; ?></a></td>
				</tr>
			</table>
		  </td>
		</tr>
<?
	}
?>
	</table>
<?}


function admin_cucul_li($qry , $tb , $cucul ){		//게시판 추출	?>


<?

	$result=dbquery($qry);
	while($board_row=mysql_fetch_array($result)){
?>
		<li><a href="ad_content.php?tb=<?=$tb?>&num=<?=$board_row["num"]?>">


		<?= strcut_utf8($board_row[title] ,$cucul , false , '...' )  ; ?>




		</a></li>
<?
	}
?>

<?}



function board_cucul($tb  = "board_1",$sub ="s05_1" ,  $ss =""  ,  $maxsu=4  ,  $maxcnt=33 ){		//게시판 추출

	$qry="select * from daegu_$tb  ";
	if  ($ss){
	$qry.=  "where    sub_title like '$ss' ";
	}

	$qry .="order by re DESC, reid asc, num desc limit 0 , $maxsu" ;
	$row = dbarray($qry) ;

	if(!$row) return ;
	$result=dbquery($qry);
	echo '' ;
	while($board_row=mysql_fetch_array($result)){
	?>
	<li><a  href="<?=$sub?>?style=content&num=<?=$board_row[num]?>"><?= strcut_utf8($board_row[title] ,$maxcnt , false , '...' )  ; ?></a> </li>
	<?
	}
	echo '' ;
}



  function board_cucul_onlile($tb  = "board_1",$sub ="s05_1" ,  $ss =""  ,  $maxsu=4  ,  $maxcnt=33 ){		//게시판 추출


		$qry=" SELECT * FROM  `daegu_online` order by idx desc LIMIT 0 , $maxsu  ";



		$row = dbarray($qry) ;

if(!$row) return ;


	$result=dbquery($qry);



 echo '' ;

	while($board_row=mysql_fetch_array($result)){
?>

<li><a  href="<?=$sub?>?style=content&idx=<?=$board_row[idx]?>"><?= strcut_utf8($board_row[subject] ,$maxcnt , false , '..' )  ; ?></a> <img src="/img/new.gif"></li>



<?
	}

  echo '' ;

}







function board_cucul_img($tb  = "board_4",$sub ="s03_1" ,   $maxsu=6  ,  $maxcnt=10 ){		//게시판 추출
$qry="select * from daegu_$tb order by re DESC, reid asc, num desc limit 0 , $maxsu" ;
$row = dbarray($qry) ;
if(!$row) return ;
$result=dbquery($qry);
$TR= 1  ;


while($board_row=mysql_fetch_array($result)){
$filename ="" ;
$num = $board_row[num];
$filename1 =  $board_row[filename1];
$i_width1 = $board_row[i_width1];
$i_height1 = $board_row[i_height1];
$filename2 =  $board_row[filename2];
$i_width2 = $board_row[i_width2];
$i_height2 = $board_row[i_height2];

if ($board_row[i_width1]  !=0   ||  $board_row[i_height1]  !=0  ){
	$filename = "<img src='/upload/$tb/".$filename1."'      border='0'>" ;
}

if ( $board_row[i_width2]   !=0  && ! $filename ){
	$filename = "<img src='/upload/$tb/".$filename2."'     border='0'>" ;
}

if($filename == ""){

	$content = recheckword($board_row[content]);
	$content = explode("<IMG ", $content);
	$content = explode(">", $content[1]);

	$content = explode('src="', $content[0]);
	$content = explode('"', $content[1]);


 		if($content[0]){
			   $filename = "<img src='".$content[0]."' >";
		}


}



if($filename == ""){

	$content = recheckword($board_row[content]);
	$content = explode("<img ", $content);
	$content = explode(">", $content[1]);

	$content = explode('src="', $content[0]);
	$content = explode('"', $content[1]);


 		if($content[0]){
			   $filename = "<img src='".$content[0]."' >";
		}


}


if($filename == ""){  $filename = "<img src='/img/blank.gif'    border='0'>" ;}
?>



<li>
	<dl>
		<dt><a href="<?=$sub?>?style=content&num=<?=$board_row[num]?>"><?=$filename?></a></dt>
		<dd><a href="<?=$sub?>?style=content&num=<?=$board_row[num]?>"><?= strcut_utf8($board_row[title] ,20 , false , '..' )  ; ?></a></dd>
		<dd><?=substr($board_row[writeday],0,10);?></dd>
	</dl>
</li>



<?
	if  ($TR ==4 or $TR ==8  or $TR ==12 or $TR ==16  ) echo "</ul><ul> ";
		$TR ++ ;
	}



}










?>
