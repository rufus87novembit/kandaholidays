<?php
/*
Plugin Name: Custom List Table Example
Plugin URI: http://www.mattvanandel.com/
Description: A highly documented plugin that demonstrates how to create custom List Tables using official WordPress APIs.
Version: 1.4.1
Author: Matt van Andel
Author URI: http://www.mattvanandel.com
License: GPL2
*/
/*  Copyright 2015  Matthew Van Andel  (email : matt@mattvanandel.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/* == NOTICE ===================================================================
 * Please do not alter this file. Instead: make a copy of the entire plugin, 
 * rename it, and work inside the copy. If you modify this plugin directly and 
 * an update is released, your changes will be lost!
 * ========================================================================== */



/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary. In this tutorial, we are
 * going to use the WP_List_Table class directly from WordPress core.
 *
 * IMPORTANT:
 * Please note that the WP_List_Table class technically isn't an official API,
 * and it could change at some point in the distant future. Should that happen,
 * I will update this plugin with the most current techniques for your reference
 * immediately.
 *
 * If you are really worried about future compatibility, you can make a copy of
 * the WP_List_Table class (file path is shown just below) to use and distribute
 * with your plugins. If you do that, just remember to change the name of the
 * class to avoid conflicts with core.
 *
 * Since I will be keeping this tutorial up-to-date for the foreseeable future,
 * I am going to work with the copy of the class provided in WordPress core.
 */
if( ! class_exists( 'WP_List_Table' ) ){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 *
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 *
 * Our theme for this list table is going to be movies.
 */
class Booking_Search_List_Table extends WP_List_Table {

    private $per_page;

    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title()
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as
     * possible.
     *
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     *
     * For more detailed insight into how columns are handled, take a look at
     * WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'rating':
            case 'director':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }


    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     *
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     *
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_title($item){

        //Build row actions
        $actions = array(
            'edit'      => sprintf( '<a href="%s">Edit</a>', get_edit_post_link( $item->ID ) ),
            'delete'    => sprintf('<a href="%s">Delete</a>',get_delete_post_link( $item->ID ) ),
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item->post_title,
            /*$2%s*/ $item->ID,
            /*$3%s*/ $this->row_actions( $actions )
        );
    }

    function column_checkin($item) {
        $checkin = kanda_get_post_meta( $item->ID, 'start_date' );

        return date( Kanda_Config::get( 'display_date_format' ), strtotime( $checkin ) );
    }

    function column_checkout($item) {
        $checkout = kanda_get_post_meta( $item->ID, 'end_date' );

        return date( Kanda_Config::get( 'display_date_format' ), strtotime( $checkout ) );
    }

    function column_booked_date($item) {
        $booking_date = kanda_get_post_meta( $item->ID, 'booking_date' );

        return date( Kanda_Config::get( 'display_date_format' ), strtotime( $booking_date ) );
    }

    function column_passenger_names($item) {
        return strtr( kanda_get_post_meta( $item->ID, 'passenger_names' ), array( '##' => ', ' ) );
    }

    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->ID                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     *
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     *
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'                => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'             => 'Title',
            'checkin'           => 'Check In',
            'checkout'          => 'Check Out',
            'booked_date'       => 'Booked Date',
            'passenger_names'   => 'Passenger Names'
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     *
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     *
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     *
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     *
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }

    }


    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     *
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {

        /**
         * First, lets decide how many records per page to show
         */
        $this->per_page = 2;

        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);


        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();


        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example
         * package slightly different than one you might build on your own. In
         * this example, we'll be using array manipulation to sort and paginate
         * our data. In a real-world implementation, you will probably want to
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        $query_result = $this->process_query();
        $data = $query_result['posts'];


        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         *
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         *
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/


        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */
        $total_items = $query_result['total'];

        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $data;


        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $this->per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$this->per_page)   //WE have to calculate the total number of pages
        ) );
    }

    function process_query() {

        $args = array(
            'post_type'         => 'booking',
            'post_status'       => 'publish',
            'order'             => 'DESC',
            'posts_per_page'    => $this->per_page,
            'paged'             => $this->get_pagenum()
        );

        $meta_query = array();
        if( isset( $_REQUEST['booking_status'] ) && $_REQUEST['booking_status'] ) {
            $meta_query[] = array(
                'key'       => 'booking_status',
                'value'     => $_REQUEST['booking_status'],
                'compare'   => '='
            );
        }

        $passenger_name = array();
        if( isset( $_REQUEST['pfn'] ) && $_REQUEST['pfn'] ) {
            $passenger_name[] = $_REQUEST['pfn'];
        }

        if( isset( $_REQUEST['pln'] ) && $_REQUEST['pln'] ) {
            $passenger_name[] = $_REQUEST['pln'];
        }

        if( ! empty( $passenger_name ) ) {
            $meta_query[] = array(
                'key'       => 'passenger_names',
                'value'     => implode( ' ', $passenger_name ),
                'compare'   => 'LIKE'
            );
        }

        if( isset( $_REQUEST['city'] ) && $_REQUEST['city'] ) {
            $meta_query[] = array(
                'key'       => 'hotel_city',
                'value'     => $_REQUEST['city'],
                'compare'   => 'LIKE'
            );
        }

        if( isset( $_REQUEST['hotel_name'] ) && $_REQUEST['hotel_name'] ) {
            $meta_query[] = array(
                'key'       => 'hotel_name',
                'value'     => $_REQUEST['hotel_name'],
                'compare'   => 'LIKE'
            );
        }

        if( isset( $_REQUEST['brn'] ) && $_REQUEST['brn'] ) {
            $meta_query[] = array(
                'key'       => 'booking_number',
                'value'     => $_REQUEST['brn'],
                'compare'   => '='
            );
        }

        if( isset( $_REQUEST['bdate'] ) && $_REQUEST['bdate'] ) {
            $meta_query[] = array(
                'key'       => 'booking_date',
                'value'     => DateTime::createFromFormat( 'd F, Y', $_REQUEST['bdate'] )->format( 'Ymd' ),
                'compare'   => '='
            );
        }

        if( isset( $_REQUEST['chidate'] ) && $_REQUEST['chidate'] ) {
            $meta_query[] = array(
                'key'       => 'start_date',
                'value'     => DateTime::createFromFormat( 'd F, Y', $_REQUEST['chidate'] )->format( 'Ymd' ),
                'compare'   => '='
            );
        }

        if( count( $meta_query ) > 1 ) {
            $meta_query['relation'] = 'AND';
        }

        if( ! empty( $meta_query ) ) {
            $args['meta_query'] = $meta_query;
        }

        $query = new WP_Query( $args );

        return array(
            'posts' => $query->get_posts(),
            'total' => $query->found_posts
        );

    }

}





