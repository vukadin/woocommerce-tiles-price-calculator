<?php if( !defined( 'ABSPATH' ) ) exit; ?>
<div id="measures" class="panel woocommerce_options_panel hidden">
    <div class="options_group">
        <?php
            woocommerce_wp_checkbox(
                array(
                    'id'            => '_wtpc_is_measurable',
                    'value'         => WTPC_Helpers::is_measurable( $product_object->get_id() ) ? 'yes' : 'no',
                    'label'         => __( 'Is measurable', 'wtpc' ),
                    'description'   => __( 'Enable this if product price is depending of its dimensions ( current price will be for one square meter )', 'wtpc' ),
                )
            );
        ?>
        
        <p class="form-field dimensions_field show_if_measurable">
            <?php /* translators: WooCommerce dimension unit*/ ?>
            <label for="wtpc_max_width"><?php echo __( 'Max Dimensions (mm)', 'wtpc' ); ?></label>
            <span class="wrap">
                <input id="wtpc_max_width" placeholder="<?php esc_attr_e( 'Width', 'wtpc' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_wtpc_max_width" value="<?php echo esc_attr( WTPC_Helpers::get_max_width( $product_object->get_id() ) ); ?>" />
                <input id=wtpc_max_height" placeholder="<?php esc_attr_e( 'Height', 'wtpc' ); ?>" class="input-text wc_input_decimal last" size="6" type="text" name="_wtpc_max_height" value="<?php echo esc_attr( WTPC_Helpers::get_max_height( $product_object->get_id() ) ); ?>" />
            </span>
            <?php echo wc_help_tip( __( 'WxH in decimal form', 'wtpc' ) ); ?>
        </p>

        <?php wp_nonce_field( WTPC_Helpers::get_nonce_action( $product_object->get_id() ), WTPC_Helpers::get_nonce_name() ); ?>
    </div>
</div>