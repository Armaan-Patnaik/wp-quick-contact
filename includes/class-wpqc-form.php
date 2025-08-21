<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WPQC_Form {
    public static function init() {
        add_shortcode('wpqc_form', [__CLASS__, 'render_form']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
        add_action('wp_ajax_wpqc_submit', [__CLASS__, 'handle']);
        add_action('wp_ajax_nopriv_wpqc_submit', [__CLASS__, 'handle']);
    }
    public static function enqueue() {
        wp_enqueue_script('wpqc-ajax', plugins_url('../assets/js/wpqc-ajax.js', __FILE__), ['jquery'], null, true);
        wp_localize_script('wpqc-ajax','wpqc',[
            'ajax_url'=>admin_url('admin-ajax.php'),
            'nonce'=>wp_create_nonce('wpqc-nonce')
        ]);
        wp_enqueue_style('wpqc-style', plugins_url('../assets/css/wpqc-style.css', __FILE__));
    }
    public static function render_form() {
        ob_start(); ?>
        <form id="wpqc-form" class="wpqc-form">
            <input type="text" name="name" placeholder="Your name" required />
            <input type="email" name="email" placeholder="Your email" required />
            <textarea name="message" placeholder="Message" required></textarea>
            <button type="submit">Send</button>
        </form>
        <?php return ob_get_clean();
    }
    public static function handle() {
        check_ajax_referer('wpqc-nonce','nonce');
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $message = wp_kses_post($_POST['message'] ?? '');
        if ( empty($email) || !is_email($email) ) {
            wp_send_json_error(['message'=>'Invalid email']);
        }
        $admin = get_option('admin_email');
        $subject = "WPQC: New message from ".$name;
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        wp_mail($admin, $subject, $body);
        wp_send_json_success(['message'=>'Thanks! We received your message.']);
    }
}
