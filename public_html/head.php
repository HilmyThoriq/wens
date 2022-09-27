<?
@ session_start();
@ header('Pragma: no-cache');
@ header('Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate');
@ header('Content-type: text/html; charset=utf-8');


 if(!defined(__root_pass)){
	define(__root_pass,"true");
	$temp_root_pass=realpath(__FILE__);
	if($temp_root_pass) $root_pass=str_ireplace("/head.php","",$temp_root_pass);
	else $root_pass="";
}

include $root_pass."/lib/config.php";
include $root_pass."/lib/function.php";
include $root_pass."/lib/input.php";
include $root_pass."/lib/thumbnail.php";
include $root_pass."/lib/pass.php";
include $root_pass."/inc/count.php";

//get_license();

$is_lang_tmp = explode("/", $PHP_SELF);
$lang_ = $is_lang_tmp[1];

//사이트관리 리스트
$language_list_row = mysql_fetch_array(mysql_query("SELECT * FROM koweb_site_language_list ORDER BY no DESC"));
$language_list = explode("|", $language_list_row[lang_list]);

if(in_array($lang_, $language_list)){
	$langwhere = "WHERE lang = '$lang_'";
} else {
	$langwhere = "WHERE lang = 'kr'";
}


//사이트 기본설정
$site_default = mysql_fetch_array(mysql_query("SELECT * FROM koweb_site_config $langwhere ORDER BY no DESC LIMIT 1"));

if($site_default[use_naver_login] == "Y"){
	$naver_callback_url = $_SERVER['HTTP_HOST'];
	$naver_callback_url_host = $_SERVER["REQUEST_SCHEME"];

	if($naver_callback_url_host == "https"){
		$naver_callback_url = "https://" . $_SERVER['HTTP_HOST'] . "/member/member.php?mode=naver";
	} else {
		$naver_callback_url = "http://" . $_SERVER['HTTP_HOST'] . "/member/member.php?mode=naver";
	}

	define('NAVER_CLIENT_ID', $site_default[naver_key]);
	define('NAVER_CLIENT_SECRET', $site_default[naver_pass]);
	define('NAVER_CALLBACK_URL', $naver_callback_url);
}

if($site_default[use_kakao_login] == "Y"){
	$kakao_callback_url = $_SERVER['HTTP_HOST'];
	$kakao_callback_url_host = $_SERVER["REQUEST_SCHEME"];

	if($kakao_callback_url_host == "https"){
		$kakao_callback_url = "https://" . $_SERVER['HTTP_HOST'] . "/member/member.php?mode=kakao";
	} else {
		$kakao_callback_url = "http://" . $_SERVER['HTTP_HOST'] . "/member/member.php?mode=kakao";
	}

	define('KAKAO_CLIENT_ID', $site_default[kakao_key]);
	// define('KAKAO_CLIENT_SECRET', $site_default[naver_pass]);
	define('KAKAO_CALLBACK_URL', $kakao_callback_url);
}


//KAKAO map api key
define('MAP_APPKEY', "27ec05cfabe6c1fecdf7fd5d8d81ee33");


//현재 주소 불러오기
$check_url = explode("?", $_SERVER['REQUEST_URI']);

//등록된 URL 인지 확인
$is_site = mysql_num_rows(mysql_query("SELECT * FROM koweb_page_metatag WHERE url='$check_url[0]' ORDER BY no DESC LIMIT 1"));

//현재 URL이 등록된 컨텐츠가 아니라면 기본 사이트 설정
if($is_site < 1){
	$site = $site_default;
	$site[br_title] = $site_default[title];
	$site[url] = $site_default[site_url];

//현재 URL이 등록된 URL 이라면 등록된 URL의 메타태그 불러옴
} else {
	$site = mysql_fetch_array(mysql_query("SELECT * FROM koweb_page_metatag WHERE url='$check_url[0]' ORDER BY no DESC LIMIT 1"));
	$site[url] = $site_default[site_url];
	$site[site_url] = $site_default[site_url];
	$site[br_title] = $site[og_title];
	$site[naver_tag] = $site_default[naver_tag];
	//등록된 태그 확인시 아래 주석 제거
	//print_r($site);
}

//파라미터에 board_id 가 있다면 게시판이므로 해당 게시판 내용 불러와서 태그 작성
if(strpos($check_url[1], "board_id") !==  false ){

	//no 가 있으면 상세글. 상세글 내용 가져옴
	if($no) {
		$data_table = $board_id;
		$data_WHERE = "WHERE no = '$no'";
	} else {
		$data_table = "koweb_board_config";
		$data_WHERE = "WHERE id = '$board_id'";
	}

	//처리
	$meta_ =  mysql_fetch_array(mysql_query("SELECT * FROM $data_table $data_WHERE LIMIT 1"));

	//mode 에 따라 태그적용
	//상세보기의 $meta_[title] 은 게시글 제목, 리스트 및 작성, 수정일 경우 $meta_[title]은 게시판명

	switch($mode){
		case "view" :
			$meta_state = "(상세보기)";

			//title = 게시글 제목 (상태) | 사이트 제목
			$meta_title_tmp = $meta_[title] . $meta_mode. " | " . $site[title];

			//description = 게시글 내용
			$meta_description_tmp = strip_tags($meta_[comments]);

			break;
		case "write" :
			$meta_state = "(작성)";

			//title = 게시판 제목 (상태) | 사이트 제목
			$meta_title_tmp = $meta_[title] . $meta_mode. " | " . $site[title];

			//description = 기본 사이트 설정
			$meta_description_tmp = $site_default[description];

			break;
		case "modify" :
			$meta_state = "(수정)";

			//title = 게시판 제목 (상태) | 사이트 제목
			$meta_title_tmp = $meta_[title] . $meta_mode. " | " . $site[title];

			//description = 기본 사이트 설정
			$meta_description_tmp = $site_default[description];

			break;
		default :
			$meta_state = "(목록)";

			//title = 게시판 제목 (상태) | 사이트 제목
			$meta_title_tmp = $meta_[title] . $meta_mode. " | " . $site[title];

			//description 게시글 목록은 사이트 기본설정 불러오게 함
			$meta_description_tmp = $site_default[description];
	}

	//기본 사이트 관리 내용으로 초기화
	$site = $site_default;
	$site[url] = $site_default[site_url];

	//title
	$site[br_title] = $meta_title_tmp;
	$site[og_title] = $meta_title_tmp;

	//description
	$site[description] = $meta_description_tmp;
	$site[og_description] =	$meta_description_tmp;

	//site_name 기본 사이트관리에 등록된 사이트 제목
	$site[og_site_name] =  $site[title];
}


