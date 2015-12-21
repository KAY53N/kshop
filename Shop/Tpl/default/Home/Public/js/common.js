/**
 * Created by xujiantao on 13-12-5.
 */
$(function(){
    $('#Kslider').Kslider();
    
    $('.m-cat-depth-1').hover(function(){
        $(this).find('.m-cat-popup').css({
            'display':'block',
            'margin-left':'130px',
            'margin-top':'-33px'
        });
        $(this).addClass('current');
    },function(){
        $(this).find('.m-cat-popup').css('display', 'none');
        $(this).removeClass('current');
    });
    $('.top_msg').css('opacity', '0.6');
    $('.top_msg').show();
});