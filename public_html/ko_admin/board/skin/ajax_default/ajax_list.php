<? include $_SERVER['DOCUMENT_ROOT'] . "/head.php"; ?>
<?
$board = mysql_fetch_array(mysql_query("SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
include $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php"; 

//페이징 변수
if (!$start) $start = 0;
$scale = 4; // 리스트 수
$page_scale	= 10; // 페이징 수


//검색
if($keyword){
	if($search_key == ""){
		$search_key = "title";
	}
	$WHERE .= " AND $search_key LIKE '%$keyword%'";
}



if($cate){
	$WHERE .= " AND category = '{$cate}'";
}

//리스트
$total_query = "SELECT * FROM $board_id WHERE 1=1 $WHERE ORDER BY notice DESC, reg_date DESC, no DESC";
$total_result = mysql_query($total_query);
$total = mysql_num_rows($total_result);

$query = "SELECT * FROM $board_id WHERE 1=1  $WHERE ORDER BY notice DESC, ref_group DESC, depth ASC, no DESC LIMIT $start, $scale";

$result = mysql_query($query);
?>
<table class="bbsList">
<caption>게시판 목록</caption>
<colgroup>
	<col data-table="number" style="width:7%"/>
	<? if($board[use_category] == "Y" || $board[use_category] == "I") { ?>
		<col data-table="category" style="width:10%"/>
	<? } ?>
	<col data-table="subject"/>
	<col data-table="write" style="width:15%"/>
	<col data-table="date" style="width:15%"/>
</colgroup>
<thead>
	<tr>
		<th scope="col" data-table="number">No.</th>
		<? if($board[use_category] == "Y" || $board[use_category] == "I") { ?>
			<th scope="col" data-table="category">구분</th>
		<? } ?>
		<th scope="col" data-table="subject">제목</th>
		<th scope="col" data-table="write">작성자</th>
		<th scope="col" data-table="date">작성일</th>
	</tr>
</thead>
<tbody>

<? if($total > 0) { ?>
<? $f_no = $total - $start; ?>
	<? while($row = mysql_fetch_array($result)){ ?>
		<?
			$date_array = explode("-",  $row[reg_date]);
			if($row[notice] == "Y") {
				$no_title = "<em class='notice'>공지</em>";
				$f_no--;
			} else {
				$no_title = $f_no--;
			}
			$title_reply = "";

			//현재글
			if($no == $row[no]){
				$total_txt = "<img src=\"/img/now_gul.gif\" />";
			}

			//7일 new 아이콘
			if ($row[reg_date] >= date("Y-m-d", strtotime("-7 day"))) {
				 $title_reply = "<em class=\"new\">새글</em>";
			} else unset($title_reply);

			//depth 가 1이상이면 답변 아이콘
			if($row[depth] > 0){
				//$padding_style = $row[depth] * 40;
				$title_reply = "<em class='reply'>답변</em>";
			}

			$reply_query_count = "SELECT * FROM board_comment WHERE ref_board_no = '$row[no]' AND board_id = '$board_id'";
			$reply_count_result = mysql_query($reply_query_count);
			$reply_count = mysql_num_rows($reply_count_result);

			if($reply_count > 0){
				$reply_query = "SELECT * FROM board_comment  ref_board_no = '$row[no]' AND board_id = '$board_id ORDER BY reg_date DESC";
				$reply_result = mysql_query($reply_query);
			}
		?>
		<tr>
			<td data-table="number"><?=$no_title?></td>
			<? if($board[use_category] == "Y" || $board[use_category] == "I") { ?>
				<td data-table="category"><? if($row[depth] < 1) echo $row[category]; ?></td>
			<? } ?>
			<td data-table="subject"><a href="javascript:do_Process('view', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '<?=$password?>')"><?=$title_reply?><?=$row[title]?><? if($board[use_comment] == "Y" ) { ?><i>[<?=$reply_count?>]</i><? } ?></a></td>
			<td data-table="write"><?=$row[name]?></td>
			<td data-table="date"><?=reset(explode(" ", $row[reg_date]))?></td>
		</tr>
	<? } ?>
<? } else { ?>
	<tr>
		<td colspan="5" class="none">등록된 글이 없습니다.</td>
	</tr>
<? } ?>
<!-- //게시판목록 -->
</tbody>
</table>

<form action="<?=$_SERVER['REQUEST_URL']?>" method="get" />
<input type="hidden" name="board_id" value="<?=$board_id?>" />
<input type="hidden" name="start" value="<?=$start?>" />
<div class="search_bbs">
	<!-- 검색 -->
	<div class="search">
		<select name="search_key" id="searchType">
			<option value="">전체</option>
			<option value="title"> 제목</option>
			<option value="name">작성자</option>
		</select>
		<input type="text" id="keyword" name="keyword" value="<?=$keyword?>">
		<input type="button" class="button" value="검색" id="search_btn_on">
		<script type="text/javascript">
			$(function(){
				$("#search_btn_on").click(function(){
					var board_id = "<?=$board[id]?>";
					var keyword = $("#keyword").val();
					var search_key = $("#searchType").val();
					var start = "<?=$start?>";
					var cate = "<?=$cate?>";

					do_Process('list', board_id, keyword, search_key, '0', cate, '', '');
				});
			});
		</script>
	</div>
	<!-- //검색 -->
</form>
<? if($auth_write){?>
<!-- 버튼 -->
	<div class="btn">
		<a href="javascript:do_Process('write', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '', '')" class="button">글쓰기</a>
	</div>
<? } ?>
</div>



<div class="pagination">
	<?
	if ($total == 0) $total = 1;
	$page_url = $PHP_SELF."?program_id=$program_id&amp;search_key=$search_key&amp;keyword=$keyword&search2=$search2";

	// 처음
	echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','0', '".$cate."', '', '');\" class=\"btn_first\"><span>맨처음</span></a>";

	$page = floor($start / ($scale * $page_scale));
	if ($start + $scale >  $scale * $page_scale) {
		$pre_start = $start - $scale * $page_scale ;
		echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$pre_start."', '".$cate."', '', '');\" class=\"btn_prev\"><span>이전</span></a>";
	}

	for ($vj = 0; $vj < $page_scale ; $vj++) {
		$ln = ($page * $page_scale + $vj) * $scale;
		$vk = $page * $page_scale + $vj + 1 ;
		$pageing = $vk - 1;
		if ($ln < $total) {
			if ($ln != $start) echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$ln."', '".$cate."', '', '');\">$vk</a>";
			else echo "<span>$vk</span>";
		}
	}

	// 마지막
	$end_page = floor($total - $scale) + 1;
	if ($end_page <= 0)	$end_page = 0;

	if ($total > (($page + 1) * $scale * $page_scale)) {
		$n_start = ($page + 1) * $scale * $page_scale ;
		echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$n_start."', '".$cate."', '', '');\" class=\"btn_next\"><span>다음</span></a>";
	}

	$end_page = ceil($total / $scale);
	if ($total) $end_start = ($end_page -1) * $scale;
	else $end_start = $end_page;

	echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$end_start."', '".$cate."', '', '');\" class=\"btn_last\"><span>맨마지막</span></a>";
	?>
</div>