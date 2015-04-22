/**
 * Created by xujiantao on 13-11-22.
 */
$(document).ready(function(){
    /* 商品详情的 点击小图大图切换 */
    $(".goods_min_pic").live('click', function(){
        var mediumPic = $(this).find('img').attr('src').replace('x_', 'z_');
        var maxPic = $(this).find('img').attr("src").replace('x_', 'd_');
        $(".goods_pic_detail div img").attr({'src':mediumPic, 'jqimg':maxPic});
        $('#zoomPicList').find('span').attr('class', 'goods_min_pic');
        $(this).attr('class', 'goods_min_pic_click');
    });

    $(".jqzoom").jqueryzoom({
        xzoom: 405,
        yzoom: 395,
        offset: 10,
        position: 'right'
    });

    function goodsTabFunc(index)
    {
        if(index.length <= 0)
        {
            index = 0;
        }
        $('#goods_tab span').attr('class', 'goods_tab').eq(index).attr('class', 'goods_tab_active');
        $('.shopCon').hide().eq(index).show();
    }

    $('#goods_tab span').click(function()
    {
        goodsTabFunc($(this).index());
    });

    /* 商品数量过滤字符 */
    $('.canshu_num').keypress(function(event)
    {
        var keyCode = event.which;
        return keyCode >= 48 && keyCode <=57;
    }).focus(function(){
        this.style.imeMode='disabled';
    });
});
