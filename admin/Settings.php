<?php

add_action( 'admin_menu', 'webino_create_menu' );
function webino_create_menu() {
    //create new top-level menu
    add_menu_page( 'WAC', 'کد تخفیف خودکار',
        'manage_options', 'webino_main_menu', 'prowy_settings_page' ,'dashicons-tickets-alt');
    //call register settings function
    add_action( 'admin_init', 'webino_register_settings' );
}
function webino_register_settings() {
    //register our settings
    register_setting( 'prowy-settings-group',
        'prowy_options', 'prowy_sanitize_options' );
}
function prowy_sanitize_options( $input ) {
    $input['off_copoun'] =
        sanitize_text_field( $input['off_copoun'] );
    $input['expiration_copoun'] =
        sanitize_text_field( $input['expiration_copoun'] );
    $input['prefix_copoun'] =
        sanitize_text_field( $input['prefix_copoun'] );
    $input['delete_copoun'] =
        sanitize_text_field( $input['delete_copoun'] );
    $input['title_email_copoun'] =
        sanitize_text_field( $input['title_email_copoun'] );
    $input['subject_email_copoun'] =
        sanitize_text_field( $input['subject_email_copoun'] );
    $input['address_email_copoun'] =
        sanitize_text_field( $input['address_email_copoun'] );
    $input['massege_email_copoun'] =
        sanitize_textarea_field( $input['massege_email_copoun'] );
    return $input;
}
function prowy_settings_page() {
    ?>
    <div class="wrap">
        <h2>تنظیمات افزونه کد تخفیف خودکار</h2>
        <p>برای استفاده ، شورت کد <b>[webino_Auto_Coupon]  </b>در برگه یا نوشته قرار دهید و پایان فرم یا لینک دکمه را لینک برگه یا نوشته انتقال دهید</p>
        <div class="notice notice-info is-dismissible">
            <p>افزونه کد تخفیف خودکار ووکامرس ، قادر است برای موضوعات مختلف استفاده شود مانند :</p>
            <p>کد تخفیف خودکار در گراویتی فرم | کد تخفیف خودکار در کیو فرم | کد تخفیف خودکار پس از کلیک بر روی دکمه | و..</p>
        </div>
        <hr>
        <form method="post" action="options.php">
            <?php settings_fields( 'prowy-settings-group' ); ?>
            <?php $prowy_options = get_option( 'prowy_options' ); ?>
            <table class="form-table">
                <tr>
                    <th>تنظیمات کد تخفیف</th>
                    <th> </th>
                    <th>تنظیمات ایمیل</th>
                    <th> </th>
                </tr>
                <tr>
                    <td><label for="off_copoun">مقدار کد تخفیف</label></td>
                    <td>
                        <input type="number" id="off_copoun"
                               name="prowy_options[off_copoun]"
                               value="<?php echo esc_attr(
                                   $prowy_options['off_copoun'] ); ?>" placeholder="چند درصد" /></td>
                    <!-- تنظیمات ایمیل-->
                    <td><label for="title_email_copoun"> نام ارسال کننده ایمیل </label></td>
                    <td>
                        <input type="text" id="title_email_copoun"
                               name="prowy_options[title_email_copoun]"
                               value="<?php echo esc_attr(
                                   $prowy_options['title_email_copoun'] ); ?>" placeholder="مثلا : وبینو " /></td>
                </tr>
                <tr>
                    <td><label for="expiration_copoun"> تاریخ انقضا کد تخفیف (روز)</label></td>
                    <td>
                        <input type="number" id="expiration_copoun"
                               name="prowy_options[expiration_copoun]"
                               value="<?php echo esc_attr(
                                   $prowy_options['expiration_copoun'] ); ?>" placeholder="اعتبار کد تخفیف (چند روز ؟)"/></td>
                    <!-- تنظیمات ایمیل-->
                    <td><label for="subject_email_copoun">موضوع ایمیل</label></td>
                    <td>
                        <input type="text" id="subject_email_copoun"
                               name="prowy_options[subject_email_copoun]"
                               value="<?php echo esc_attr(
                                   $prowy_options['subject_email_copoun'] ); ?>" placeholder="<?php echo 'مثلا  : کد هدیه ویژه شما' ; ?>" /></td>

                </tr>
                <tr>
                    <td><label for="prefix_copoun">پیشوند کد تخفیف</label></td>
                    <td>
                        <input type="text" id="prefix_copoun"
                               name="prowy_options[prefix_copoun]"
                               value="<?php echo esc_attr(
                                   $prowy_options['prefix_copoun'] ); ?>" placeholder="مثلا : aban"/></td>
                    <!-- تنظیمات ایمیل-->
                    <td><label for="address_email_copoun"> ایمیل ارسال کننده  </label></td>
                    <td>
                        <input type="email" id="address_email_copoun"
                               name="prowy_options[address_email_copoun]"
                               value="<?php echo esc_attr(
                                   $prowy_options['address_email_copoun'] ); ?>" placeholder="<?php echo 'info@site.com' ; ?>" /></td>
                </tr>

                <tr>
                    <td><label for="delete_copoun"><b>کد تخفیف پس از پایان انقضا حذف شود؟</b></label></td>
                    <td>
                        <?php if (esc_attr(
                                $prowy_options['delete_copoun'] )=='yes'){$checked='checked';}?>
                        <input name="prowy_options[delete_copoun]" type="checkbox" id="delete_copoun"
                               value="yes" <?php echo $checked;?>/>
                    </td>
                    <!-- تنظیمات ایمیل-->
                    <td><label for="massege_email_copoun"> متن پیام ارسالی ایمیل </label></td><td>
                        <textarea id='massege_email_copoun' name='prowy_options[massege_email_copoun]' rows='7' cols='50' type='textarea'><?php echo esc_attr(
                                $prowy_options['massege_email_copoun'] ); ?></textarea>

                        <p>برای نمایش کد تخفیف از <b>{copoun}</b> در پیام استفاده کنید</p>

                    </td>
                </tr>

            </table>
            <p class="submit">
                <input type="submit" class="button-primary"
                       value="ذخیره تنظیمات" />
            </p>
        </form>
    </div>
    <?php
}



// off_copoun
function off_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['off_copoun']) ;

}
// expiration_copoun
function expiration_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['expiration_copoun']) ;

}
// delete_copoun
function delete_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['delete_copoun']) ;

}
// title_email_copoun
function title_email_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['title_email_copoun']) ;

}
// subject_email_copoun
function subject_email() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['subject_email_copoun']) ;

}
// address_email_copoun
function address_email_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['address_email_copoun']) ;

}
// massege_email_copoun
function massege_email_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['massege_email_copoun']) ;

}
// prefix_copoun
function prefix_copoun() {
    $prowy_options = get_option( 'prowy_options' );
    return esc_attr($prowy_options['prefix_copoun']) ;

}