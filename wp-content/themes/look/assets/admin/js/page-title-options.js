/**
 * Created by Vu Anh on 7/29/2015.
 */
jQuery(document).ready(function(){

    jQuery('body').on('click','.checkbox-appearance',function(){

        var checkbox = jQuery(this).closest('.checkbox-wrapper').find('input[type="checkbox"]');

        if(jQuery(this).hasClass('enable'))
        {
            jQuery(this).removeClass('enable');

            checkbox.prop('checked',true);
        }else{
            jQuery(this).addClass('enable');
            checkbox.prop('checked',false);
        }
    });

    jQuery('.sample-error').on('click','.notice-dismiss',function(){
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data:{action:'dismiss_same_notice'},
            success:function(){

            }
        });
    });

});