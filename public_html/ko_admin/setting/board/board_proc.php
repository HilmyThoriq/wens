<?
// 게시판 ID 정리
$id = trim("board_" . $id);
$reg_date = date("Y-m-d H:i:s");

foreach($category_tmp as $value){
	if($value){
		$category_detail .= $value ."|";
	}
}
$category_detail = substr($category_detail, 0, -1);

if(!$use_comment){
	$use_comment = "N";
}

if(!$use_category){
	$use_category = "N";
}

if($mode == "write_proc"){

	// 게시판 ID 중복 체크
	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_board_config WHERE id='$id'"));
	if ($check[0])	error("중복된 게시판 ID 입니다.");

	//신규 스킨 추가나 컬럼 추가 될시 아래 add_query 구문에 추가하세요 * NULL 허용 필수 *
	$add_query = "`money` varchar(10) DEFAULT NULL,
	`contact_type` varchar(10) DEFAULT NULL,
	`term` varchar(100) DEFAULT NULL,
	`area` varchar(100) DEFAULT NULL,
	`area_hidden` varchar(100) DEFAULT NULL,
	`select_date` varchar(32) DEFAULT NULL,";

	if($skin == "land"){
		$add_query .= "
			`counseling_time` varchar(50) DEFAULT NULL COMMENT '상담가능시간',
			`owner_relation` varchar(10) DEFAULT NULL COMMENT '소유자와관계',
			`owner_name` varchar(50) DEFAULT NULL COMMENT '소유자이름',
			`owner_phone` varchar(50) DEFAULT NULL COMMENT '소유자연락처',
			`trade_type` varchar(20) DEFAULT NULL COMMENT '거래종류',
			`item_add` varchar(255) DEFAULT NULL COMMENT '추가정보',
			`item_option` varchar(255) DEFAULT NULL COMMENT '옵션항목',
			`sell_area` varchar(10) DEFAULT NULL COMMENT '분양면적',
			`use_area` varchar(10) DEFAULT NULL COMMENT '전용면적',
			`floor` varchar(50) DEFAULT NULL COMMENT '해당층_총층수',
			`room` varchar(50) DEFAULT NULL COMMENT '방수_욕실수',
			`management_cost` varchar(50) DEFAULT NULL COMMENT '관리비',
			`heating` varchar(20) DEFAULT NULL COMMENT '난방시설',
			`direction` varchar(20) DEFAULT NULL COMMENT '방향',
			`parking_cost` varchar(20) DEFAULT NULL COMMENT '주차',
			`in_day` varchar(50) DEFAULT NULL COMMENT '입주가능일',
			`certificate` varchar(255) DEFAULT NULL COMMENT '등기부 등본',
			`sell_price` int(10) DEFAULT NULL COMMENT '매매금액',
			`deposit_price` int(10) DEFAULT NULL COMMENT '임대차보증금',
			`loan_price` int(10) DEFAULT NULL COMMENT '대출금액',
			`etc_price` int(10) DEFAULT NULL COMMENT '기타',
		";
	}

	// 게시판 스키마
	$schema = "CREATE TABLE `$id` (
	  `no` int(11) NOT NULL AUTO_INCREMENT,
	  `depth` int(2) DEFAULT '1',
	  `ref_no` int(50) DEFAULT '0',
	  `ref_group` int(50) DEFAULT '0',
	  `id` varchar(50) DEFAULT '',
	  `CI` varchar(255) DEFAULT '',
	  `DI` varchar(255) DEFAULT '',
	  `name` varchar(50) NOT NULL,
	  `password` varchar(255) DEFAULT '',
	  `phone` varchar(50) DEFAULT '',
	  `email` varchar(50) DEFAULT '',
	  `zip` varchar(10) DEFAULT '',
	  `address1` varchar(255) DEFAULT '',
	  `address2` varchar(255) DEFAULT '',
	  `category` varchar(255) DEFAULT '',
	  `title` varchar(255) NOT NULL,
	  `comments_type` varchar(10) DEFAULT '',
	  `tag_type` varchar(10) DEFAULT '',
	  `comments` text,
	  `etc` text,
	  `notice` char(1) DEFAULT '',
	  `secret` char(1) DEFAULT '',
	  `file_type` varchar(255) DEFAULT 'zip|jpg|jpeg|png|gif|bmp',
	  `file_1` varchar(255) DEFAULT '',
	  `file_2` varchar(255) DEFAULT '',
	  `file_3` varchar(255) DEFAULT '',
	  `file_4` varchar(255) DEFAULT '',
	  `file_5` varchar(255) DEFAULT '',
	  `file_6` varchar(255) DEFAULT '',
	  `file_7` varchar(255) DEFAULT '',
	  `file_8` varchar(255) DEFAULT '',
	  `file_9` varchar(255) DEFAULT '',
	  `file_10` varchar(255) DEFAULT '',
	  `useDownload_1` varchar(20) DEFAULT '',
	  `useDownload_2` varchar(20) DEFAULT '',
	  `useDownload_3` varchar(20) DEFAULT '',
	  `useDownload_4` varchar(20) DEFAULT '',
	  `useDownload_5` varchar(20) DEFAULT '',
	  `useDownload_6` varchar(20) DEFAULT '',
	  `useDownload_7` varchar(20) DEFAULT '',
	  `useDownload_8` varchar(20) DEFAULT '',
	  `useDownload_9` varchar(20) DEFAULT '',
	  `useDownload_10` varchar(20) DEFAULT '',
	  `reg_date` varchar(20) NOT NULL,
	  `ip` varchar(20) NOT NULL,
	  `reply_state` char(1) DEFAULT '',
	  `reply_id` varchar(50) DEFAULT '',
	  `reply_name` varchar(50) DEFAULT '',
	  `reply_phone` varchar(50) DEFAULT '',
	  `reply_email` varchar(50) DEFAULT '',
	  `reply_comments` text,
	  `reply_ip` varchar(20) DEFAULT '',
	  `reply_date` varchar(20) DEFAULT '',
	  `reply_file_1` varchar(255) DEFAULT '',
	  `reply_file_2` varchar(255) DEFAULT '',
	  `reply_file_3` varchar(255) DEFAULT '',
	  `reply_file_4` varchar(255) DEFAULT '',
	  `reply_file_5` varchar(255) DEFAULT '',
	  `view_count` int(11) DEFAULT '0',
	  `hidden` char(1) DEFAULT '',
	  `link` varchar(255) DEFAULT NULL,
	  $add_query
	  PRIMARY KEY (`no`),
	  KEY `name` (`name`),
	  KEY `title` (`title`)
	);";

	/*----------------------------------------------------------------------------*/
	// 작업
	/*----------------------------------------------------------------------------*/
	// 게시판 생성
	@mysql_query($schema);

	foreach($dept_auth as $value){
		$dept_query .= $value."|";
	}
	$dept_query = substr($dept_query, 0, -1);

	if($skin == "area"){
		$use_category = "Y";
		$category_detail = "서울|인천|경기|대전|충남|충북|강원|광주|전남|전북|대구|경북|경남|울산|부산|제주";
	}

	$sort_ = mysql_fetch_array(mysql_query("SELECT * FROM koweb_board_config ORDER BY sort DESC LIMIT 1"));
	$sort = $sort_[sort]+1;

	// 게시판 정보 입력
	@mysql_query("INSERT INTO koweb_board_config VALUES('', '$id', '$title', '$skin', '$auth_write', '$auth_read', '$auth_reply', '$auth_delete', '$auth_comment', '$use_category', '$category_detail', '$use_comment', '$use_reply', '$always_secret', '$file_count', '$file_limit_size', '$use_address', '$sms_auth', '$spam_auth', '$dept_query', '$is_membership', '$reg_date', '$state', '$sort')");

	// 첨부파일 폴더 생성
	mkdir($_SERVER[DOCUMENT_ROOT] . "/upload/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/upload/" . $id, 0777);

	$alert_txt = "등록";

} else if ($mode == "modify_proc"){

	foreach($dept_auth as $value){
		$dept_query .= $value."|";
	}
	$dept_query = substr($dept_query, 0, -1);

	//기존게시판정보 불러오기
	$board_ = mysql_fetch_array(mysql_query("SELECT * FROM koweb_board_config WHERE no='$no' LIMIT 1"));

	//지도형게시판으로 수정하였을시, 기존에 지도게시판이었으면 카테고리 건들필요 없음, 기존에 지도게시판이 아니었다면 카테고리내용 자동변경
	if($skin == "area"){
		if($board_[skin] != "area"){
			$use_category = "Y";
			$category_detail = "서울|인천|경기|대전|충남|충북|강원|광주|전남|전북|대구|경북|경남|울산|부산|제주";
		}
	}

	@mysql_query("UPDATE koweb_board_config  SET title='$title', skin='$skin', auth_write='$auth_write', auth_read='$auth_read', auth_reply='$auth_reply', auth_delete='$auth_delete', auth_comment='$auth_comment', use_category='$use_category', category_detail='$category_detail', use_comment='$use_comment', use_reply='$use_reply', always_secret = '$always_secret' , file_count='$file_count', file_limit_size='$file_limit_size', use_address='$use_address', dept_auth = '$dept_query', reg_date='$reg_date', sms_auth='$sms_auth', spam_auth='$spam_auth', state='$state', is_membership = '$is_membership' WHERE no='$no'");

	$alert_txt = "수정";

} else {
	//기존 게시판 정보
	$board_ = mysql_fetch_array(mysql_query("SELECT * FROM koweb_board_config WHERE no='$no' LIMIT 1"));
	$rename_ = date("YmdHis")."_change_".$board_[id];
	@mysql_query("RENAME TABLE $board_[id] TO $rename_");

	@rename($_SERVER[DOCUMENT_ROOT] . "/upload/" . $board_[id], $_SERVER[DOCUMENT_ROOT] . "/upload/" . $rename_);
	@mysql_query("DELETE FROM koweb_board_config WHERE no = '$no' LIMIT 1");
	$alert_txt = "삭제";
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
?>
<script type="text/javascript">
alert("<?=$alert_txt?> 되었습니다.");
location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
</script>
