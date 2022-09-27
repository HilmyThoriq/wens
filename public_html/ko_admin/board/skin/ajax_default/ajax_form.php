<?
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
$board = mysql_fetch_array(mysql_query("SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
include $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php"; 

$mode_title = "글쓰기";
$row[name] = $_SESSION['member_name'];

if($mode == "modify"){
	$query = "SELECT * FROM $board_id WHERE no='$no'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$mode_title = "글수정";

	//관리자가 아님
	if(!$_SESSION[is_admin]){
		if($board[is_membership] != "Y"){
		//비밀번호가 없음
			if($password == "" ){
				echo "<script>do_auth('auth', '$board_id', '$keyword', '$search_key', '$start', '$cate', '$row[no]', '', 'modify');</script>";
				exit;
			}

			$password = hash("sha256", $password);

			//비밀번호가 틀림
			if($row[password] != $password){
				alert("해당게시물은 작성자 및 관리자만 확인 가능합니다.");
				echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
				exit;
			}
		} else {
			if($_SESSION[member_id] != $row[id]){
				alert("해당게시물은 작성자 및 관리자만 수정 가능합니다.");
				echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
				exit;
			}
		}
	}
}
if($mode == "reply"){
	$query = "SELECT name, comments, title FROM $board_id WHERE no='$ref_no'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$comm_first_text = "<p>&nbsp;</p><hr> ".$row[name]." 님이 작성하신 글입니다.<br>";
	$comm_last_text= "<p>&nbsp;</p><hr><p></p><br />";
	$row[comments] = $comm_first_text . $row[comments] . $comm_last_text;

	$title_first_text = "$row[title] 의 답변입니다.";
	$row[title] = $title_first_text;
}
?>
<form action="/ko_admin/board/skin/<?=$skin?>/proc.php" method="post" name="form" enctype="multipart/form-data" id="default_ajax_form">
<input type="hidden" name="board_id" value="<?=$board_id?>" />
<input type="hidden" name="mode" value="<?=$mode?>" />
<input type="hidden" name="return_url" value="<?=$url?>" />
<input type="hidden" name="no" value="<?=$row[no]?>" />
<input type="hidden" name="ref_no" value="<?=$ref_no?>" />
<input type="hidden" name="tag_type" value="<?=$tag_type?>" />

<table class="bbsView">
	<caption>게시판 <?=$mode_title?></caption>
	<colgroup>
		<col data-write="th" style="width:15%"/>
		<col data-write="td" style="width:85%"/>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row"><span class="marking">필수항목</span><label for="title">제목</label></th>
			<td><input type="text" name="title" id="title" class="inputFull required" value="<?=$row[title]?>" title="제목"/></td>
		</tr>
		<tr>
			<th scope="row"><span class="marking">필수항목</span><label for="name">작성자</label></th>
			<td><input type="text" name="name" id="name" value="<?=$row[name]?>" class="required" <?=$mem_option?> title="작성자"/></td>
		</tr>
		<? if($board[use_category] == "Y") { ?>
			<tr>
				<th scope="row"><span class="marking">필수항목</span><label for="category">구분</label></th>
				<td>
					<? $category = explode("|", $board[category_detail]); ?>
					<select name="category" id="category">
						<? for($i = 0; $i < count($category); $i++){ ?>
							<option <?=$cate == $category[$i] ? "selected" : ""?> value="<?=$category[$i]?>"><?=$category[$i]?></option>
						<? } ?>
					</select>
				</td>
			</tr>
		<? } ?>
		<? if($board[use_category] == "I") { ?>
			<tr>
				<th scope="row"><span class="marking">필수항목</span><label for="category">구분</label></th>
				<td>
					<input type="text" name="category" id="category" value="<?=$row[category]?>" class="required" title="구분"/>
				</td>
			</tr>
		<? } ?>
		<tr>
			<th scope="row">비밀글 여부</th>
			<td>
				<div class="designCheck">
					<input type="checkbox" name="secret" id="secret" value="Y" /><label for="secret">비밀글 사용</label>
				</div>
			</td>
		</tr>
		<? if($_SESSION[is_admin]){ ?>
			<tr>
				<th scope="row">공지글 여부</th>
				<td>
					<div class="designCheck">
						<input type="checkbox" name="notice" id="notice" value="Y" /><label for="notice">공지글 여부</label>
					</div>
				</td>
			</tr>
		<? } ?>
		<? if($board[sms_auth] == "Y" && $mode != "modify"){ ?>
			<tr>
				<th scope="row"><span class="marking">필수항목</span>휴대폰번호 입력</th>
				<td class="tel">
					<input type="text" name="phone1" id="phone1" class="input100 required" value="<?=$phone[0]?>" maxlength="3" title="휴대폰 첫번재 자릿수번호"/> -
					<input type="text" name="phone2" id="phone2" class="input100 required" value="<?=$phone[1]?>" maxlength="4" title="휴대폰 두번째 자릿수번호"/> -
					<input type="text" name="phone3" id="phone3" class="input100 required" value="<?=$phone[2]?>" maxlength="4" title="휴대폰 세번째 자릿수번호"/>
					<a href="#" onclick="sms_send();" class="button white">인증번호 받기</a>
				</td>
			</tr>
			<tr>
				<th scope="row"><span class="marking">필수항목</span>인증번호 입력</th>
				<td>
					<input type="text" name="sms_auth" value="" class="input200 required" id="sms_auth" title="인증번호"/>
					<span id="auth_message"><a href="#" onclick="sms_auth();" class="button">확인</a></span>
					<input type="hidden" name="sms_auth2" id="sms_auth2" value="" class="auth_required" />
				</td>
			</tr>
		<? } ?>
		<tr>
			<th scope="row"><label for="smart_content">내용</label></th>
			<td>
				<textarea name="comments" id="smart_content" rows="2" cols="2" style="height:412px;" class="inputFull"><?=$row[comments]?></textarea>
			</td>
		</tr>
		<? if($_SESSION['auth_admin_ci'] == "koweb"){ ?>
		<tr>
			<th scope="row"><label for="etc">기타</label></th>
			<td>
				<textarea name="etc" id="etc" rows="1" cols="2" class="inputFull"><?=$row[etc]?></textarea>
			</td>
		</tr>
		<? } ?>
		<? if($board[file_count] != 0){ ?>
		<tr>
			<th scope="row">첨부파일</th>
				<td>
				<?
					for($i = 1; $i <= $board[file_count]; $i++) {
							$file_title = $row["file_".$i];
							$del_ = "파일선택";
							$del_2 = "";
							if($file_title){
								 $del_2 = "<label for='file_$i' class='button white'><span>기존 첨부파일 : <a href='/upload/$board_id/$file_title' target='_blank'>$file_title</a></label>";
							}
							echo "<div class='designFile'><input type='text' readonly='readonly' value=''/><input type='file' name='file_$i' id='file_$i'/><label for='file_$i' class='button white'> $del_</label> $del_2 </div>";
					 }
				?>
				<em class="tip">첨부파일 가능 용량은 <?=$board[file_limit_size]?>MB 입니다</em>
			</td>
		</tr>
		<? } ?>
		<? if($board[spam_auth] == "Y"){ ?>
		<? $_SESSION[rand_auth] = rand(0000,9999); ?>
			<tr>
				<th scope="row"><span class="marking">필수항목</span><label for="rand_auth_">스팸방지</label></th>
				<td class="spam"><span><?=$_SESSION[rand_auth]?></span><input type="text" name="rand_auth_" id="rand_auth_" placeholder="좌측 번호를 입력해주세요." class="input200 required" title="스팸방지번호"/></td>
			</tr>
		<? } ?>
		<? if($board[is_membership] != "Y"){ ?>
		<tr>
			<th scope="row"><span class="marking">필수항목</span><label for="pin">비밀번호</label></th>
			<td>
				<input type="password" name="password" id="pin" value="" class="required" title="비밀번호"/>
				<em class="tip">※ 글 수정, 삭제시 필요합니다.</em>
			</td>
		</tr>
		<? } ?>
	</tbody>
</table>
<script type="text/javascript">
	$(":checkbox[name='notice'][value='<?=$row['notice']?>']").prop("checked", true);
	$(":checkbox[name='secret'][value='<?=$row['secret']?>']").prop("checked", true);
</script>
<!-- //글쓰기 -->
<!-- 버튼 -->
<div class="btn_area">
	<?
	echo "<input type=\"button\" class=\"button\" onclick=\"javascript:do_Proc('".$mode."', '".$board_id."', '".$keyword."', '".$search_key."','".$start."');\" value=\"저장\">";

	echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$start."', '".$cate."', '', '');\" class=\"button gray\">취소</a>";
	?>
</div>
</form>

<!-- //버튼 -->

