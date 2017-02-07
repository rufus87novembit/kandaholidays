<?php

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

/**
 * Theme Name
 *
 * @return string
 */
function kanda_get_theme_name() {
    return 'kanda_theme';
}

/**
 * Get default theme option values
 *
 * @return array
 */
function kanda_get_theme_defaults() {
    static $kanda_defaults;

    // Load only once.
    if ( ! $kanda_defaults ) {

        $kanda_defaults = array(
            kanda_get_theme_name() => kanda_get_theme_customizer_default_values()
        );
    }

    return $kanda_defaults;
}

/**
 * Get theme option
 *
 * @param $option_name
 */
function kanda_get_theme_option( $option_name ) {
    $theme_name = kanda_get_theme_name();
    $theme_data = get_theme_mod( $theme_name );

    if( array_key_exists( $option_name, $theme_data ) ) {
        $option_value = $theme_data[ $option_name ];
    } else {
        $defaults = kanda_get_theme_defaults();
        $option_value = $defaults[ $option_name ];
    }

    return $option_value;
}

/**
 * Register section and fields for it
 *
 * @param $section
 * @param $fields
 */
function kanda_register_section_with_fields( $section, $fields ) {

    Kanda_Customizer::add_section( $section['id'], $section['args'] );
    foreach( $fields as $field_id => $args ) {
        Kanda_Customizer::add_field( $field_id, $args );
    }

}