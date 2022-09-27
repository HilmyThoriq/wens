/* 
 * custom js Document
*/ 

$(document).ready(function() {
	responsive();	
    gallery();
    mainTab();
	subnavigation();
	triggerBtn();
});

function subnavigation(){
	var naviTitle = $('#header .gnb > li a.on').html();
	$('.area_subVisual h2').html(naviTitle);

	$(".sub_nav_tab li a.now").click(function(){
		$(".sub_nav_tab ul").slideUp();
		$(this).removeClass('rotate');
		if(!$(this).next().is(":visible"))
		{
			$(this).next().slideDown();
			$(this).addClass('rotate');
		}
	});
}

function triggerBtn(){
		$('.btn_trigger').click(function() {
        $('.wrap_sitemap').toggleClass('active');
		$('.area_sitemap .gnb > li').each(function(){
                var gnbS = $('.area_sitemap .gnb > li');
				var gnbA = $(this).children('a');
				if($(this).children('ul').length > 0){
					gnbA.on('click',function(e){
						e.preventDefault();
						gnbS.children('ul').stop().slideUp();
						$(this).addClass('active');
						$(this).siblings('a').addClass('active');
						$(this).parent().children('ul').stop().slideDown();
						return false;
					});
				}
			});
        return false;
     });
}

//lnb + subTab
$(document).ready(function() {	
    var snbTab = $('#header .gnb > li > a.on').next('ul').children("li").find(".depth02").html();
	$('.lnb .depth03 .under_menu, .area_subTab.typeA ul').append(snbTab);

	$('#header nav .gnb > li').each(function(){
		var gnblink = $(this).children('a');
		
		if(gnblink.hasClass('on')){
			// var lnbText = $(this).children('a').text();
			var lnbList = $(this).children('ul.depth').html();
			var gnbList = $("#header nav .gnb").html();
			
			//1차삽입
			$('.lnb .depth01 .under_menu').append(gnbList);
			$('.lnb .depth01 .under_menu').find('a').removeAttr('id');
			
			//2차삽입
			$('.lnb .depth02 .under_menu').append(lnbList);
			$('.lnb .depth02 .under_menu').find('a').removeAttr('id');

			//1차 title
			var gnbTitle = $('#header nav .gnb > li > a.on').text();
			$('.lnb .depth01 .now').children('span').text(gnbTitle);	

            //2차 title		
            if($(".depth03").length > 0){
				var lnbTitle = $('.lnb .depth02 .under_menu').find('a.on').parents("ul").siblings("a").text();
				
			}	else{
				 var lnbTitle = $('.lnb .depth02 .under_menu').find('a.on').text();
			}
			$('.lnb .depth02 .now').children('span').text(lnbTitle);	
            $('.area_subtitle.on').children('h3').text(lnbTitle);

			//3차
            var snbTitle = $('#header nav .gnb > li a.on').siblings("ul").find(".depth02").children("li").children(".on").text();
			// console.log(snbTitle);
			$('.lnb .depth03 .now').children('span').text(snbTitle);	
		}
	});
});

