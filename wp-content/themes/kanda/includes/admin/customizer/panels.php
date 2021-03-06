<?php
/**
 * Kanda Theme panels defination
 *
 * @package Kanda_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Get panels configuration
 *
 * @return array
 */
function kanda_get_panels() {
    return array(
        'general' => array(
            'priority'    => 10,
            'title'       => esc_html__( 'General Options', 'kanda' ),
            'description' => esc_html__( 'Theme general options', 'kanda' ),
        ),
        'front_page' => array(
            'priority'    => 11,
            'title'       => esc_html__( 'Front Page', 'kanda' ),
            'description' => esc_html__( 'Theme general options', 'kanda' ),
        ),
        'emails' => array(
            'priority'    => 12,
            'title'       => esc_html__( 'Emails', 'kanda' ),
            'description' => esc_html__( 'Theme front pages', 'kanda' ),
        ),
        'banners' => array(
            'priority'    => 13,
            'title'       => esc_html__( 'Banners', 'kanda' ),
            'description' => esc_html__( 'Theme general options', 'kanda' ),
        ),
    );
}