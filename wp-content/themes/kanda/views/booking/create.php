<form class="form-block" id="form_create_booking" method="post">
    <h4><?php echo _n( 'Adult', 'Adults', $this->adults, 'kanda' ); ?></h4>

    <fieldset class="fieldset row">
        <?php foreach( $this->adults as $i => $adult ) { ?>
        <div class="col-md-6">
            <div class="box body-bg" data-block="<?php echo $i; ?>">
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Title', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="adults[<?php echo $i; ?>][title]">
                            <option value="mr" <?php selected( $adult['title'], 'mr' ); ?>><?php _e( 'Mr', 'kanda' ); ?></option>
                            <option value="mrs" <?php selected( $adult['title'], 'mrs' ); ?>><?php _e( 'Mrs', 'kanda' ); ?></option>
                            <option value="mrs" <?php selected( $adult['title'], 'ms' ); ?>><?php _e( 'Ms', 'kanda' ); ?></option>
                        </select>
                        <div class="form-control-feedback"><small></small></div>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'First Name', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <input type="text" name="adults[<?php echo $i; ?>][first_name]" class="form-control" value="<?php $adult['first_name']; ?>">
                        <div class="form-control-feedback"><small></small></div>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Last Name', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <input type="text" name="adults[<?php echo $i; ?>][last_name]" class="form-control" value="<?php $adult['last_name']; ?>">
                        <div class="form-control-feedback"><small></small></div>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Date of birth', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <input type="text" name="adults[<?php echo $i; ?>][date_of_birth]" class="form-control birthdate deny-typing" value="<?php $adult['date_of_birth']; ?>">
                        <div class="form-control-feedback"><small></small></div>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Gender', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="adults[<?php echo $i; ?>][gender]">
                            <option value="m" <?php selected( $adult['gender'], 'm' ); ?>><?php _e( 'Male', 'kanda' ); ?></option>
                            <option value="f" <?php selected( $adult['gender'], 'f' ); ?>><?php _e( 'Female', 'kanda' ); ?></option>
                        </select>
                        <div class="form-control-feedback"><small></small></div>
                    </div>
                </div>
                <div class="form-group row clearfix">
                    <label class="form-label col-lg-5"><?php esc_html_e( 'Nationality', 'kanda' ); ?>:</label>
                    <div class="select-wrap col-lg-7">
                        <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="adults[<?php echo $i; ?>][nationality]">
                            <?php foreach( kanda_get_nationality_choices() as $iso => $nat_name ) { ?>
                            <option value="<?php echo $iso; ?>" <?php selected( $iso, $adult['nationality'] ); ?>><?php echo $nat_name; ?></option>
                            <?php } ?>
                        </select>
                        <div class="form-control-feedback"><small></small></div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </fieldset>

    <?php if( $this->children ) { ?>
    <h4><?php echo _n( 'Child', 'Children', $this->children, 'kanda' ); ?></h4>
    <fieldset class="fieldset row">
        <?php foreach( $this->children as $i => $child ) { ?>
            <div class="col-md-6">
                <div class="box body-bg" data-block="<?php echo $i; ?>">
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Title', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="children[<?php echo $i; ?>][title]">
                                <option value="mr" <?php selected( $child['title'], 'mr' ); ?>><?php _e( 'Mr', 'kanda' ); ?></option>
                                <option value="mrs" <?php selected( $child['title'], 'miss' ); ?>><?php _e( 'Miss', 'kanda' ); ?></option>
                            </select>
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'First Name', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <input type="text" name="children[<?php echo $i; ?>][first_name]" class="form-control" value="<?php $child['first_name']; ?>">
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Last Name', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <input type="text" name="children[<?php echo $i; ?>][last_name]" class="form-control" value="<?php $child['last_name']; ?>">
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Date of birth', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <input type="text" name="children[<?php echo $i; ?>][date_of_birth]" class="form-control birthdate deny-typing" value="<?php $child['date_of_birth']; ?>">
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Gender', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="children[<?php echo $i; ?>][gender]">
                                <option value="m" <?php selected( $child['gender'], 'm' ); ?>><?php _e( 'Male', 'kanda' ); ?></option>
                                <option value="f" <?php selected( $child['gender'], 'f' ); ?>><?php _e( 'Female', 'kanda' ); ?></option>
                            </select>
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                    <div class="form-group row clearfix">
                        <label class="form-label col-lg-5"><?php esc_html_e( 'Nationality', 'kanda' ); ?>:</label>
                        <div class="select-wrap col-lg-7">
                            <select class="<?php echo apply_filters( 'custom-select-classname', 'kanda-select' ); ?>" name="children[<?php echo $i; ?>][nationality]">
                                <?php foreach( kanda_get_nationality_choices() as $iso => $nat_name ) { ?>
                                <option value="<?php echo $iso; ?>" <?php selected( $iso, $child['nationality'] ); ?>><?php echo $nat_name; ?></option>
                                <?php } ?>
                            </select>
                            <div class="form-control-feedback"><small></small></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </fieldset>
    <?php } ?>

    <footer class="form-footer clearfix">
        <input type="hidden" name="hotel_code" value="<?php echo $this->hotel_code; ?>" />
        <input type="hidden" name="city_code" value="<?php echo $this->city_code; ?>" />
        <input type="hidden" name="room_number" value="<?php echo $this->room_number; ?>" />
        <input type="hidden" name="room_type_code" value="<?php echo $this->room_type_code; ?>" />
        <input type="hidden" name="contract_token_id" value="<?php echo $this->contract_token_id; ?>" />
        <input type="hidden" name="room_configuration_id" value="<?php echo $this->room_configuration_id; ?>" />
        <input type="hidden" name="meal_plan_code" value="<?php echo $this->meal_plan_code; ?>"  />
        <input type="hidden" name="request_id" value="<?php echo $this->request_id; ?>" />
        <input type="hidden" name="security" value="<?php echo wp_create_nonce( 'kanda-save-booking' ); ?>" />
        <input type="submit" name="kanda_save_booking" value="<?php _e( 'Book', 'kanda' ); ?>" class="btn -secondary pull-right">
    </footer>

</form>

<?php
    echo kanda_get_loading_popup();
    echo kanda_get_error_popup();
?>