/** ************************ REGISTER THE TEST PAGE ****************************
 *******************************************************************************
 * Now we just need to define an admin page. For this example, we'll add a top-level
 * menu item to the bottom of the admin menus.
 */
function kanda_add_menu_items(){
    add_submenu_page(
        'edit.php?post_type=booking',
        __( 'Booking Search', 'kanda' ),
        __( 'Booking Search', 'kanda' ),
        'activate_plugins',
        'search_bookings',
        'search_bookings_render_list_page'
    );
}
add_action( 'admin_menu', 'kanda_add_menu_items');





/** *************************** RENDER TEST PAGE ********************************
 *******************************************************************************
 * This function renders the admin page and the example list table. Although it's
 * possible to call prepare_items() and display() from the constructor, there
 * are often times where you may need to include logic here between those steps,
 * so we've instead called those methods explicitly. It keeps things flexible, and
 * it's the way the list tables are used in the WordPress core.
 */
function search_bookings_render_list_page(){

    //Create an instance of our package class...
    $booking_search_list_table = new Booking_Search_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $booking_search_list_table->prepare_items();

    ?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <h2><?php _e( 'Booking Search', 'kanda' ); ?></h2>

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get" action="<?php echo add_query_arg( array( 'post_type' => 'booking', 'page' => 'search_bookings' ), admin_url( 'edit.php' ) ); ?>">
            <input type="hidden" name="post_type" value="booking">
            <input type="hidden" name="page" value="search_bookings">
            <div class="clearfix">
                <div class="list-table-form-row">
                    <label><?php _e( 'Passenger First Name', 'kanda' ); ?>:</label>
                    <?php $selected = isset( $_REQUEST['booking_status'] ) ? $_REQUEST['booking_status'] : ''; ?>
                    <select name="booking_status">
                        <option value="" <?php selected( $selected, '' ); ?>>---</option>
                        <option value="requested" <?php selected( $selected, 'requested' ); ?>><?php _e( 'On Request', 'kanda' ); ?></option>
                        <option value="option" <?php selected( $selected, 'option' ); ?>><?php _e( 'Option', 'kanda' ); ?></option>
                        <option value="confirmed" <?php selected( $selected, 'confirmed' ); ?>><?php _e( 'Confirmed', 'kanda' ); ?></option>
                        <option value="cancelled" <?php selected( $selected, 'cancelled' ); ?>><?php _e( 'Cancelled', 'kanda' ); ?></option>
                        <option value="paid" <?php selected( $selected, 'paid' ); ?>><?php _e( 'Paid', 'kanda' ); ?></option>
                    </select>
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Passenger First Name', 'kanda' ); ?>:</label>
                    <input type="text" name="pfn" value="<?php echo isset( $_REQUEST['pfn'] ) ? $_REQUEST['pfn'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Passenger Last Name', 'kanda' ); ?>:</label>
                    <input type="text" name="pln" value="<?php echo isset( $_REQUEST['pln'] ) ? $_REQUEST['pln'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Booked City', 'kanda' ); ?>:</label>
                    <input type="text" name="city" value="<?php echo isset( $_REQUEST['city'] ) ? $_REQUEST['city'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Hotel Name', 'kanda' ); ?>:</label>
                    <input type="text" name="hotel_name" value="<?php echo isset( $_REQUEST['hotel_name'] ) ? $_REQUEST['hotel_name'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Booking Reference Number', 'kanda' ); ?>:</label>
                    <input type="text" name="brn" value="<?php echo isset( $_REQUEST['brn'] ) ? $_REQUEST['brn'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Booking Date', 'kanda' ); ?>:</label>
                    <input type="text" name="bdate" class="datepicker" value="<?php echo isset( $_REQUEST['bdate'] ) ? $_REQUEST['bdate'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <label><?php _e( 'Check In Date', 'kanda' ); ?>:</label>
                    <input type="text" name="chidate" class="datepicker" value="<?php echo isset( $_REQUEST['chidate'] ) ? $_REQUEST['chidate'] : ''; ?>" />
                </div>
                <div class="list-table-form-row">
                    <?php
                        submit_button( __( 'Search', 'kanda' ), 'primary', 'kanda_search_booking' );
                    ?>
                </div>
            </div>
        </form>

        <!-- Now we can render the completed list table -->
        <?php $booking_search_list_table->display() ?>

    </div>
    <?php
}