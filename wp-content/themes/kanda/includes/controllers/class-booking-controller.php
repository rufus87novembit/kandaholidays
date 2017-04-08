<?php
if( ! class_exists( 'Base_Controller' ) ) {
    require_once ( KANDA_CONTROLLERS_PATH . 'class-base-controller.php' );
}

class Booking_Controller extends Base_Controller {

    protected $name = 'booking';
    public $default_action = 'list';

    public function __construct($post_id = 0) {
        if (!is_user_logged_in()) {
            kanda_to('login');
        }

        parent::__construct($post_id);
    }

    private function create_add_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'create_enqueue_scripts' ), 11 );
    }

    public function create_enqueue_scripts() {
        global $wp_scripts;

        $back_script = $wp_scripts->query( 'back', 'registered' );

        if( ! $back_script ) {
            return false;
        }
        if( !in_array( 'jquery-ui-datepicker', $back_script->deps ) ){
            $back_script->deps[] = 'jquery-ui-datepicker';
        }
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_localize_script( 'back', 'booking', array(
            'validation' => Kanda_Config::get( 'validation->back->form_create_booking' )
        ));
    }

    /**
     * Get request for booking
     * @param $args
     */
    public function create( $args ) {

        $this->create_add_hooks();

        $is_valid = true;
        $hotel_code = isset($_GET['hotel_code']) ? $_GET['hotel_code'] : '';
        $city_code = isset($_GET['city_code']) ? $_GET['city_code'] : '';
        $room_number = isset($_GET['room_number']) ? $_GET['room_number'] : '';
        $request_id = isset($_GET['request_id']) ? $_GET['request_id'] : '';
        $room_type_code = isset($_GET['room_type_code']) ? $_GET['room_type_code'] : '';
        $contract_token_id = isset($_GET['contract_token_id']) ? $_GET['contract_token_id'] : '';
        $room_configuration_id = isset($_GET['room_configuration_id']) ? $_GET['room_configuration_id'] : '';
        $meal_plan_code = isset($_GET['meal_plan_code']) ? $_GET['meal_plan_code'] : '';

        if (
            ! $hotel_code ||
            ! $city_code ||
            ! $room_number ||
            ! $room_type_code ||
            ! $contract_token_id ||
            ! $room_configuration_id ||
            ! $meal_plan_code ||
            ! $request_id
        ) {
            $is_valid = false;
        }

        if( $is_valid ) {

            $security = isset( $_GET['security'] ) ? $_GET['security'] : '';

            if ( wp_verify_nonce($security, 'kanda-create-booking') ) {

                $request = provider_iol()->hotels()->get_request_data($request_id);

                if ( $request ) {
                    $request_args = IOL_Helper::savable_format_to_array($request->request);
                    $adults_count = $request_args['room_occupants'][$room_number]['adults'];
                    $children_count = (bool)$request_args['room_occupants'][$room_number]['child'] ? count($request_args['room_occupants'][$room_number]['child']['age']) : 0;

                    $adults = array_fill(0, $adults_count, array(
                        'title' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'date_of_birth' => '',
                        'gender' => '',
                        'nationality' => 'AM'
                    ));

                    $children = array_fill(0, $children_count, array(
                        'title' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'date_of_birth' => '',
                        'gender' => '',
                        'nationality' => 'AM'
                    ));
                } else {
                    $is_valid = false;
                }

            } else {
                $is_valid = false;
            }
        }


        if( ! $is_valid ) {
            $this->show_404();
        }

        $this->adults = $adults;
        $this->children = $children;
        $this->hotel_code = $hotel_code;
        $this->city_code = $city_code;
        $this->room_number = $room_number;
        $this->room_type_code = $room_type_code;
        $this->contract_token_id = $contract_token_id;
        $this->room_configuration_id = $room_configuration_id;
        $this->meal_plan_code = $meal_plan_code;
        $this->request_id = $request_id;

        $this->title = __( 'Create Booking', 'kanda' );
        $this->view = 'create';
    }

    /**
     * Ajax request for hotel booking
     */
    public function create_booking() {
        if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $is_valid = true;

            parse_str( $_POST['details'], $details );
            $security = isset( $details['security'] ) ? $details['security'] : '';

            if( wp_verify_nonce( $security, 'kanda-save-booking' ) ) {

                $hotel_code = isset($details['hotel_code']) ? $details['hotel_code'] : '';
                $city_code = isset($details['city_code']) ? $details['city_code'] : '';
                $room_number = isset($details['room_number']) ? $details['room_number'] : '';
                $request_id = isset($details['request_id']) ? $details['request_id'] : '';
                $room_type_code = isset($details['room_type_code']) ? $details['room_type_code'] : '';
                $contract_token_id = isset($details['contract_token_id']) ? $details['contract_token_id'] : '';
                $room_configuration_id = isset($details['room_configuration_id']) ? $details['room_configuration_id'] : '';
                $meal_plan_code = isset($details['meal_plan_code']) ? $details['meal_plan_code'] : '';

                if (
                    ! $hotel_code ||
                    ! $city_code ||
                    ! $room_number ||
                    ! $room_type_code ||
                    ! $contract_token_id ||
                    ! $room_configuration_id ||
                    ! $meal_plan_code ||
                    ! $request_id
                ) {
                    $is_valid = false;
                }

                if( $is_valid ) {

                    $request = provider_iol()->hotels()->get_request_data($request_id);

                    if ( $request ) {

                        $request_args = IOL_Helper::savable_format_to_array($request->request);
                        $adults_count = $request_args['room_occupants'][$room_number]['adults'];
                        $children_count = (bool)$request_args['room_occupants'][$room_number]['child'] ? count($request_args['room_occupants'][$room_number]['child']['age']) : 0;

                        $adults = isset($details['adults']) ? $details['adults'] : array_fill(0, $adults_count, array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'date_of_birth' => '',
                            'gender' => ''
                        ));

                        $children = isset( $details['children'] ) ? $details['children'] : array_fill(0, $children_count, array(
                            'title' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'date_of_birth' => '',
                            'gender' => ''
                        ));

                        /** get cancellation policy */
                        $c_start_date = date( IOL_Config::get( 'date_format' ), strtotime( $request_args['start_date'] ) );
                        $c_end_date = date( IOL_Config::get( 'date_format' ), strtotime( $request_args['end_date'] ) );
                        $cancellation_response = provider_iol()->hotels()->hotel_cancellation_policy( $hotel_code, $room_type_code, $contract_token_id, $c_start_date, $c_end_date );

                        if( $cancellation_response->is_valid() ) {



                            $repeaters = array(
                                'adults'                => array(),
                                'children'              => array(),
                                'cancellation_policy'   => array()
                            );

                            $data = $cancellation_response->data;
                            $cancellation_policies = ( array_key_exists( 'cancellationdetails', $data ) && isset( $data['cancellationdetails']['cancellation'] ) ) ? $data['cancellationdetails']['cancellation'] : array();
                            for( $i = 0; $i < count( $cancellation_policies ); $i++ ) {
                                $now = time();
                                $from_timestamp = max( strtotime( $cancellation_policies[$i]['fromdate'] ), $now );
                                $to_timestamp = min( strtotime( $cancellation_policies[$i]['todate'] ), strtotime( $request_args['end_date'] ) );
                                if ( $to_timestamp <= $now ) {
                                    continue;
                                }

                                $repeaters['cancellation_policy'][] = array(
                                    'from'      => date( 'Ymd', $from_timestamp ),
                                    'to'        => date( 'Ymd', $to_timestamp ),
                                    'charge'    => ( strtolower( $cancellation_policies[$i]['percentoramt'] ) == 'a' ) ? sprintf( '%1$d %2$s', $cancellation_policies[$i]['nighttocharge'], _n( 'night', 'nights', $cancellation_policies[$i]['nighttocharge'], 'kanda' ) ) : sprintf( '%1$d%%', intval( $cancellation_policies[$i]['value'] ) )
                                );

                            }

                            $booking_response = provider_iol()->bookings()->create(array(
                                'start_date'            => $request_args['start_date'],
                                'end_date'              => $request_args['end_date'],
                                'hotel_code'            => $hotel_code,
                                'city_code'             => $city_code,
                                'room_type_code'        => $room_type_code,
                                'contract_token_id'     => $contract_token_id,
                                'room_configuration_id' => $room_configuration_id,
                                'meal_plan_code'        => $meal_plan_code,
                                'adults'                => $adults,
                                'children'              => $children
                            ));

                            if ( $booking_response->is_valid() ) {
                                $data = $booking_response->data;

                                $start_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['startdate'] );
                                $end_date = DateTime::createFromFormat( IOL_Config::get( 'date_format' ), $data['hoteldetails']['enddate'] );
                                $interval = $end_date->diff( $start_date );
                                $nights_count = $interval->d;

                                $real_price = $data['bookingdetails']['bookingtotalrate'];
                                $real_price = kanda_covert_currency_to( $real_price, 'USD', $data['bookingdetails']['currency'] );
                                $real_price = $real_price['amount'];

                                $additional_fee = kanda_get_hotel_additional_fee( $data['hoteldetails']['hotelcode'] );
                                $earnings = $additional_fee * $nights_count;
                                $agency_price = $real_price + $earnings;

                                $earnings = number_format( $earnings, 2 );
                                $real_price = number_format( $real_price, 2 );
                                $agency_price = number_format( $agency_price, 2 );

                                //$data['hoteldetails']['hotelcode'];
                                $hotels_query = new WP_Query(array(
                                    'post_type' => 'hotel',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 1,
                                    'meta_query' => array(
                                        array(
                                            'key'     => 'hotelcode',
                                            'value'   => $data['hoteldetails']['hotelcode'],
                                            'compare' => '=',
                                        )
                                    )
                                ));
                                if( $hotels_query->have_posts() ) {
                                    $hotels = $hotels_query->get_posts();
                                    $hotel = $hotels[0];
                                    $hotel_city = kanda_get_post_meta( $hotel->ID, 'hotelcity' );
                                } else {
                                    $hotel_city = '';
                                }

                                $meta_data = array(
                                    'start_date'            => $start_date->format( 'Ymd' ),
                                    'end_date'              => $end_date->format( 'Ymd' ),
                                    'hotel_name'            => $data['hoteldetails']['hotelname'],
                                    'hotel_code'            => $data['hoteldetails']['hotelcode'],
                                    'hotel_city'            => $hotel_city,
                                    'real_price'            => $real_price,
                                    'agency_price'          => $agency_price,
                                    'earnings'              => $earnings,
                                    'booking_status'        => $data['bookingdetails']['bookingstatus'],
                                    'room_type'             => $data['hoteldetails']['roomdetails']['room']['roomtype'],
                                    'meal_plan'             => $data['hoteldetails']['roomdetails']['room']['mealplan'],
                                    'booking_number'        => $data['bookingdetails']['bookingnumber'],
                                    'booking_date'          => $data['bookingdetails']['bookeddate'],
                                    'payment_status'        => 'unpaid',
                                    'visa_rate'             => 0,
                                    'transfer_rate'         => 0,
                                    'other_rate'            => 0,
                                    'adults'                => '',
                                    'children'              => '',
                                    'cancellation_policy'   => ''
                                );

                                $nationalities = kanda_get_nationality_choices();
                                $keymap = array(
                                    'title'         => 'title',
                                    'first_name'    => 'firstname',
                                    'last_name'     => 'lastname',
                                    'date_of_birth' => 'dateofbirth',
                                    'nationality'   => 'nationality',
                                    'gender'        => 'gender'
                                );

                                $passengers = $data['bookingdetails']['passengerdetails']['passenger'];
                                $passengers = IOL_Helper::is_associative_array( $passengers ) ? array( $passengers ) : $passengers;

                                /** adults repeater */
                                $adults = wp_list_filter( $passengers, array(
                                    'passengertype' => 'ADT'
                                ) );
                                $adults = array_values( $adults );

                                for( $i = 0; $i < count( $adults ); $i++ ) {
                                    $adult = array();
                                    foreach( $keymap as $meta_key => $response_key ) {
                                        switch( $response_key ) {
                                            case 'nationality':
                                                $meta_value = $nationalities[ $adults[$i][$response_key] ];
                                                break;
                                            default:
                                                $meta_value = $adults[$i][$response_key];
                                                break;
                                        }

                                        $adult[ $meta_key ] = $meta_value;
                                    }
                                    $repeaters['adults'][] = $adult;
                                }
                                /** /end adults repeater */


                                /** children repeater */
                                $children = wp_list_filter( $passengers, array(
                                    'passengertype' => 'CHD'
                                ) );
                                $children = array_values( $children );

                                for( $i = 0; $i < count( $children ); $i++ ) {
                                    $child = array();
                                    foreach( $keymap as $meta_key => $response_key ) {
                                        if( $response_key == 'nationality' ) {
                                            $meta_value = $nationalities[ $children[$i][$response_key] ];
                                        } else {
                                            $meta_value = $children[$i][$response_key];
                                        }

                                        $child[ $meta_key ] = $meta_value;
                                    }
                                    $repeaters['children'][] = $child;
                                }
                                /** /end children repeater */

                                $booking_id = wp_insert_post( array(
                                    'post_author' => get_current_user_id(),
                                    'post_title' => sprintf( '%1$s - #%2$s', $data['hoteldetails']['hotelname'], $data['bookingdetails']['bookingnumber'] ),
                                    'post_name' => kanda_generate_random_string( 20 ),
                                    'post_status' => 'publish',
                                    'post_type' => 'booking'
                                ), true );

                                if( is_wp_error( $booking_id ) ) {
                                    $is_valid = false;
                                    $message = __( 'Error creating booking', 'kanda' );
                                } else {
                                    $redirect_to = get_permalink( $booking_id );
                                    foreach( $meta_data as $meta_key => $meta_value ) {
                                        switch ( $meta_key ) {
                                            case 'payment_status':
                                            case 'booking_status':
                                                $sanitize_value = strtolower( $meta_value );
                                                break;
                                            default:
                                                $sanitize_value = $meta_value;
                                        }
                                        update_field( $meta_key, $sanitize_value, $booking_id );
                                    }
                                    foreach( $repeaters as $parent_key => $rows ) {
                                        foreach( $rows as $row ) {
                                            add_row( $parent_key, $row, $booking_id );
                                        }
                                    }

                                    do_action( 'kanda/booking/create', $booking_id );
                                }
                            } else {
                                $is_valid = false;
                                $message = $booking_response->message;
                            }

                        } else {
                            $is_valid = false;
                            $message = $cancellation_response->message;
                        }

                    } else {
                        $is_valid = false;
                        $message = esc_html__( 'Invalid request', 'kanda' );
                    }

                }
            } else {
                $is_valid = false;
                $message = esc_html__( 'Invalid request', 'kanda' );
            }

            if( $is_valid ) {
                wp_send_json_success( array(
                    'redirect_to' => $redirect_to
                ) );
            } else {
                wp_send_json_error( array(
                    'message' => $message
                ) );
            }
        }
        $this->show_404();
    }

    /**
     * Send booking details via email
     * @param $args
     */
    public function send_details_email( $args ) {
        if( isset( $_POST['kanda_send_email'] ) ) {

            $is_valid = true;

            $security = isset( $_POST['security'] ) ? $_POST['security'] : '';
            if( wp_verify_nonce( $security, 'kanda-send-booking-data-email' ) ) {

                $email = isset( $_POST['email_address'] ) ? $_POST['email_address'] : '';
                if( !$email ) {
                    $is_valid = false;
                    $message = __( 'Email address is requeired', 'kanda' );
                } elseif( filter_var($email, FILTER_VALIDATE_EMAIL) === false ) {
                    $is_valid = false;
                    $message = __( 'Invalid email address', 'kanda' );
                }

                if( $is_valid ) {
                    $bookings_query = new WP_Query( array(
                        'name'        => $args[ 'k_booking_slug' ],
                        'author'      => get_current_user_id(),
                        'post_type'   => 'booking',
                        'post_status' => 'publish',
                        'numberposts' => 1
                    ) );
                    if( $bookings_query->have_posts() ) {
                        $bookings = $bookings_query->get_posts();
                        $booking = $bookings[0];

                        ob_start();
                            $booking_id = $booking->ID;
                            include Kanda_Mailer::get_layout_path() . 'booking-details.php';
                        $booking_details = ob_get_clean();

                        $user = get_user_by( 'email', $email );
                        $first_name = $last_name = '';
                        if( $user ) {
                            $first_name = $user->first_name;
                            $last_name = $user->last_name;
                        }

                        $subject = kanda_get_theme_option( 'email_booking_details_title' );
                        $message = kanda_get_theme_option( 'email_booking_details_body' );
                        $variables = array(
                            '{{BOOKING_DETAILS}}' => $booking_details,
                            '{{FIRST_NAME}}'      => $first_name,
                            '{{LAST_NAME}}'       => $last_name
                        );
                        $sent = kanda_mailer()->send_user_email( $email, $subject, $message, $variables );
                        if( $sent ) {
                            $notification_type = 'success';
                            $notification_message = __( 'Email successfully sent', 'kanda' );
                        } else {
                            $notification_type = 'error';
                            $notification_message = __( 'Error sending email. Please try again later.', 'kanda' );
                        }
                        $this->set_notification( $notification_type, $notification_message );

                        kanda_to( 'booking', array( 'view', $args[ 'k_booking_slug' ] ) );
                    } else {
                        $is_valid = false;
                        $message = __( 'Invalid request', 'kanda' );
                    }
                    wp_reset_query();
                }

            } else {
                $is_valid = false;
                $message = __( 'Invalid request', 'kanda' );
            }

            if( ! $is_valid ) {
                kanda_to( 404 );
            }

        }
        kanda_to( 404 );
    }

}