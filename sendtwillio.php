<?php

/*
Plugin Name: Sendtwillio
Plugin URI: https://sendtwillio.com
Description: A wordpress plugin for sending bulk SMS using Twilio
Version:  1.0.0
Author: Muhammad Bilal Jamil
*/

require_once(plugin_dir_path(__FILE__) . '/twillio-lib/src/Twilio/autoload.php');

use Twilio\Rest\Client;

class Sendtwillio
{

    public function send_message($to, $message, $from)
    {
        $TWILIO_SID = get_option('twilio_account_sid', '');
        $TWILIO_TOKEN = get_option('twilio_auth_token', '');

        if (empty($from))
            $from = get_option('twilio_number', '');

        try {
            $client = new Client($TWILIO_SID, $TWILIO_TOKEN);
            $response = $client->messages->create(
                $to,
                array(
                    "from" => $from,
                    "body" => $message
                )
            );
            echo $response;
            self::DisplaySuccess();
        } catch (Exception $e) {
            self::DisplayError($e->getMessage());
        }
    }

    /**
     * Designs for displaying Notices
     *
     * @since    1.0.0
     * @access   private
     * @var $message - String - The message we are displaying
     * @var $status   - Boolean - its either true or false
     */
    public static function adminNotice($message, $status = true)
    {
        $class =  ($status) ? "notice notice-success" : "notice notice-error";
        $message = __($message, "sample-text-domain");
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }

    /**
     * Displays Error Notices
     *
     * @since    1.0.0
     * @access   private
     */
    public static function DisplayError($message = "Aww!, there was an error.")
    {
        add_action('adminNotices', function () use ($message) {
            self::adminNotice($message, false);
        });
    }

    /**
     * Displays Success Notices
     *
     * @since    1.0.0
     * @access   private
     */
    public static function DisplaySuccess($message = "Successful!")
    {
        add_action('adminNotices', function () use ($message) {
            self::adminNotice($message, true);
        });
    }
}

/**
 * Sends a standard text message to the supplied phone number
 * @param $to | Recipient of sms message
 * @param $message | Message to recipient
 * @param $from | Twilio number for WordPress to send message from
 * @return array | $response
 * @since 0.1.0
 */

function twilio_send_message($to, $message, $from="")
{
   $TwillioInstance = new Sendtwillio();
   return $TwillioInstance->send_message($to, $message, $from);
}


/**
 * Builds the Twilio settings menus 
 * @since 0.1.0
 */
function twilio_admin_menu()
{
    add_options_page('Twilio', 'Twilio', 'manage_options', 'twilio', 'twilio_page_settings');
}

add_action('admin_menu', 'twilio_admin_menu');


/**
 * Displays the 'Home' page in settings
 * @since 0.1.0
 */
function twilio_page_settings()
{
    include_once('pages/settings.php');
}

/**
 * Saves the settings from the options pages for Twilio
 * @since 0.1.0
 */
function twilio_page_save_settings()
{

    if (isset($_GET['action']) && ($_GET['action'] == 'update')) {

        if ($_GET['page'] == 'twilio') {

            update_option('twilio_account_sid', $_GET['accountSID']);
            update_option('twilio_auth_token', $_GET['authToken']);
            update_option('twilio_number', $_GET['twilio_number']);
        }

        // Redirect back to settings page after processing
        $goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
        wp_redirect($goback);
    }
}

add_action('init', 'twilio_page_save_settings');


function send_message_button()
{
    echo "<button id='send_sms_message'>Send SMS</button>";
    echo '<script>
             jQuery(document).ready(function($) {
                console.log("Jquery is working");
                $("#send_sms_message").on("click", function() {
            var data = {
                "action": "send_sms_message",
            };
            console.log("message Data :", data);
            $.post("http://localhost/offers/wp-admin/admin-ajax.php", data, function(response) {
                alert("Response: " + response);
            });
        });
    });
</script>';
}
add_shortcode('send_message_button', 'send_message_button');

function send_sms_message()
{
    $otp = random_int(0, 999999);
    $otp = str_pad($otp, 6, 0, STR_PAD_LEFT);
    echo $otp;
    // twilio_send_message('+923026842597', $otp);
}

add_action("wp_ajax_send_sms_message", "send_sms_message");
add_action("wp_ajax_nopriv_send_sms_message", "send_sms_message");
