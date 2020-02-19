<?php
/**
Plugin Name: کد تخفیف خودکار | وبینو
Plugin URI: http://webino.site
Description: افزونه ویژه ساخت کد تخفیف ووکامرس میباشد ، شورت کد را از صفحه تنظیمات افزونه برداشته و در یک برگه قرار دهید
Author: webino 
Author URI: https://webino.site
Version: 1.0.0

 */



defined('ABSPATH')||die;
include_once "admin/Settings.php";


    function function_new_copon_webino(){
        global $current_user;
        get_currentuserinfo();
        $user_email = $current_user->user_email;
        $user_id = $current_user->user_id;
// email_check
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'shop_coupon',
            'post_status' => 'publish',

        );

        $coupons = get_posts($args);
		$email_check=false ;
        foreach ($coupons as $key => $value) {
            $webino_email_check = $value->post_excerpt . ',';
			$webino_user_check =  $value->post_title ;
            if(strpos($webino_email_check,$user_email) !== false) {
                $email_check=true ;
				$copoun_title_webino=$webino_user_check;
            }
        }
        if ($email_check) {
			echo '<div class="copoun-auto"><span>'.'کد تخفیف ویژه شما : '. $copoun_title_webino . '</span></div>';
			
        } else {

//coupon
            $prefix_cop = prefix_copoun();
            $coupon_code = $prefix_cop . rand(10, 800);
            if (isset($coupon_code)) {
                define("copon_code", $coupon_code);
            }
            if ($coupon_code == copon_code) {
            }
            echo '<div class="copoun-auto"><span>'.'کد تخفیف ویژه شما : '. copon_code . '</span></div>';

            $amount = off_copoun(); // Amount
            $discount_type = 'percent';
            $coupon = array(
                'post_title' => copon_code,
                'post_content' => '<b>',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'shop_coupon',
                'post_excerpt' => off_copoun().' درصد تخفیف برای '.$user_email,
            );

            $new_coupon_id = wp_insert_post($coupon);
            $day_ex = '+' . expiration_copoun() . 'days';
// Add meta
            update_post_meta($new_coupon_id, 'discount_type', $discount_type);
            update_post_meta($new_coupon_id, 'coupon_amount', $amount);
            update_post_meta($new_coupon_id, 'individual_use', 'no');
            update_post_meta($new_coupon_id, 'product_ids', '');
            update_post_meta($new_coupon_id, 'exclude_product_ids', '');
            update_post_meta($new_coupon_id, 'usage_limit', '1');
            update_post_meta($new_coupon_id, 'expiry_date', strtotime("$day_ex"));
            update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
            update_post_meta($new_coupon_id, 'free_shipping', 'no');
            if (isset($user_email)) {
                update_post_meta($new_coupon_id, 'customer_email', $user_email);
            }
// send user_email
$masseg_enail=massege_email_copoun();
			
//user posted variables
            $message = str_replace ( '{copoun}' , copon_code ,$masseg_enail);
            $name = title_email_copoun(). " ";
            $email = address_email_copoun();


//php mailer variables

            $to = $user_email;
            $subject = subject_email();
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: ' . $name . '<' . $email . '>';

//Here put your Validation and send mail
            $sent = wp_mail($to, $subject , strip_tags($message), $headers);
            if ($sent) {
                echo '<div class="Email-result">'.'کد تخفیف با موفقیت به ایمیل شما ارسال شد'.'</div>';
            }//message sent!
            else {
				 echo '<div class="Email-result">'.'متاسفانه در ارسال ایمیل مشکلی به وجود آمد'.'</div>';
               
            }//message wasn't sent

        }
    }

        add_shortcode('webino_Auto_Coupon', 'function_new_copon_webino');




if (delete_copoun()=='yes') {

    function schedule_delete_expired_coupons()
    {
        if (!wp_next_scheduled('delete_expired_coupons')) {
            wp_schedule_event(time(), 'daily', 'delete_expired_coupons');
        }
    }

    add_action('init', 'schedule_delete_expired_coupons');

    /**
     * Trash all expired coupons when the event is triggered.
     */
    function delete_expired_coupons()
    {
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'shop_coupon',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'expiry_date',
                    'value' => current_time('Y-m-d'),
                    'compare' => '<='
                ),
                array(
                    'key' => 'expiry_date',
                    'value' => '',
                    'compare' => '!='
                )
            )
        );

        $coupons = get_posts($args);

        if (!empty($coupons)) {
            $current_time = current_time('timestamp');

            foreach ($coupons as $coupon) {
                wp_trash_post($coupon->ID);
            }
        }

        add_action('delete_expired_coupons', 'delete_expired_coupons');
    }
}
// end delete coupons
        