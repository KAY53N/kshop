/**
 * Created by xujiantao on 13-11-22.
 */
$(document).ready(function(){

    /* 商品数量过滤字符 */
    $("#goods_num").keypress(function(event)
    {
        var keyCode = event.which;
        return keyCode >= 48 && keyCode <=57;
    }).focus(function(){
        this.style.imeMode='disabled';
    });

    $('#goods_num').live('focusout', function()
    {
        var goodsNumInput = $(this);
        /* 商品数量数据处理 */
        if($(this).val() < 1)
        {
            return $(this).val('1');
        }
        else
        {
            while($(this).val()[0] == 0)
            {
                $(this).val(function()
                {
                    return $(this).val().replace($(this).val()[0], '');
                });
            }
        }

        delete condition;
        var condition = {};
        /* 小计价格计算 */
        var sellPrice = $(this).parentsUntil('tr').parent().find('#sell_price').text();
        var goodsNum = parseInt($(this).val());
        var ajaxMinStatistics = $(this).parentsUntil('tr').parent().find('#minStatistics') //小计元素
        /*  商品总额 */
        condition['total_price'] = sellPrice*goodsNum;
        condition['buy_num'] = goodsNum;
        condition['condition_id'] = $(this).parentsUntil('tr').parent().find('#conditionId').val(); //加入购物车的商品id
        condition['goods_id'] = $(this).parentsUntil('tr').parent().find('#goodsId').val();

        //AJAX更新数量和小计
        $.getJSON(_APP_+'/cart/up_ajax', {conditionData:condition},function(json)
        {
            if(json.status == 1)
            {
                ajaxMinStatistics.text(json.data.total_price);
                $('#allPrice').text(json.data.all_price);
                //AJAX更新数量和总价成功
            }
            else if(json.status == -1)
            {
                goodsNumInput.val(json.data.cartNum);
                alert(json.info);
            }
            else{
                alert(json.info);
                //AJAX更新数量和总价失败
            }
        });
    });

    //Ajax删除商品
    $("#delGoods").live('click', function()
    {
        var deleteId = $(this).parentsUntil('tr').parent().find('#conditionId').val();
        var deleteGoods = $(this).parentsUntil('tr').parent();
        $.getJSON(_APP_+'/cart/del_goods',{condition_id:deleteId},function(json)
        {
            if(json.status == 1)
            {
                var delMinStatistics = deleteGoods.find('#minStatistics').text();
                var cartNum = parseInt($('#memberCartNum').text())-1;
                $('#memberCartNum').text(cartNum);
                $('#allPrice').text(parseFloat($('#allPrice').text() - delMinStatistics));
                deleteGoods.detach();
            }
            else
            {
                //删除失败
            }
        });
    });

    //优惠券默认值处理
    $('.favorable_number').focusin(function()
    {
        if($(this).val() == '请输入优惠券号码')
        {
            $(this).val('');
        }
    });

    $('.favorable_number').focusout(function()
    {
        if($(this).val() == '')
        {
            $(this).val('请输入优惠券号码');
        }
    });
});
