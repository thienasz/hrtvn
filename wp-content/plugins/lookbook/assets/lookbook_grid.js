( function( $ ) {
    "use strict";
    $(function() {
        $('body').on('click','.ldraggable',function(){
           var url = $(this).find('a').attr('href');
           window.location = url;
        });
        var wall = new freewall("#freewall");
        var cellW = 330;
        var cellH = 415;
        if($(window).width() < 330)
        {
            cellW = $(window).width();
        }
        wall.reset({
            selector: '.cell',
            animate: true,
            cellW: cellW,
            cellH: cellH,
            gutterX: 30, // width spacing between blocks;
            gutterY: 30,
            onResize: function() {
                wall.fitWidth();
            },
            onBlockFinish: function(item,block,setting){
                if(typeof item != 'undefined')
                {
                    var nWidth = item.width;
                    var Width = $(this).attr('data-cellw');

                    var nHeight = item.height;
                    var Height = $(this).attr('data-cellh');
                    var i = 0;
                    $(this).children('.ldraggable').each(function(){
                        var oleft = $(this).attr('data-left');
                        var top = $(this).attr('data-top');
                        var tmp = parseFloat(nWidth)/parseFloat(Width);
                        var left = parseFloat(oleft)*tmp;

                        $(this).css('left',left+'px');
                        var tmp = nHeight / Height;
                        var top = parseFloat(top)*tmp + i*20;
                        $(this).css('top',top+'px');
                        i++;
                    });
                }

            }
        });
        wall.fitWidth();

        $('#load-more').on('click','a',function(){
            var paged = parseInt($(this).attr('paged')) + 1;
            var current = $(this);
            $.ajax({
                url: look_ajax_url,
                type: 'post',
                data:{paged:paged,action:'lookbook_more'},
                dataType: 'html',
                success:function(data){

                    if(data.trim().length < 20)
                    {
                        $('#load-more').hide();
                    }else{
                        $('.free-wall').append(data);
                        wall.fitWidth();
                        current.attr('paged',paged + 1);
                    }

                }
            });
        });

    });
} )( jQuery );