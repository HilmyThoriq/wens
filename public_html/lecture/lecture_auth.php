<?
if(!$_SESSION['auth_level']) $_SESSION['auth_level'] = 10;
$auth_write = true;
$auth_read = true;
$auth_delete = true;
$auth_comment = true;
$auth_reply = true;


if($_SESSION['auth_level'] != 1){
	if($board[auth_write] < $_SESSION['auth_level']) $auth_write = false;
	if($board[auth_read] < $_SESSION['auth_level']) $auth_read = false;
	if($board[auth_delete] < $_SESSION['auth_level']) $auth_delete = false;
	if($board[auth_comment] < $_SESSION['auth_level']) $auth_comment = false;
	if($board[auth_reply] < $_SESSION['auth_level']) $auth_reply = false;
}


if($board[dept_auth]){

	if(!strpos($board[dept_auth], $_SESSION[member_dept])){
		error("게시판 접근 권한이 없습니다.");
		exit;
	}
}

switch ($mode) {
	case "write" :
	case "modify" :
	if(!$auth_write) error("권한이 없습니다.");
	break;

	case "reply" :
	//에디터 js 로 따로
	if(!$auth_reply) error("권한이 없습니다.");
	break;

	case "view" :
	if(!$auth_read) error("권한이 없습니다.");
	break;

	case "delete" : // 삭제
	if(!$auth_delete) error("권한이 없습니다.");
	break;

	case "check" :
	break;

	case "comment_proc" :
	if(!$auth_comment) error("권한이 없습니다.");
	break;

}

?>
