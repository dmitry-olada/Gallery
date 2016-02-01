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






});