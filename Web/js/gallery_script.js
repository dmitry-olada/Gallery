$('#add_comment').click(function(e){
    e.preventDefault();
    $.post($(this).attr('href'), {data : $("#text_comment").val()});
    show();
});

var show = function (){
    $.ajax({
        url: 'comment/1',
        method: 'post',
        cache: false,
        success: function(html){
            var json = jQuery.parseJSON(html);
            $('.comment_display').empty();
            $(json).each(function(index, data){
                $(data).each(function(index, data){
                    $('.comment_display').append("<a style='float: left' href='/profile/" + data.users_id + "'>" + data.nick + ":&nbsp</a><p>" + data.comment + "</p>");
                });
            });
        },
        type: 'json'
    });
};

$(document).ready(function(){
    show();
    window.setInterval(show, 10000);
});

document.getElementById('links').onclick = function (event) {
    var borderless = true;
    $('#blueimp-gallery').data('useBootstrapModal', !borderless);
    $('#blueimp-gallery').toggleClass('blueimp-gallery-controls', borderless);
    event = event || window.event;
    var target = event.target || event.srcElement,
        link = target.src ? target.parentNode : target,
        options = {index: link, event: event},
        links = this.getElementsByTagName('a');
    blueimp.Gallery(links, options);
};