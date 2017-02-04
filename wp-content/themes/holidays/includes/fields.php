<?php

class Kanda_Fields {

    private $active;
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

        if( ! $this->active ) return;

        $this->add_option_pages();

        if( is_admin() ) {
            add_filter( 'acf/update_value/name=send_activation_email', array( $this, 'send_activation_email' ), 10, 3 );
            add_filter( 'acf/update_value/name=kanda_developer_email', array( $this, 'sanitize_developer_email' ), 10, 3 );
        }

    }

    /**
     * Check acf plugin status
     */
    private function check_activity() {
        if( ! function_exists( 'is_plugin_active' ) ) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $this->active = is_plugin_active( $this->plugin );
    }

    /**
     * Add option pages
     */
    private function add_option_pages() {

        $option_pages = array(
            array(
                'parent' => array(
                    'page_title' => esc_html__( 'General Options', 'kanda' ),
                    'menu_title' => esc_html__( 'Options', 'kanda' ),
                    'menu_slug'     => 'kanda_go',
                    'capability' => 'edit_posts',
                    'icon_url'   => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMzAwLjAwMDAwMHB0IiBoZWlnaHQ9IjMwMC4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDMwMC4wMDAwMDAgMzAwLjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjEzLCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxNQo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwzMDAuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNMTI5MCAyOTg2IGMtMzUzIC01NSAtNjg3IC0yMzUgLTkxMyAtNDkyIC0zMjYgLTM3NCAtNDUwIC04ODEgLTMzMQotMTM1OCA2NiAtMjY1IDE5NCAtNDg4IDM5OCAtNjkyIDIyMiAtMjIxIDQ1OCAtMzQ5IDc2NiAtNDE1IDE1MSAtMzMgNDM4IC0zMwo1ODcgLTEgNjE5IDEzNCAxMDg5IDYyMyAxMTg4IDEyMzcgMjAgMTIxIDE5IDM1NyAtMSA0NzYgLTU1IDMxOSAtMTk5IDU5NQotNDI5IDgyNCAtMjA2IDIwNSAtNTAwIDM1OSAtNzgwIDQxMSAtMTE5IDIxIC0zNzUgMjcgLTQ4NSAxMHogbTQ0NyAtMjM1IGM1MTYKLTEwMCA5MTcgLTUwMSAxMDIwIC0xMDE4IDIzIC0xMTggMjMgLTM1NSAtMSAtNDc2IC00NyAtMjQyIC0xNTQgLTQ0MyAtMzM2Ci02MzAgLTI2MyAtMjcyIC01NTAgLTM5NyAtOTEzIC0zOTcgLTM2NyAwIC02NDIgMTE0IC05MDIgMzc1IC0yMDAgMTk5IC0zMTUKNDEzIC0zNjAgNjY5IC0yMCAxMTUgLTIwIDMzNyAwIDQ1MiA0NiAyNjIgMTc3IDUwMCAzODQgNjk2IDE4NCAxNzYgNDAzIDI5MAo2MzcgMzMyIDExOSAyMiAzNTIgMjAgNDcxIC0zeiIvPgo8cGF0aCBkPSJNODM4IDE1MTAgbDIgLTgzMCAxMTAgMCAxMTAgMCAwIDI4OSAwIDI5MCAxMzAgMTIzIGM3MiA2OCAxMzYgMTIzCjE0MiAxMjMgNiAwIDE0MiAtMTg2IDMwMiAtNDEyIGwyODkgLTQxMyAxNDQgMCBjNzkgMCAxNDMgMSAxNDMgMyAwIDEgLTE2MgoyMjMgLTM2MCA0OTIgLTE5OCAyNzAgLTM1OSA0OTIgLTM1OSA0OTUgMCAzIDE1NSAxNTQgMzQzIDMzNSBsMzQ0IDMzMCAtMTQ1IDMKLTE0NCAzIC00MTIgLTQwNyAtNDEyIC00MDYgLTMgNDA2IC0yIDQwNiAtMTEzIDAgLTExMiAwIDMgLTgzMHoiLz4KPC9nPgo8L3N2Zz4K',
                    'redirect'   => false
                ),
                'children' => array(
                    'kanda_front' => array(
                        'page_title'    => esc_html__( 'Front Options', 'kanda' ),
                        'menu_title'    => esc_html__( 'Front', 'kanda' ),
                        'redirect'      => false
                    ),
                    'kanda_email' => array(
                        'page_title'    => esc_html__( 'Email Options', 'kanda' ),
                        'menu_title'    => esc_html__( 'Emails', 'kanda' ),
                        'redirect'      => false
                    )
                )
            )
        );

        foreach( $option_pages as $page ) {
            $parent = acf_add_options_page( $page['parent'] );

            foreach( $page['children'] as $slug => $options ) {
                $options = array_merge( $options, array( 'parent_slug' => $parent['menu_slug'], 'menu_slug' => $slug ) );

                acf_add_options_sub_page( $options );
            }

        }

    }

    /**
     * Get option
     *
     * @param $name
     * @param bool|false $default
     * @return bool
     */
    public function get_option( $name, $default = false ) {
        static $options;
        $options_prefix = 'kanda_';

        if( is_null( $options ) ) {
            global $wpdb;

            $prefix = 'options_' . $options_prefix;

            $query = $wpdb->prepare(
                "SELECT `option_name` as `name`, `option_value` as `value`
              FROM `{$wpdb->options}`
             WHERE `option_name` LIKE '%s'", ( $prefix . '%' )
            );

            $results = $wpdb->get_results( $query );
            foreach( $results as $row ) {
                $key = str_replace( $prefix, '', $row->name );
                $options[ $key ] = $row->value;
            }
        }

        $name = str_replace( $options_prefix, '', $name );

        return isset( $options[ $name ] ) ? maybe_unserialize( $options[ $name ] ) : $default;
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

                $subject = kanda_fields()->get_option( 'kanda_email_profile_approved_subject' );
                $message = kanda_fields()->get_option( 'kanda_email_profile_approved_body' );
                $variables = array( '{{LOGIN_URL}}' => sprintf( '<a href="%1$s">%1$s</a>', kanda_url_to( 'login' ) ) );

                $sent = kanda_mailer()->send_user_email( $user->user_email, $subject, $message, $variables );
                if( ! $sent ) {
                    Kanda_Log::log( sprintf( 'Error sending email to user for account activation notification. Details: user_id=%d', $user->ID ) );
                }
            }

            // Set back to 0 to give resend functionality
            $value = 0;

        }

        return $value;
    }

    /**
     * Sanitize developer email field to contain correct multiple email addresses
     *
     * @param $value
     * @param $post_id
     * @param $field
     * @return array|string
     */
    public function sanitize_developer_email( $value, $post_id, $field ) {
        if( $value ) {
            $value = explode( ',', $value );
            $value = array_filter( array_map( 'trim', $value ) );

            $value = implode( ', ', $value );
        }

        return $value;
    }

}

/**
 * Get fields instance
 *
 * @return Kanda_Fields
 */
function kanda_fields() {
    return Kanda_Fields::get_instance();
}

kanda_fields();