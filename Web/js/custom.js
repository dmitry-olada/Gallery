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


    $('#add_bookmark').click(function(e){
        e.preventDefault();
        $.post( "/bookmarks/add", {user_id : $("#user_id").val()}, function(data){
            if(data == 1){
                $('#add_bookmark').text('Add to BM');
            }else{
                $('#add_bookmark').text('Remove to BM');
            }
        }, "json");
    });

    $('.ajax_btn1').click(function(e) {
        e.preventDefault();
        $.post($(this).attr('href'), {data : $(".photo_change_name", $(this).parent()).val()});
    });

    $('.ajax_btn').click(function(e){
        var del = confirm('Really delete?');
        if(del){
            e.preventDefault();
            $.post("/albums/photo_delete/", {data: $(this).attr('href')});
            location.reload();
        }else{
            e.preventDefault();
        }
        //$.post($(this).attr('href'));
    });

    $('.ajax_buhlikes').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'), function(data){
            if($('.buh_text',$(this)).html() > data){
                $('.buh_image_1',$(this)).hide();
                $('.buh_image_2',$(this)).show();
            }else{
                $('.buh_image_2',$(this)).hide();
                $('.buh_image_1',$(this)).show();
            }
            $('.buh_text',$(this)).html(data);
        }.bind(this));
    });

});