//responsive style
function responsive(){
	var res = ""
	var param = $("#header");
	var gnbArea = $(".gnb > li");
	var gnbLink = gnbArea.children("a");
	
	$('#header nav .gnb > li ul').addClass('active');
	$('#header').append('<a href="#" class="btn_close">메뉴닫기</a>');
	$('#header .btn_menu').append('<span></span><span></span><span></span>');	

	//default 
	if(!$(".area_sitemap .top h3").is(":hidden")) res = "web";
	else res = "mob";  
	param.attr("class",res);
	def(param);

	$(window).resize(function(){
		if(!$(".area_sitemap .top h3").is(":hidden")) res2 = "web";
		else res2 = "mob"; 
		param.attr("class",res2);
		if(res != res2){
			res = res2;  
			def(param);
		}
	}); 

	//mobile
	$('.btn_menu').on('click',function(){
		if($(this).hasClass('active')){
			$('.btn_menu').removeClass('active');
			$('body').removeClass('active');
			param.find('nav').removeClass('active');
			gnbLink.removeClass('active');	
			gnbLink.parent().find('ul').slideUp();			
			
			posY = $('body').attr('data-scroll');
			$(window).scrollTop(posY);
		}else{
			posY = window.scrollY || document.documentElement.scrollTop;
			$(this).toggleClass('active');		
			setTimeout(function() {
				 $('body').toggleClass('active');
			}, 400);			
			param.find('nav').toggleClass('active');
			gnbLink.removeClass('active');	
			gnbLink.parent().find('ul').slideUp();	
			$('body').attr('data-scroll',posY);
		}
		return false;
	});		

	function def(param){
		if(param.attr("class") == "web" ){			
			$('#header .gnb > li > a').unbind('click');
			$('#header .gnb > li > ul.detph').removeAttr('style');
			$('.btn_menu, body, #header nav').removeClass('active');
			gnbLink.removeClass('active');
			
			gnbLink.hover(function() {
				if(param.attr("class") == "web" || param.attr("class") && "web"){
					$(this).addClass("active").parent().addClass("active"); 
					$(this).parent().hover(function() {
					}, function(){     
					   $(this).removeClass("active", function(){
							$(this).parent().find("a").removeClass("active");
					   });    
					}); 
					  //하위메뉴가 없을경우  a에 active 추가 없음
					if(!($(this).parent().find("ul").length > 0)) {
					   $(this).parent().removeClass("active").find("a").removeClass("active");
					}
				}
			});
			
		} else if (param.attr("class") == "mob"){ 
            $('.area_sitemap .gnb > li').off('mouseenter mouseleave');
			$('.area_sitemap .gnb > li').each(function(){
                var gnbS = $('.area_sitemap .gnb > li');
				var gnbA = $(this).children('a');
				if($(this).children('ul').length > 0){
					gnbA.on('click',function(e){
						e.preventDefault();
						gnbS.children('ul').stop().slideUp();
						$(this).addClass('active');
						$(this).siblings('a').addClass('active');
						$(this).parent().children('ul').stop().slideDown();
						return false;
					});
				}
			});
		}		
	}
}

function gallery(){	

    //   메인 리뷰
    $('.con_gallery .list').slick({
        dots: false,
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        prevArrow: $('.arr_prev'),
		nextArrow: $('.arr_next'),
        responsive: [
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    // arrows: false,
                    // dots: true,
                }
            },
            {
                breakpoint: 1240,
                settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
                // arrows: false,
                // dots: true,
                }
            },
            {
                breakpoint: 1024,
                settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                // arrows: false,
                // dots: true,
                }
            },
            {
                breakpoint: 960,
                settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                // arrows: false,
                // dots: true,
                }
            }
        ]
    });
}

//tab 
function mainTab(){
    $(".tab_content").hide(); //전체 컨텐츠 숨김
    $(".notice_list .tab_content:first").show(); //링크 탭
 
    $("ul.tab li").click(function() {
		
      $(".tab_content").hide();
      var activeTab = $(this).attr("rel"); 
      $("."+activeTab).fadeIn();		
		
      $("ul.tab li").removeClass("on");
      $(this).addClass("on");

    });	
}

//auto search
$(function() {
    var searchSource = [   
    "창경궁.서울대학교병원",
    "명륜3가.성대입구",
    "종로2가.삼일교",
    "혜화동로터리.여운형활동터",
    "서대문역사거리",
    "서울역사박물관.경희궁앞",
    "서울역사박물관.경희궁앞",
    "광화문",
    "광화문1",
    "종로1가",
    "종로1가",
    "종로2가",
    "종로2가",]; // 배열 생성
    
    $('.autoSearch').autocomplete({ // autocomplete 구현 시작부
        source : searchSource, //source 는 자동완성의 대상
        select : function(event, ui) { // item 선택 시 이벤트
            console.log(ui.item);
        },
        focus : function(event, ui) { // 포커스 시 이벤트
            return false;
        },
        minLength : 1, // 최소 글자 수
        autoFocus : true, // true로 설정 시 메뉴가 표시 될 때, 첫 번째 항목에 자동으로 초점이 맞춰짐
        classes : { // 위젯 요소에 추가 할 클래스를 지정
            'ui-autocomplete' : 'highlight'
        },
        delay : 500, // 입력창에 글자가 써지고 나서 autocomplete 이벤트 발생될 떄 까지 지연 시간(ms)
        disable : false, // 해당 값 true 시, 자동완성 기능 꺼짐
        position : { my : 'right top', at : 'right bottom'}, // 제안 메뉴의 위치를 식별
        close : function(event) { // 자동완성 창 닫아질 때의 이벤트
            console.log(event);
        }
    });
});

