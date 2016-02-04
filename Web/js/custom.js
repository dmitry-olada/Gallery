jQuery(document).ready(function($){



    /***************Disguise Flash*********************/

    setTimeout(function(){$('.box').fadeOut(1000)},3000);

	/************** Scroll Navigation *********************/
	// Не открывает ссылки лкм тогда
	/*$('.navigation').singlePageNav({
        currentClass : 'active'
    });*/


    /************** Responsive Navigation *********************/

	$('.menu-toggle-btn').click(function(){
        $('.responsive-menu').stop(true,true).slideToggle();
    });


    $('#add_bookmark').click(function(){
        $.post( "/bookmarks/add", {user_id : $("#user_id").val()}, function(data){
        }, "json");
    });

    $('.ajax_btn1').click(function(e) {
        e.preventDefault();
        $.post($(this).attr('href'), {data : $(".photo_change_name", $(this).parent()).val()});
    });

    $('.ajax_btn').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'));
    });

    $('.ajax_buhlikes').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'));
    });






});