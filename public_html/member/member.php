<? include  $_SERVER['DOCUMENT_ROOT'] . "/head.php"; ?>
<?include("../inc/top.html")?>
<?include("../inc/header.html")?>

<?
	//member 기본정보 불러오기
	//프로그램 통합 관리 DB 보면됨
	$member_table = "koweb_member";
	//member 기본 변수
	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$url = "http://" . $http_host . $request_uri;
	//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/member.js";
	echo "<script type=\"text/javascript\" src=\"/js/member.js\"></script>";
	echo "<script type=\"text/javascript\" src=\"/js/namecheck.js\"></script>";
	//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/namecheck.js";
switch ($mode) {

	//약관동의
	case "agree" :
		//에디터 js 로 따로
		if(!$step) $step = 1;
		if($step != 1) $step = 1;
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $member[title]'; </script>";

		if($_SESSION['auth_level'] && $_SESSION['member_id']){
			error("이미 로그인이 되어있습니다.");
			exit;
		} else {
			include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_agree.html";
		}
		break;

	//회원가입 폼
	case "join" :
		//본인인증 여부 체크 변수
		$name_check = true;
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(회원가입) | $member[title]'; </script>";

		if($_SESSION['auth_level'] && $_SESSION['member_id']){
			error("이미 로그인이 되어있습니다.");
			exit;
		}

		//step 이 2가 아니면 약관동의로 다시 넘어간다 ( 주소를 이용 바로 들어오는 것 방지 )
		if($step != 2){
			alert("회원가입 약관에 동의 하셔야 진행 하실 수 있습니다.");
			include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_agree.html";
		}


		//현재 사이트가 본인인증을 사용하는가?
		if($site[use_namecheck] == "Y"){

			//정상적인 CI 와 DI 값이 세션에 존재하면 본인인증 변수를 true 처리 한다.
			if($_SESSION[CI] && $_SESSION[DI]){
				$name_check = true;

			//CI 및 DI값이 없다면 본인인증 안한것이니 본인인증 페이지로 넘긴다.
			} else {
				//본인인증 변수를 false 로 처리 한다음 본인인증 진행
				$name_check = false;

				//TODO 본인인증
				//url 로 리턴 변수까지 다 넣고 날렸다가~~~return url로 돌아오면 됨.
				$return_url = $request_uri;
				$param1 = "mode|".$mode;
				$param2 = "step|".$step;
				$param3 = "";
				url("/inc/namecheck.html?return_url=$request_uri&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5");
				break;
			}
		}

		//본인인증 변수가 true 일 경우 form 으로. 아니면 다시 약관동의로
		if($name_check){
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_form.html";
		} else {
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_agree.html";
		}
		break;

	//회원가입 처리
	case "join_proc" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $member[title]'; </script>";

		if($step != 3){
			error("비정상적인 접근입니다.");
			exit;
		}
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_proc.php";
		break;

	//로그인
	case "login" :
		//에디터 js 로 따로
		//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $member[title]'; </script>";
		if($_SESSION['auth_level'] && $_SESSION['member_id']){
			echo $mode;
			error("이미 로그인이 되어있습니다.");
			exit;
		}
		include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_login.html";
		break;

	//로그인 처리
	case "login_proc" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(로그인처리) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_proc.php";
		break;

	//로그인 처리
	case "naver" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(로그인처리) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_proc.php";
		break;

	//로그인 처리
	case "kakao" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(로그인처리) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_proc.php";
		break;

	//로그아웃
	case "logout" : // 삭제
		if(isblank($_SESSION['member_id'])) error("비정상적인 접근입니다.");
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_logout.php";
		break;

	//마이페이지 - 회원정보 수정
	case "modify" :
		if(isblank($_SESSION['member_id'])) error("비정상적인 접근입니다.");
		if($_SESSION[member_type] == "NAVER" || $_SESSION[member_type] == "KAKAO") error("간편로그인 회원은 회원정보 수정을 할 수 없습니다.");
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(회원정보 수정) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_form.html";
		break;

	//마이페이지 - 회원정보 수정 처리
	case "modify_proc" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(회원정보 수정) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_proc.php";
		break;

	//회원탈퇴
	case "secession" :
		if(isblank($_SESSION['member_id'])) error("비정상적인 접근입니다.");
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_proc.php";
		break;

	//본인인증
	case "check" :
		if(isblank($_SESSION['member_id'])) error("비정상적인 접근입니다.");
		if($_SESSION['login_type']) error("간편로그인은 회원정보를 수정할수 없습니다.");
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $member[title]'; </script>";

		if($site[use_namecheck] == "Y"){
			//TODO 본인인증
			//url 로 리턴 변수까지 다 넣고 날렸다가~~~return url로 돌아오면 됨.
			$return_url = $request_uri;
			$param1 = "mode|".$mode;
			$param2 = "return_mode|".$mode;
			$param3 = "";
			url("/inc/namecheck.html?return_url=$request_uri&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5");
			break;
		} else {
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_auth.html";
		}
		break;

	//아이디 / 비밀번호 찾기
	case "find_id" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(아이디) | $member[title]'; </script>";
		if($site[use_namecheck] == "Y"){
			//TODO 본인인증
			//url 로 리턴 변수까지 다 넣고 날렸다가~~~return url로 돌아오면 됨.
			$return_url = $request_uri;
			$param1 = "mode|"."find_proc";
			$param2 = "step|".$step;
			$param3 = "return_mode|"."find_id";
			url("/inc/namecheck.html?return_url=$request_uri&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5");
			break;
		} else {
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_find_id.html";
		}
		break;

	case "find_pw" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(비밀번호 찾기) | $member[title]'; </script>";
		if($site[use_namecheck] == "Y"){
			//TODO 본인인증
			//url 로 리턴 변수까지 다 넣고 날렸다가~~~return url로 돌아오면 됨.
			$return_url = $request_uri;
			$param1 = "mode|"."find_proc";
			$param2 = "step|".$step;
			$param3 = "return_mode|"."find_pw";
			url("/inc/namecheck.html?return_url=$request_uri&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5");
			break;
		} else {
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_find_pw.html";
		}
		break;

	case "find_proc" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(비밀번호 찾기) | $member[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_find_proc.php";
		break;


	//기본??
	default:
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $member[title]'; </script>";
		//로그인 한 상태라면 마이페이지
		if($_SESSION['auth_level'] && $_SESSION['member_id']){
			//@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member.php";
				url("/member/member.php?mode=check&return_mode=modify");
				exit;
		} else {
		//로그인 하지 않았으면 로그인 페이지
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/member/member_login.html";
		}
		break;

	}
?>
<?include("../inc/footer.html")?>