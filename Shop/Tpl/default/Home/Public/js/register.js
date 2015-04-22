/**
 * Created by xujiantao on 13-11-22.
 */
$(document).ready(function(){
    $('input[name=username]').focusout(function()
    {
        if($.trim($(this).val()).length >= 6)
        {
            var usernameVal = $(this).val();
            $.getJSON(_APP_ + '/register/register_user', {username:usernameVal}, function(json){
                if(json.status == 0)
                {
                    $('#us').empty().append('<font color=green><b>此用户未被注册！可以使用！</b></font>');
                    $('form').attr('onSubmit', 'return Validator.Validate(this,2)');
                }
                else if(json.status == 1)
                {
                    $('#us').empty().append('<font color=red><b>此用户已被注册！请修改后重试！</b></font>');
                    $("form").attr('onSubmit', 'return false');
                }
                else if(json.status == -1)
                {
                    $('#us').empty().append('<font color=red><b>用户名非法！</b></font>');
                    $("form").attr('onSubmit', 'return false');
                }
            });
        }
        else
        {
            $('#us').empty();
            $('form').attr('onSubmit', 'return Validator.Validate(this,2)');
        }
    });
});
