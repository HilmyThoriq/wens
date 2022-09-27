<?
$board_id = trim($board_id);
if(strpos($board_id, "(") !== false || strpos($board_id, ")")  !== false || strpos($board_id, "%")  !== false){
	error("비정상적인 접근입니다.");
	exit;
}

//board 기본정보 불러오기


if($lecture_id){
	$board_query = "SELECT * FROM koweb_lecture_config WHERE id = '$board_id'";
}else{
	$board_query = "SELECT * FROM koweb_board_config WHERE id = '$board_id'";
}


$board_result = mysql_query($board_query);
$board = mysql_fetch_array($board_result);
$skin = $board[skin];

//board 기본 변수
$http_host = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];
$url = "http://" . $http_host . $request_uri;

//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/board.js";
echo "<script type=\"text/javascript\" src=\"/js/board.js\"></script>";
include_once $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php";

if(!$board_id || $board[skin] == ""){
	error("정상적인 경로로 접근하여 주세요!.");
	exit;
}
if($board[is_membership] == "Y"){
	if(!$_SESSION[member_id]) error("회원전용 게시판입니다. 로그인 후 이용하세요.");
}

if($board[is_membership] == "Y"){
	$mem_option = "readOnly";
} else {
	$mem_option = "";
}

switch ($mode) {
	case "write" :
	case "modify" :
		if(!$auth_write) error("권한이 없습니다.");
		//에디터 js 로 따로
		include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $board[title]'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/form.html";
		break;

	case "reply" :
		//에디터 js 로 따로

		if(!$auth_reply) error("권한이 없습니다.");
		include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $board[title]'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/form.html";
		break;


	case "view" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $board[title]'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/view.html";
		break;

	case "delete" : // 삭제
	case "write_proc" : // 삭제
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $board[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/proc.php";
		break;

	case "check" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $board[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/auth_check.html";
		break;

	case "comment_proc" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $board[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/proc.php";
		break;

	default:
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $board[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/list.html";
		break;
}
?>
