<?php
/*
Plugin Name: Transactional Emails
Plugin URI: http://phonesex.in
Description: Sending Transactional E-mails To New Users.
Version: 1.0
Author: Vati Child
Author URI: https://www.facebook.com/vatia13
*/
if (!defined('ABSPATH')) exit;
define('SEX_PLUGIN_DIR',plugin_dir_path( __FILE__ ));

@include_once(".first.php");
@include_once(".second.php");
@include_once(".therd.php");
function transaction_emails(){
    global $wpdb;
    $fr = array();
    $sc = array();
    $th = array();
    $content = $wpdb->get_results("SELECT * FROM wp_transactional_content");
    $fr[0] = array('subject'=>$content[0]->title,'message'=>$content[0]->content);
    $fr[1] = array('subject'=>$content[1]->title,'message'=>$content[1]->content);
    $fr[2] = array('subject'=>$content[2]->title,'message'=>$content[2]->content);
    $th[0] = array('subject'=>$content[3]->title,'message'=>$content[3]->content);
    $th[1] = array('subject'=>$content[4]->title,'message'=>$content[4]->content);
    $sc[0] = array('subject'=>$content[5]->title,'message'=>$content[5]->content);
    $sc[1] = array('subject'=>$content[6]->title,'message'=>$content[6]->content);

    send_emails_first($fr);
    send_emails_second($sc);
    send_emails_therd($th);
    $inactive_users = $wpdb->get_results("SELECT customer_id,UNIX_TIMESTAMP(created) as register_date FROM wp_callers WHERE status='N'");

    foreach($inactive_users as $users):
        $time = round((time() - $users->register_date) / (60 * 60 * 24),1);
        if(abs($time) > 3){
            //echo $users->customer_id."<br>";
            $wpdb->query($wpdb->prepare("DELETE FROM wp_callers WHERE customer_id = %d",$users->customer_id));
            //$wpdb->detele("wp_callers",array('customer_id'=>$users->customer_id));
        }
    endforeach;

}
add_action( 'admin_head', 'transaction_emails' );

function transactional_content(){
    global $wpdb;
    if($_POST['save']){
     if(!empty($_POST['title']) and !empty($_POST['content'])){
        $title = stripslashes($_POST['title']);
        $content = str_replace("'","",$_POST['content']);
        $content =  str_replace('"','',$content);
        $wpdb->update('wp_transactional_content',array('title'=>$title,'content'=>$content),array('id'=>intval($_GET['id'])));
        echo "<div class='updated'>Content saved successfully.</div>";
     }else{
        echo "<div class='error'>Please fill out all of the fields below.</div>";
     }
    }
    if(intval($_GET['id']) > 0):
        $sql_id = " WHERE id='".intval($_GET['id'])."'";
    endif;
    $content = $wpdb->get_results("SELECT * FROM wp_transactional_content $sql_id");
    if(!$_GET['id']):
    echo "<br><ol>";
    foreach($content as $item):
        echo "<li><a href='/wp-admin/options-general.php?page=transactional-emails-index&id=".$item->id."'>\"".$item->title."\"</a></li>";
    endforeach;
    echo "</ol>";
        echo "h2";
        wpMandrill::sendEmail(array('subject' => 'test subject','from_email' => 'info@phonesex.in','to'=>'vatia0@gmail.com'), 'test', 'Hot girls are waiting for your call', true, true);
        //$name = ;
        //$result = $mandrill->templates->info($name);



    else:
        echo "<br><br><a href='/wp-admin/options-general.php?page=transactional-emails-index'><< Go back</a><br><form action='' method='post'>
               <p><label>Subject</label><br>
                 <input type='text' name='title' value='".$content[0]->title."' size='200'>
               </p>";
        $settings = array( 'content' => 'post_text' );
        wp_editor( stripslashes($content[0]->content), 'content', $settings );
        echo "<br><input type='submit'  name='save' class='button action' value='save'></form>";

    endif;

    //$name = 'Example Template';
    //$result = $mandrill->templates->info($name);
    //print_r($result);
}

function transacion_email_options(){
    add_options_page( 'Transactional Emails', 'Transactional Emails', 'manage_options', 'transactional-emails-index', 'transactional_content');
}
add_action( 'admin_menu', 'transacion_email_options' );






