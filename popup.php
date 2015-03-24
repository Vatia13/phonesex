<?php
/*
Plugin Name: Call me popup
Plugin URI: http://phonesex.in
Description: Pop up: login,add funds,phone on call me
Version: 1.0
Author: Vati Child
Author URI: https://www.facebook.com/vatia13
*/

if (!defined('ABSPATH')) exit;

function get_popup_scripts(){
    wp_enqueue_style('popup',plugins_url( 'css/popup.css', __FILE__ ));
    wp_enqueue_script('popup',plugins_url( 'js/popup.js', __FILE__ ));
}
add_action('wp_enqueue_scripts','get_popup_scripts');

function get_popup_session(){
global $wpdb;
if($_REQUEST['mode']=="login"){
    $logininfo = $wpdb->get_row("SELECT * FROM wp_callers WHERE email='".trim($_POST['login_email'])."' And pass='".trim($_POST['login_pass'])."'");
    if($logininfo){
        if($logininfo->status=='Y' && $logininfo->isactive==1 ){
            if($logininfo->is_first==0){
                $wpdb->query( $wpdb->prepare("UPDATE wp_callers SET is_first = 1 WHERE id =".$logininfo->id."" ));
                $_SESSION['UID'] = $logininfo->id;
                $_SESSION['USERNAME'] = $logininfo->username;
                $_SESSION['CUSTOMERID'] = $logininfo->customer_id;
                $_SESSION['CUSTOMERPIN'] = $logininfo->pin;
                wp_redirect(get_page_link(2177));
            }else{
                $_SESSION['UID'] = $logininfo->id;
                $_SESSION['USERNAME'] = $logininfo->username;
                $_SESSION['CUSTOMERID'] = $logininfo->customer_id;
                $_SESSION['CUSTOMERPIN'] = $logininfo->pin;
                $rdrct = "https://www.phonesex.in/?enter=yes";
                wp_redirect($rdrct);
            }
        }else{
            header('Location:https://www.phonesex.in/login/');
        }
    }else{
        header('Location:https://www.phonesex.in/login/');
    }
}

}
add_action('wp_head','get_popup_session');

function get_popup_background(){
    echo '<div id="popup-background" style="z-index:99;text-align:center;display:none;top:0;left:0;background-color:#414141;opacity:0.7;position:fixed;width:100%;height:100%;"></div>';
    echo '<div id="popup-login-form" style="z-index:100;width:100%;display:none;height:100%;position:fixed;left:0;top:0;"></div>';
}

add_action('wp_footer','get_popup_background');

