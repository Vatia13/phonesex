<?php if (!defined('ABSPATH')) exit;
function registered_users_without_minutes($days,$email,$fr=array())
{
    global $wpdb;
    $headers = '';
    $check = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "transactional_emails WHERE " . $wpdb->prefix . "transactional_emails.email='" . $email . "' and " . $wpdb->prefix . "transactional_emails.option='nm'");
    if ($days >= 3 && $days < 6 && $check <= 0) {
        $subject = $fr[0]['subject'];
        $message = $fr[0]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    } elseif ($days >= 6 && $days < 10 && $check == 1) {
        $subject = $fr[1]['subject'];
        $message = $fr[1]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    } elseif ($days >= 10 && $days < 13 && $check == 2) {
        $subject = $fr[2]['subject'];
        $message = $fr[2]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    } elseif ($days > 13 && $check == 3) {
        $subject = $fr[1]['subject'];
        $message = $fr[1]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "nm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }
}

function send_emails_first($fr=array()){
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
           registered_users_without_minutes($days,$item->email,$fr);
        }


       // echo "<br>";
    endforeach;
}