jQuery(document).ready(function($){


    $('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();

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


    $('.add_bookmark').click(function(e){
        e.preventDefault();
        $.post( "/bookmarks/add", {user_id : $("#user_id").val()}, function(data){
            if(data == 1){
                $('.add_bookmark').text('Add to BM');
            }else{
                $('.add_bookmark').text('Remove to BM');
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
        }else {
            e.preventDefault();
        }
    });

    $('.ajax_buhlikes').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'), function(data) {
            if(data[0] == 1){
                $('#buh_link_' + data[2]).html(data[1] + "&nbsp<img src=../images/vine2.png height='50px' width='20px'>");
            }else{
                $('#buh_link_' + data[2]).html(data[1] + "&nbsp<img src=../images/vine3.png height='50px' width='20px'>");
            }
        }, 'json');
    });

    $('.share_button').click(function(e){
        e.preventDefault();
        $.post($(this).attr('href'), {js_data: $(".share_input", $(this).parent()).val()}, function(data){
            $('.share_container').append(data);
        }, 'json');
    });







});