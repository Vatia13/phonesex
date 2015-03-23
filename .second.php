<?php if (!defined('ABSPATH')) exit;
function registered_users_minutes_left($days,$email,$sc=array()){
    global $wpdb;
    $headers = '';

    $check = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "transactional_emails WHERE " . $wpdb->prefix . "transactional_emails.email='" . $email . "' and " . $wpdb->prefix . "transactional_emails.option='hm'");
    if ($days >= 3 && $days < 6 && $check <= 0) {
        $subject = $sc[1]['subject'];
        $message = $sc[1]['message'];

        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "hm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }elseif($days >= 6 && $days < 9 && $check == 1){
        $subject = $sc[0]['subject'];
        $message = $sc[0]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "hm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }elseif($days >= 9 && $days < 13 && $check == 2){
        $subject = $sc[1]['subject'];
        $message = $sc[1]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "hm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }elseif($days > 13 && $check == 3){
        $subject = $sc[0]['subject'];
        $message = $sc[0]['message'];

        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "hm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }

}

function send_emails_second($sc=array()){
    global $wpdb;
    $y = date("Y");
    $m = date("m");
    $d = date("d");
    $date = mktime(0,0,0,$m,$d,$y);
    $date = $date - (30 * 24 * 60 * 60);
    //  echo date("Y-m-d",$date);
    $time = time();
    $users = $wpdb->get_results("SELECT MAX(o.order_date) as od,o.credits,c.email,c.customer_id FROM ".$wpdb->prefix."orders o,".$wpdb->prefix."callers c
                                 WHERE o.userid=c.customer_id and UNIX_TIMESTAMP(o.order_date) > ".$date." and o.payment_status = 'paid' GROUP BY o.userid ORDER BY o.order_date DESC");

    foreach($users as $item):
        $credits = $wpdb->get_var("SELECT credits FROM ".$wpdb->prefix."credits WHERE id='".$item->customer_id."'");
        $y = date("Y",strtotime($item->od));
        $m = date("m",strtotime($item->od));
        $d = date("d",strtotime($item->od));
        $paid = mktime(0,0,0,$m,$d,$y);
        $dtime = $time - $paid;
        $days = $dtime / (60 * 60 * 24);
        $days = round(abs($days),1);
        if($credits > 0){
            //echo $days . " - ". $item->email . " -  - ". $credits;
            registered_users_minutes_left($days,$item->email,$sc);
        }


       // echo "<br>";
    endforeach;

}