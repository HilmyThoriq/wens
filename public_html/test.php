
<!DOCTYPE html>
<html lang="kr">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
	<style>
	@import url('https://fonts.googleapis.com/css2?family=Caveat&display=swap');
	@import url('https://fonts.googleapis.com/css2?family=Black+Han+Sans&display=swap');
	
	</style>
  <script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
 <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
<script src="https://unpkg.com/html2canvas@1.0.0-rc.5/dist/html2canvas.js"></script>
 </head>
 <body>
<div id="ddkd">
	<textarea id="textyo"  style="width:400px; height:700px; font-size:40px;"></textarea>
</div>



<select name="" id="change">
	<option value="">서체변경</option>
	<option value="궁서체">궁서체</option>
	<option value="고딕체">고딕체</option>
	<option value="굴림체">굴림체</option>
	<option value="맑은고딕">맑은고딕</option>
	<option value="Caveat">웹</option>
	<option value="Black Han Sans, sans-serif">웹2</option>
</select>


<select name="" id="change2">
	<option value="">편지지변경</option>
	<option value="/images/sample.jpg">꽃</option>
	<option value="/upload/board_notice/sample_images_01.png">나도모름1</option>
	<option value="/upload/board_notice/sample_images_03.png">나도모름2</option>
	<option value="/upload/board_notice/sample_images_07.png">나도모름3</option>
	<option value="/upload/board_notice/sample_images_08.png">나도모름4</option>
</select>

<div id="change_div" style="font-size:40px; width:400px; height:700px;">
</div>

<div id="img"></div>

<button id="btn">gogo</button>


<script type="text/javascript">
	$(function(){
		$("#change").change(function(){
			$("#textyo").css("font-family", $(this).val());
			$("#change_div ").css("font-family", $(this).val());
			$("#change_div ").html($("#textyo").val().replace(/\n/g, "<br>"));

		});

		$("#change2").change(function(){
			$("#textyo").css("background-image", "url('"+$(this).val()+"')");
			$("#change_div ").css("background-image", "url('"+$(this).val()+"')");
			$("#change_div ").html($("#textyo").val().replace(/\n/g, "<br>"));
		});

		$("#textyo").keyup(function(){
			$("#change_div ").html($(this).val().replace(/\n/g, "<br>"));
		});


	});

</script>
<script>
$(function(){
    $("#btn").click(function(){
	//	$("#textyo").val($("#textyo").val().replace(/\n/g, "<br>"));
        html2canvas($('#change_div').get(0)).then(function(canvas){
            $("#img").html(canvas);
            var dataURL = canvas.toDataURL('image/png');
              dataURL = dataURL.replace(/^data:image\/[^;]*/, 'data:application/octet-stream');

              dataURL = dataURL.replace(/^data:application\/octet-stream/, 'data:application/octet-stream;headers=Content-Disposition%3A%20attachment%3B%20filename=Canvas.png');


              var aTag = document.createElement('a');

             aTag.download = 'from_canvas.png';

             aTag.href = dataURL;

             aTag.click();
        })
    })

})
</script>

 </body>



</html>



