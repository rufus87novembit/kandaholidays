<?php
/**
 * Kanda Theme section
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

$theme_name = kanda_get_theme_name();
$kanda_customizer_defaults = kanda_get_customizer_defaults();
$section_id = 'auth_page';

/**
 * "Authorization" pages data
 */
return array(
    'section' => array(
        'id' => $section_id,
        'args' => array(
            'title'          => esc_html__( 'Pages assignment', 'kanda' ),
            'description'    => esc_html__( 'Pages assignment settings', 'kanda' ),
            'panel'          => basename( __DIR__ ),
            'priority'       => 10,
            'capability'     => 'edit_theme_options',
        )
    ),
    'fields' => array(
        'auth_page_login' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[auth_page_login]',
            'label'       => esc_html__( 'Page for "Login"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Login"', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'auth_page_login' ],
            'priority'    => 10,
        ),
        'auth_page_register' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[auth_page_register]',
            'label'       => esc_html__( 'Page for "Registration"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Registration"', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'auth_page_register' ],
            'priority'    => 11,
        ),
        'auth_page_forgot' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[auth_page_forgot]',
            'label'       => esc_html__( 'Page for "Forgot Password"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Forgot Password"', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'auth_page_forgot' ],
            'priority'    => 12,
        ),
        'auth_page_reset' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[auth_page_reset]',
            'label'       => esc_html__( 'Page for "Reset Password"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Reset Password"', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'auth_page_reset' ],
            'priority'    => 13,
        ),
        'user_page_profile' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[user_page_profile]',
            'label'       => esc_html__( 'Page for "Profile"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Reset Password"', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'user_page_profile' ],
            'priority'    => 14,
        ),
        'user_page_hotel' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[user_page_hotel]',
            'label'       => esc_html__( 'Page for "Hotel"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Hotel" actions', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'user_page_hotel' ],
            'priority'    => 15,
        ),
        'user_page_booking' => array(
            'type'        => 'dropdown-pages',
            'settings'    => $theme_name . '[user_page_booking]',
            'label'       => esc_html__( 'Page for "Booking"', 'kanda' ),
            'description' => esc_html__( 'Choose page to use for "Booking" actions', 'kanda' ),
            'section'     => $section_id,
            'default'     => $kanda_customizer_defaults[ 'user_page_booking' ],
            'priority'    => 16,
        )
        //other fields go here
    )
);