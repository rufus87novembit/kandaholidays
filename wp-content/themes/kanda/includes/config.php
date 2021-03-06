<?php
/**
 * Kanda Theme configuration
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Kanda_Config {

    /**
     * Role for agencies
     *
     * @var
     */
    private static $agency_role;

    /**
     * Role for reservators
     * @var
     */
    private static $reservator_role;

    /**
     * Cookie lifetimes
     *
     * @var array
     */
    private static $cookie_lifetime;

    /**
     * Transient expiration time in seconds
     *
     * @var array
     */
    private static $transient_expiration;

    /**
     * Forms validation data
     *
     * @var array
     */
    private static $validation;

    /**
     * Controllers map
     *
     * @var array
     */
    private static $controller_map;

    /**
     * Holds display date format
     * @var
     */
    private static $display_date_format;

    /**
     * Holds active providers
     *
     * @var array
     */
    public static $providers = array();

    /**
 * Spare days count for cancellation policy dates
 *
 * @var int
 */
    public static $spare_days_count = 2;

    /**
     * Email from address
     *
     * @var string
     */
    public static $email_from = 'noreply@kandaholidays.com';

    /**
     * Google recaptcha site key
     * @var string
     */
    public static $google_site_key = '6LeyMyUTAAAAAHSVJyjANkySQnfePSz2tzLKUswF';

    /**
     * Google recaptcha secret key
     * @var string
     */
    public static $google_secret_key = '6LeyMyUTAAAAAGlgwLp5mjUU-h14hV8VYnRpY6xj';

    /**
     * Google recaptcha validation endpoint
     */
    public static $google_recaptcha_validation_endpoint = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Get configuration value
     *
     * @param string $property
     * @param string $delimiter
     * @return null
     */
    static public function get( $property = '', $delimiter = '->' ) {
        if( ! $property ) {
            return null;
        }

        $property = explode( $delimiter, $property );
        $key = array_shift( $property );

        $value = self::${$key};
        foreach( $property as $p ) {
            $value = $value[ $p ];
        }

        return $value;
    }

    /**
     * Init class
     */
    static function init() {
        self::$agency_role = 'agency';
        self::$reservator_role = 'reservator';
        self::$cookie_lifetime = array(
            'authentication'    => array(
                'administrator' => 1 * DAY_IN_SECONDS,
                'reservator'    => 1 * DAY_IN_SECONDS,
                'agency'        => 1 * DAY_IN_SECONDS,
            ),
            'login'              => 1 * HOUR_IN_SECONDS,
            'register'           => 1 * HOUR_IN_SECONDS,
            'forgot_password'    => 1 * HOUR_IN_SECONDS,
            'reset_password'     => 1 * DAY_IN_SECONDS,
        );
        self::$validation = array(
            'front' => array(
                'data' => array(
                    'username_min_length' => 6,
                    'username_max_length' => 25,
                    'password_min_length' => 8,
                    'password_max_length' => 50
                ),
                'form_login' => array(
                    // key => input_id
                    'username' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'loginRegex' => esc_html__( 'Must contain only numbers and/or letters', 'kanda' )
                    ),
                    'password' => array(
                        'required' => esc_html__( 'Required', 'kanda' )
                    )
                ),
                'form_register' => array(
                    // key => input_id
                    'username' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'loginRegex' => esc_html__( 'Numbers, letters, dashes, underlines only', 'kanda' ),
                        'rangelength' => sprintf( esc_html__( 'Username must be between %1$s and %2$s characters in length', 'kanda' ), '{0}', '{1}' ),
                    ),
                    'email' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'email'    => esc_html__( 'Invalid email', 'kanda' )
                    ),
                    'password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'rangelength' => esc_html__( 'Password must be between {0} and {1} characters in length', 'kanda' )
                    ),
                    'confirm_password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'equalTo' => __( 'Does not match', 'kanda' )
                    ),
                    'first_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'last_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'mobile' => array(
                        'phone_number' => esc_html__( 'Invalid mobile number', 'kanda' )
                    ),
                    'company_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'company_license' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'company_phone' => array(
                        'phone_number' => esc_html__( 'Invalid phone number', 'kanda' )
                    ),
                    'captcha' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'invalid'  => esc_html__( 'Invalid Captcha', 'kanda' ),
                    )
                ),
                'form_forgot_password' => array(
                    // key => input_id
                    'username_email' => array(
                        'required' => esc_html__( 'Required', 'kanda' )
                    ),
                ),
                'form_reset_password' => array(
                    // key => input_id
                    'password'          => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'rangelength' => sprintf( esc_html__( 'Password must be between %1$s and %2$s characters in length', 'kanda' ), '{0}', '{1}' )
                    ),
                    'confirm_password'  => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'equalTo' => __( 'Does not match', 'kanda' )
                    )
                ),
            ),
            'back' => array(
                'data' => array(
                    'username_min_length' => 6,
                    'username_max_length' => 25,
                    'password_min_length' => 8,
                    'password_max_length' => 50
                ),
                'form_edit_profile' => array(
                    // key => input_id
                    'user_email' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'email'    => esc_html__( 'Invalid email', 'kanda' )
                    ),
                    'first_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'last_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'mobile' => array(
                        'phone_number' => esc_html__( 'Invalid mobile number', 'kanda' )
                    ),
                    'company_phone' => array(
                        'phone_number' => esc_html__( 'Invalid phone number', 'kanda' )
                    ),
                    'company_website' => array(
                        'url' => esc_html__( 'Invalid URL', 'kanda' )
                    )
                ),
                'form_edit_password' => array(
                    'old_password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'new_password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'rangelength' => esc_html__( 'Password must be between {0} and {1} characters in length', 'kanda' )
                    ),
                    'confirm_password' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'equalTo' => __( 'Does not match', 'kanda' )
                    ),
                ),
                'form_hotel_search' => array(
                    // key => input_id
                    'start_date' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'jquery_ui_datepicker' => esc_html__( 'Invalid date', 'kanda' ),
                    ),
                    'end_date' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'jquery_ui_datepicker' => esc_html__( 'Invalid date', 'kanda' ),
                    ),
                    'nights_count' => array(
                        'required'   => esc_html__( 'Required', 'kanda' )
                    ),
                    'rooms_count' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    )
                ),
                'form_create_booking' => array(
                    // key => input_id
                    'title' => array(
                        'required' => esc_html__( 'Required', 'kanda' )
                    ),
                    'first_name' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'last_name' => array(
                        'required'   => esc_html__( 'Required', 'kanda' )
                    ),
                    'age' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    ),
                    'gender' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                    )
                ),
                'form_booking_email_details' => array(
                    // key => input_id
                    'email_address' => array(
                        'required' => esc_html__( 'Required', 'kanda' ),
                        'email' => esc_html__( 'Invalid Email Adrress', 'kanda' ),
                    ),
                )
            )
        );
        self::$controller_map = array(
            'auth'          => 'login|register|forgot|reset',
            'profile'       => 'edit|password|photo',
            'hotels'        => 'index|results|view_hotel',
            'index'         => 'dashboard'
        );
        self::$display_date_format = get_option( 'date_format' );
    }

}

Kanda_Config::init();