//파라미터에 program_id 가 있다면 프로그램이므로 해당 프로그램 내용 불러와서 태그 작성
if(strpos($check_url[1], "program_id") !==  false ){

	//no 가 있으면 상세글. 상세글 내용 가져옴
	if($no) {
		$data_table = $program_id;
		$data_WHERE = "WHERE no = '$no'";
	} else {
		$data_table = "koweb_program_config";
		$data_WHERE = "WHERE id = '$program_id'";
	}

	$meta_ =  mysql_fetch_array(mysql_query("SELECT * FROM $data_table $data_WHERE LIMIT 1"));

	//메타태그 확인시 아래 주석 제거
	//print_r($meta_);

	$base_ =  mysql_fetch_array(mysql_query("SELECT * FROM koweb_program_config WHERE id = '$program_id' LIMIT 1"));

	//각 mode에 따라 태그적용
	//상세보기의 $meta_[title] 은 작성된 제목, 리스트 및 작성, 수정일 경우 $meta_[title]은 프로그램명
	switch($mode){
		case "view" :
			$meta_mode = " (상세보기)";

			//title = 프로그램 게시글 제목 (상태) | 사이트 제목
			$meta_title_tmp = $meta_[title] . $meta_mode. " | " . $site[title];

			//description = 프로그램 게시글 내용
			$meta_description_tmp = strip_tags($meta_[contents]);
			break;

		case "write" :
			$meta_mode = " (작성)";
			//description
			$meta_description_tmp = $meta_[title] . $meta_mode. " | " . $site[title];
			break;
		case "modify" :
			$meta_mode = " (수정)";
			//description
			$meta_description_tmp = $meta_[title] . $meta_mode. " | " . $site[title];
			break;
		default :
			$meta_mode = " (목록)";
			//title = 프로그램 명 (상태) | 사이트 제목
			$meta_title_tmp = $meta_[title] . $meta_mode . " | " . $site[title];
			//description
			$meta_description_tmp = $meta_[title] . $meta_mode. " | " . $site[title];
	}

	//title ( 제목 + 상태 | 사이트 제목 )
	$meta_title = $meta_title_tmp;

	//og_title
	$meta_og_title = $meta_title_tmp;

	//description
	$meta_description = $meta_description_tmp;

	//og_description
	$meta_og_description = $meta_description_tmp;

	$site = $site_default;
	$site[url] = $site_default[site_url];
	//title 적용
	$site[br_title] = $meta_title;
	$site[og_title] =  $meta_og_title;
	//description 적용
	$site[description] = $meta_description;
	$site[og_description] = $meta_og_description;

	$site[og_site_name] =  $site[title];
}

//모바일 에이전트 확인
$mobile_version_flag = false;
if($mobile_version_flag){
    $mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS|Opera)/';
    if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
    	$mobile_agent_ = true;
    } else {
    	$mobile_agent_ = false;
    }

    $add_get_param = http_build_query($_GET);
    $file_path = $_SERVER['PHP_SELF'];
    $file_path_ = explode("/",$file_path);

    if($_GET['pc'] || $_SESSION['pc']){
        $_SESSION['pc'] = true;
        unset($_SESSION['mobile']);
        $mobile_agent_ = false;
    }

    if($_GET['mobile'] || $_SESSION['mobile']){
        $_SESSION['mobile'] = true;
        unset($_SESSION['pc']);
        $mobile_agent_ = true;
    }


    if($file_path_[1] != "ko_admin"){
        if($mobile_agent_){
            if($file_path_[1] != "mobile"){
                if($mode != "naver" && $mode != "kakao")
                header("Location: /mobile".$file_path."?".$add_get_param);
            }
        }

        if($_GET['mobile'] || $_GET['pc']){
            unset($_GET['pc']);
            unset($_GET['mobile']);
            $add_get_param2 = http_build_query($_GET);
            header("Location: ".$file_path."?".$add_get_param2);
        }
    }
    // 푸터에 넣을때 참고 //

    // $_GET['mobile']=1;
    // unset($_GET['pc']);
    // $add_get_param = http_build_query($_GET);
    // $file_path = $_SERVER['PHP_SELF'];
    // <li><a href="/mobile".$file_path."?".$add_get_param">모바일버전보기</a></li>

    // member/member_logout.php에 추가내용  //

    // if($_SESSION['pc']){
    //     $device_add = "?pc=1";
    // }
    // if($_SESSION['mobile']){
    //     $device_add = "?mobile=1";
    // }
    // 추가후, location에 $device_add 추가
}
?>
