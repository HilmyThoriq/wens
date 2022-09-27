<script type="text/javascript">
	function setCookie( name, value, expirehours, expireminutes) { 
		var todayDate = new Date(); 
		
		todayDate.setHours( todayDate.getHours() + expirehours ); 
		todayDate.setMinutes( todayDate.getMinutes() + expireminutes );
		document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 

	} 
	function closeWin(no) { 
		document.getElementById("layer_"+no).style.display = "none";
	}
	function todaycloseWin(no) { 
		var cookie_name = "layer_"+no;
		var stodayDate = new Date();
		setCookie( cookie_name, "done" , 23-stodayDate.getHours(), 60-stodayDate.getMinutes()); 
		//document.getElementById(no).style.display = "none";
		$("#layer_"+no).css("display", "none");
	}
</script>


<?
	$result	= mysql_query("SELECT * FROM koweb_popup WHERE '$today' BETWEEN start_date AND end_date AND type='layer' AND state = 'Y' ORDER BY start_date ASC, end_date ASC, no ASC");
	while ($row = mysql_fetch_array($result)) {
	
?>
<div id="layer_<?=$row[no]?>" class="layerPop" style="left:<?=$row[position_width]?>px; top:<?=$row[position_height]?>px; width:<?=$row[width]?>px; z-index:<?=$row[zindex]?>">
	<a href="<?=$row[link_url]?>" target="<?=$row[link_type]?>"><img src="/upload/program/popup/<?=$row[img]?>" alt="<?=$row[title]?>"/></a>
	<div class="btn">
		<button onclick="todaycloseWin('<?=$row[no]?>');">오늘 하루 열지 않기</button>
		<button onclick="closeWin('<?=$row[no]?>');">X 닫기</button>
	</div>
</div>

<script type="text/javascript">
	cookiedata = document.cookie; 
	if ( cookiedata.indexOf("layer_"+"<?=$row[no]?>") < 0 ){ 
		document.getElementById("layer_<?=$row[no]?>").style.display = "block";
	} else {
		document.getElementById("layer_<?=$row[no]?>").style.display = "none"; 
	}
</script>
<? } ?>
