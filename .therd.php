<?php if (!defined('ABSPATH')) exit;
function registered_users_no_minutes_left($days,$email,$th=array()){
    global $wpdb;
    $headers = '';

    $check = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "transactional_emails WHERE " . $wpdb->prefix . "transactional_emails.email='" . $email . "' and " . $wpdb->prefix . "transactional_emails.option='sm'");
    if ($days >= 1 && $days < 3 && $check <= 0) {
        $subject = $th[1]['subject'];
        $message = $th[1]['message'];

        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "sm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }elseif($days >= 3 && $days < 6 && $check == 1){
        $subject = $th[0]['subject'];
        $message = $th[0]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "sm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }elseif($days >= 6 && $days < 13 && $check == 2){
        $subject = $th[1]['subject'];
        $message = $th[1]['message'];
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "sm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }elseif($days > 13 && $check == 3){
        $subject = $th[0]['subject'];
        $message = $th[0]['message'];

        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: Phonesex.in <info@phonesex.in>" . "\r\n";

        // $send = mail($toEmail, $subject, $messageBody, $headers);
        $wpdb->insert("wp_transactional_emails", array("email" => $email, "option" => "sm"));
        wpMandrill::mail($email, $subject, $message, $headers);
    }

}


function send_emails_therd($th=array()){
    global $wpdb;
    $y = date("Y");
    $m = date("m");
    $d = date("d");
    $date = mktime(0,0,0,$m,$d,$y);
    $date = $date - (30 * 24 * 60 * 60);
    //  echo date("Y-m-d",$date);
    $time = time();
    $users = $wpdb->get_results("SELECT o.last_call,o.credits,c.email FROM ".$wpdb->prefix."credits o,".$wpdb->prefix."callers c
                                 WHERE o.id=c.customer_id and UNIX_TIMESTAMP(o.last_call) > ".$date." and o.credits <= 0 ORDER BY o.last_call DESC");

    foreach($users as $item):
        $y = date("Y",strtotime($item->last_call));
        $m = date("m",strtotime($item->last_call));
        $d = date("d",strtotime($item->last_call));
        $paid = mktime(0,0,0,$m,$d,$y);
        $dtime = $time - $paid;
        $days = $dtime / (60 * 60 * 24);
        $days = round(abs($days),1);

            //echo $days . " - ". $item->email . " -  - ". $credits;
        if($item->credits <= 0){
           registered_users_no_minutes_left($days,$item->email,$th);
        }

        // echo "<br>";
    endforeach;

}