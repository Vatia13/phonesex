<?php if (!defined('ABSPATH')) exit;
function registered_users_without_minutes($days,$email)
{
    global $wpdb;

    $check = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "transactional_emails WHERE " . $wpdb->prefix . "transactional_emails.email='" . $email . "' and " . $wpdb->prefix . "transactional_emails.option='nm'");
    if ($days >= 3 && $days < 6 && $check <= 0) {
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::sendEmail(array('subject' => 'Just Listen','from_email' => 'info@phonesex.in','to'=>$email), 'Phonesex', 'Phonesex.in - Just Listen', true, true);
    } elseif ($days >= 6 && $days < 10 && $check == 1) {

        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::sendEmail(array('subject' => 'Special discount for new callers','from_email' => 'info@phonesex.in','to'=>$email), 'Phonesex', 'Special discount for new callers', true, true);

    } elseif ($days >= 10 && $days < 13 && $check == 2) {
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::sendEmail(array('subject' => 'We know you like ASS','from_email' => 'info@phonesex.in','to'=>$email), 'Phonesex', 'We know you like ASS', true, true);

    } elseif ($days > 13 && $check == 3) {
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::sendEmail(array('subject' => 'Special discount for new callers','from_email' => 'info@phonesex.in','to'=>$email), 'Phonesex', 'Special discount for new callers', true, true);
    }
}

function send_emails_first(){
    global $wpdb;
    $y = date("Y");
    $m = date("m");
    $d = date("d");
    $date = mktime(0,0,0,$m,$d,$y);
    $date = $date - (18 * 24 * 60 * 60);
  //  echo date("Y-m-d",$date);
    $time = time();
    $users = $wpdb->get_results("SELECT ".$wpdb->prefix."callers.* FROM ".$wpdb->prefix."callers
                                 WHERE UNIX_TIMESTAMP(".$wpdb->prefix."callers.created) > ".$date." and status='Y'");
    foreach($users as $item):
        $uid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."credits WHERE id='".$item->customer_id."'");
        $y = date("Y",strtotime($item->created));
        $m = date("m",strtotime($item->created));
        $d = date("d",strtotime($item->created));
        $h = date("h",strtotime($item->created));
        $i = date("i",strtotime($item->created));
        $created = mktime($h,$i,0,$m,$d,$y);
        $dtime = $time - $created;
        $days = $dtime / (60 * 60 * 24);
        $days = round(abs($days),1);
        if($uid <= 0){
            //echo $days . " - ". $item->email . " = ". $credits;
           registered_users_without_minutes($days,$item->email);
        }


       // echo "<br>";
    endforeach;
}