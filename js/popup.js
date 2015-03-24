/**
 * Created by Vati Child on 11/26/14.
 */
(function($){
    var operators = $('.callme_btn').length;
    for(var i=0;i<operators;i++){
        var href = $('.callme_btn:eq('+i+')').attr('href');
        //alert(href);
        var str = href.split('/');
        $('.callme_btn:eq('+i+')').removeAttr('href');
        $('.callme_btn:eq('+i+')').attr('onClick',"opInfo('"+str[4]+"')");
    }

})(jQuery);
function opInfo(name){

    var url = '/wp-admin/admin-ajax.php';
    if(name !== ''){
        var data = {
            action:'get_popup_info',
            name:name
        }
        jQuery.post(url,data,function(response){
            jQuery("#popup-background").css('display','block');
            jQuery("#popup-login-form").css('display','block');
            jQuery("#popup-login-form").show();
                jQuery("#popup-login-form").html(response);
                jQuery("#popup-background").show();

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
                jQuery("#popup-login-form .call-me-login").css('width','90%','important');
                jQuery("#popup-login-form .call-me-balance").css('width','90%','important');
            }
            if( jQuery(window).width() < 800 ) {
                jQuery("#popup-login-form .call-me-login").css('width','90%','important');
                jQuery("#popup-login-form .call-me-balance").css('width','90%','important');
            }
        });
    }
    jQuery("#popup-background").click(function(){
        jQuery(this).hide();
        jQuery("#popup-login-form").html('');
        jQuery("#popup-login-form").hide();
        jQuery("#popup-login-form").css('display','none');
    });


}
function closePop(){
    jQuery("#popup-background").hide();
    jQuery("#popup-login-form").html('');
    jQuery("#popup-login-form").hide();
    jQuery("#popup-login-form").css('display','none');
}
function fillEmail(e){
    jQuery("#login_email").val(jQuery(e).val());
}
function fillPass(e){
    var pass = jQuery(e).val().replace(/./g, '*');
    jQuery("#login_pass").val(pass);
}