function get_popup_info(){
    global $wpdb;
    $out = '';
    if(!empty($_POST['name'])){
        $agent = $wpdb->get_results('SELECT * FROM wp_sex_agents WHERE permalink="'.$_POST['name'].'"');
        $chkRating = $wpdb->get_row("SELECT star_val FROM wp_ratings WHERE agent_id='".$agent[0]->agent_id."'");
        $main = unserialize($agent[0]->main);
        $img = unserialize($agent[0]->img);
        $languages = unserialize($agent[0]->languages);
        if($_SESSION['UID']){
            $fetchCredit = mysql_fetch_array(mysql_query("Select * from `wp_credits` Where id=".$_SESSION['CUSTOMERID']));
            if($fetchCredit['credits']){
                $out .= '<div class="call-me-balance">';
                $out .= '<div class="caller-name">Call '.$main['name'].' <a class="close_popup" onclick="closePop()"><b>X</b></a> </div>';
                $out .= '<table>
                         <tr>
                         <td class=td1 width="50%" >
                         <table width="100%">
                           <tr>
                             <td width="42%">
							 
                              <img style=" border-radius: 12px;    box-shadow: 0 0 6px #ccc;  border: 1px solid #d9d9d9; "src="'.content_url().'/database/img/'.$img[0].'" alt="" width="100%"/>
                              </td>
                              <td width="3%">&nbsp;</td>
                              <td valign="top">
							  
							  <p style="font-weight: 600; font-size: 15px; margin-bottom: 10px; ">'.$main['name'].'</p>	
							  ';
                $out .= get_stars($chkRating->star_val);

                $out .= '<br>';
                $out .= '<b>Age:</b> '.$agent[0]->age.' years<br>';
                $out .= '<b>Languages:</b> ';
                $i=0;
                foreach($languages as $lang): $i++;
                    $out .= $lang;
                    if($i<count($languages) - 1):
                        $out .= ",&nbsp;";
                    endif;
                endforeach;
                $out .= '<br>';

                $out .= '<br>
                              </td>
                         </tr>
                         </table>
                         <div class="clearfix"></div>
                         </td>
                         <td valign="top">';

                $out .= '<ul>
<p style="font-size: 20px;text-align: center; text-shadow: 0px -1px 0px #fff;"><img src="/wp-content/themes/phonesex/images/indian_flag.png"><u><a href="tel:000-800-100-4368">000-800-100-4368</a></u> </p>

<p style="font-size: 10px;  margin: 8px;text-align: center;">You will be prompted to enter the information below when calling in. Please have it ready prior to your call.</p>

                            
                         <li style="
    background: white;
">Your Customer Code: '.$_SESSION['CUSTOMERID'].'</li>
                         <li style="
    background: white;
">Your Access Code: '.$_SESSION['CUSTOMERPIN'].'</li>
                         <li style="
    background: white;
">'.$main['name'].'&#8216;s Box Number: '.$agent[0]->agent_id.'</li>
</ul>

<p style="font-size: 11px;text-align: center;margin-bottom: 3px;"><u><a href="tel:011-31-20-894-7000">Call from USA / Canada</a></u></p>
<p style="font-size: 11px;text-align: center;margin-bottom: 13px;"><u><a href="tel:0031-20-894-7000">Call from other countries</a></u></p>

</td>
						 
</tr></table></div>';
                die($out);
            }else{
                $out .= '<div class="call-me-balance">';
                $out .= '<div class="caller-name">Call '.$main['name'].' <a class="close_popup" onclick="closePop()"><b>X</b></a> </div>';
                $out .= '<table>
                         <tr>
                         <td class=td1 width="50%" >
                         <table width="100%">
                           <tr>
                             <td width="42%">
							 
                              <img style=" border-radius: 12px;    box-shadow: 0 0 6px #ccc; border: 1px solid #d9d9d9;"src="'.content_url().'/database/img/'.$img[0].'" alt="" width="100%"/>
                              </td>
                              <td width="3%">&nbsp;</td>
                              <td valign="top">
							  
							  <p style="font-weight: 600; font-size: 15px; margin-bottom: 10px; margin-top:5px; ">'.$main['name'].'</p>
							  ';

                $out .= get_stars($chkRating->star_val);

                $out .= '<br>';
                $out .= '<b>Age:</b> '.$agent[0]->age.' years<br>';
                $out .= '<b>Languages:</b> ';
                $i=0;
                foreach($languages as $lang): $i++;
                    $out .= $lang;
                    if($i<count($languages) - 1):
                        $out .= ",&nbsp;";
                    endif;
                endforeach;
                $out .= '<br>';
$out .= '
 
<br><br>
                                
                              </td>
                         </tr>
                         </table>
                         <div class="clearfix"></div>
                         </td>
                         <td valign="top">
                       <ul style="  text-align: center;">
 <p style=" font-size: 16px;  margin: 8px;color: red;font-weight: 600;" align="center">You have to <a href="https://www.phonesex.in/choose-package/"><u>buy minutes</u></a> to see the number of '.$main['name'].'</p>


                            
                         <li style="
    background: white;
">Your Customer Code: '.$_SESSION['CUSTOMERID'].'</li>
                         <li style="
    background: white;
">Your Access Code: '.$_SESSION['CUSTOMERPIN'].'</li>
                         <li style="
    background: white;
">'.$main['name'].'&#8216;s Box Number: '.$agent[0]->agent_id.'</li>
<div class="spacer10"></div>
<a href="https://www.phonesex.in/choose-package/" class="btn-add-funds" style="  padding: 7px 25px;">Buy Minutes</a>
</ul>


</td>
                         </tr></table></div>';
                die($out);
            }
        }else{
            $out .= '<div class="call-me-login">';
             $out .= '<table><tr><td class=td1 width=50% ><div class="login_popup">
                <div style="color: rgb(255, 0, 0); font-size: 13px;"/>
                <form action="" method="post" name="loginfrm" id="loginfrm" onsubmit="return submitLogin()">
                <input type="hidden" name="mode" value="login"/>
                <h2>Member Login</h2>
                <br/><span>Enter your E-mail:<input name="login_email"  onkeyup="fillEmail(this)" type="text" value=""/>
                </span><span>Enter Password:<input name="login_pass" onkeyup="fillPass(this)" type="password" value=""/>
                </span><span><input class="login" name="" type="submit" value="Login"/></span></form><span align="center">
                <a href="https://www.phonesex.in/member-registration/" style="color: rgb(153, 153, 153);">Not registered yet?</a>
                </span> | <span align="center"><a href="https://www.phonesex.in/forgot-password/" style="color: rgb(153, 153, 153);">Forgot password?</a>
                </span><br/><div/></div></td><td valign="top" width=50% >
				<h2>How to call?</h2>
<br>
  <p><b style=" font-size: 18px;  color: rgb(248, 122, 5);">1</b> <u><a href="https://www.phonesex.in/login/">Login</a></u> or <a href="https://www.phonesex.in/member-registration/"><u>Register</u></a> in less than a minute and activate your account</p>
  <p><b style=" font-size: 18px;  color: rgb(248, 122, 5);">2</b> Buy minutes and click on the "Call Me" button to view the number of the girl </p>
  <p><b style=" font-size: 18px;  color: rgb(248, 122, 5);">3</b> Dial the number of the girl and enter your Customer Code and Access Code</p>
  <p><b style=" font-size: 18px;  color: rgb(248, 122, 5);">4</b> Choose option 2 and enter the box number of the girl... Have fun!!</b></p><br>

  
				
                 
								 
                <a class="close_popup" onclick="closePop()"><b>X</b></a></td></tr></table>';
            $out .='</div>';
            die($out);
        }

    }
}
add_action( 'wp_ajax_get_popup_info', 'get_popup_info' );
add_action( 'wp_ajax_nopriv_get_popup_info', 'get_popup_info' );

function get_stars($rating){

    if($rating == 1){
        return '<img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />';
    }elseif($rating == 2){
        return '<img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />';
    } elseif($rating == 3){
        return '<img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />';
    } elseif($rating == 4){
        return '<img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />';
    } elseif($rating == 5){
        return'<img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />
    <img src="/wp-content/themes/phonesex/images/star_rating.png" height="18px" width="18px" />';
    }else{
        return '<img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />
    <img src="https://phonesex.a.cdnify.io/wp-content/themes/phonesex/images/star0.png" height="18px" width="18px" />';
    }

}