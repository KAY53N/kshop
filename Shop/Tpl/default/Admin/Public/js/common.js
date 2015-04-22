/**
 * Created by xujiantao on 13-11-29.
 */
$(document).ready(function()
{
    function yesno(id)
    {
        if(confirm('id为【' + id + '】信息是否删除?'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    $('a[id="del"]').click(function(){
        return yesno($(this).parent().parent().find('#id').text());
    });

    $('#more_del').click(function(){
        if(confirm('你确定要删除所选中的新闻吗?'))
        {
            return true;
        }
        else
        {
            return false;
        }
    });

    $('#more_checkbox').click(function()
    {
        if($('.check').attr('checked'))
        {
            $(this).attr('checked', true);
            $('.check').attr('checked', false);
        }
        else
        {
            $(this).attr('checked', false);
            $('.check').attr('checked', true);
        }
    });

    $('tr td').slice(1, -1).hover(function(){
        $(this).parent().find('td').css('background', '#C1FFC1');
    },function(){
        $(this).parent().find('td').css('background', '#ffffff');
    });

    $('tr').slice(1,-1).click(function(){
        var checkbox = $(this).find("input[type=checkbox]");
        if(checkbox.attr('checked'))
        {
            checkbox.attr('checked', false);
        }
        else
        {
            checkbox.attr('checked', true);
        }
    });

    $('input[type="checkbox"]').click(function(){
        if($(this).attr('checked'))
        {
            $(this).attr('checked', false);
        }
        else
        {
            $(this).attr('checked', true);
        }
    });

    $('#username').focusout(function(){
        if($(this).val() != '')
        {
            var usernameVal = $(this).val();
            $.getJSON('<{:U("Admin-User/user_check")}>',{username:usernameVal},function(json)
            {
                if(json.status == 0)
                {
                    $('#us').empty().append('<font color=green><b>此用户未被注册！可以使用！</b></font>');
                    $('form').attr('onSubmit","return Validator.Validate(this,2)');
                }else{
                    $('#us').empty().append('<font color=red><b>此用户已被注册！请修改后重试！</b></font>');
                    $('form').attr('onSubmit', 'return false');
                }
            });
        }
        else
        {
            $('form').attr('onSubmit","return Validator.Validate(this,2)');
            $('#us').empty();
        }
    });
});