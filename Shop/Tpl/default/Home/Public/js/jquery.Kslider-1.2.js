/*
 *  author:		xujiantao - http://www.xujiantao.com
 *
 *	version:	Kslider 1.2 - 2013-12-4
 *
 */
(function($){
    $.fn.Kslider = function(options)
    {
        var SLIDER = $(this);
        var START_INDEX = 0;
        var LI_SIZE = $('li', SLIDER).size();
        var END_INDEX = LI_SIZE - 1;

        var options = $.extend({}, $.fn.Kslider.defaults, options);

        function btnActiveOrAltActive(ranking)
        {
            if($('#altbox').length > 0)
            {
                var alt = SLIDER.find('li img').eq(ranking - 1).attr('alt');
                SLIDER.find('#altbox').text(alt);
            }
            if($('#btnList').length > 0)
            {
                $('#btnList span', SLIDER).attr('class', options.numBtnSty).eq(-ranking).attr('class', options.numBtnSty + '_active');
            }
        }

        var animates = function(animateType, direct)
        {
            if(animateType == 'NEXT')
            {
                START_INDEX = START_INDEX >= END_INDEX ? 0 : START_INDEX + 1;
            }
            else if(animateType == 'PREV')
            {
                START_INDEX = START_INDEX <= 0 ? END_INDEX : START_INDEX - 1;
            }

            if(direct)
            {
                var thisDirect = START_INDEX;
            }
            else
            {
                var thisDirect = START_INDEX + 1;
            }

            btnActiveOrAltActive(thisDirect);

            var spacing = (thisDirect - 1) * parseInt(options.effect == 'horizontal' ? options.widthVal : options.heightVal);

            $('ul', SLIDER).trigger('fn_' + options.effect,[-spacing]);
        }

        $('#btnList span').live('click', function(){
            START_INDEX = parseInt($(this).text());
            animates(null, true);
        });

        $('.sliderBtn', SLIDER).live('click', function(){
            animates($(this).attr('type').toUpperCase(), false);
        });

        $('ul', SLIDER).bind('fn_' + options.effect,function(event, extent){
            switch(options.effect)
            {
                case 'horizontal':
                    $(this).stop().animate({marginLeft:extent}, options.speeds);
                    break;

                case 'fade':
                    $(this).hide().css('margin-top',extent).fadeIn('slow');
                    break;

                case 'vertical':
                    $(this).stop().animate({marginTop:extent}, options.speeds);
                    break;

                case 'none':
                    $(this).css('margin-top', extent);
                    break;
            }
        });

        return this.each(function()
        {
            var ulWidthParam = 1;
            if(options.effect == 'horizontal')
            {
                ulWidthParam = LI_SIZE;
            }

            $('ul', SLIDER).width(options.widthVal * ulWidthParam);


            if(SLIDER.attr('id'))
            {
                var sliderDom = '#' + SLIDER.attr('id');
            }
            else
            {
                var sliderDom = '.' + SLIDER.attr('class');
            }

            $(sliderDom + ', ' + sliderDom + ' li').width(options.widthVal).height(options.heightVal);

            var firstAlt = $(SLIDER).find('li img').eq(0).attr('alt')
            $(SLIDER).find('#altbox').text(firstAlt);

            var btnSize = LI_SIZE;
            for(var i=1; i <= btnSize; btnSize--)
            {
                var numBtnClassName = options.numBtnSty;
                if(btnSize == 1)
                {
                    numBtnClassName = options.numBtnSty + '_active';
                }
                $(SLIDER).find('#btnList').append('<span class="' + numBtnClassName + '">' + btnSize + '</span>');
            }

            if(options.autoPlay == 1)
            {
                var picTimer;
                $(SLIDER).hover(function(){

                    clearInterval(picTimer);

                },function(){

                    picTimer = setInterval(function(){
                        animates('NEXT', false);
                    },options.delays);

                }).trigger('mouseleave');
            }
        });
    };

    $.fn.Kslider.version = '1.2';

    $.fn.Kslider.defaults = {
        autoPlay        :        1,
        speeds          : 		 400,
        preNexBtnShow   :        1,
        delays          :        4000,
        widthVal        :        500,
        heightVal       :        200,
        effect          :	     'vertical',    // horizontal、vertical、fade、none
        numBtnSty       :        'num'
    };
})(jQuery);