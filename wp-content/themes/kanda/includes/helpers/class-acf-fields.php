<?php
/**
 * Kanda Theme "ACF" plugin helper
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Kanda_Fields {

    public $active;
    private $plugin = 'advanced-custom-fields-pro/acf.php';

    /**
     * Singleton.
     */
    static function get_instance() {
        static $instance = null;
        if ( $instance == null) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->check_activity();

        if( ! $this->active ) {
            return;
        }

        if( is_admin() ) {
            add_filter( 'acf/update_value/name=send_activation_email', array( $this, 'send_activation_email' ), 10, 3 );
            add_filter('acf/prepare_field/name=hotelcode', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=hotelcity', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=hotelstarrating', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=hotelphone', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=hoteladdress', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=hotelweb', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=checkintime', array( $this, 'make_readonly' ) );
            add_filter('acf/prepare_field/name=checkouttime', array( $this, 'make_readonly' ) );

            add_filter('acf/prepare_field/name=hotel_city', array( $this, 'get_city_name' ) );
            add_filter('acf/prepare_field/name=hotelcity', array( $this, 'get_city_name' ) );
        }

    }

    /**
     * Check acf plugin status
     */
    private function check_activity() {
        if( ! function_exists( 'is_plugin_active' ) ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $this->active = is_plugin_active( $this->plugin );
    }

    /**
     * Send / resend profile activation email
     *
     * @param $value
     * @param $post_id
     * @param $field
     * @return int
     */
    public function send_activation_email( $value, $post_id, $field ) {

        if( $value ) {

            $user_id = preg_replace('/[^0-9]/', '', $post_id);
            $user = get_user_by('id', (int)$user_id);

            if ( $user ) {

                $subject = kanda_get_theme_option( 'email_profile_activation_title' );
                $message = kanda_get_theme_option( 'email_profile_activation_body' );
                $variables = array( '{{LOGIN_URL}}' => sprintf( '<a href="%1$s">%1$s</a>', kanda_url_to( 'login' ) ) );

                $sent = kanda_mailer()->send_user_email( $user->user_email, $subject, $message, $variables );
                if( ! $sent ) {
                    kanda_logger()->log( sprintf( 'Error sending email to user for account activation notification. Details: user_id=%d', $user->ID ) );
                }
            }

            // Set back to 0 to give resend functionality
            $value = 0;

        }

        return $value;
    }

    /**
     * Make field readonly
     *
     * @param $field
     * @return mixed
     */
    public function make_readonly( $field ) {
        $field['readonly'] = true;

        return $field;
    }

    /**
     * Get city human value
     *
     * @param $field
     * @return mixed
     */
    public function get_city_name( $field ) {

        if( $field['value'] ) {
            $city_name = IOL_Helper::get_city_name_from_code( $field['value'] );
            if( $city_name ) {
                $field['value'] = $city_name;
            }
        }
        return $field;
    }

    /**
     * Get hotel additional fee
     *
     * @param $hotel_post_id
     * @return mixed|null|void
     */
    public function get_hotel_additional_fee( $hotel_post_id ) {
        return get_field( 'additional_fee', $hotel_post_id );
    }

}

/**
 * Get fields helper instance
 *
 * @return Kanda_Fields
 */
function kanda_fields() {
    return Kanda_Fields::get_instance();
}

kanda_fields();