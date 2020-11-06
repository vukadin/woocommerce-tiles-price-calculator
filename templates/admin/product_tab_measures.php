<?php 
    if( !defined( 'ABSPATH' ) ) exit; 
    $is_measurable = WTPC_Helpers::is_measurable( $product_object->get_id() );
?>
<div id="measures" class="panel woocommerce_options_panel hidden">
    <div class="options_group">
        <?php
            woocommerce_wp_checkbox(
                array(
                    'id'            => '_wtpc_is_measurable',
                    'class'         => 'wtpc_is_measurable',
                    'value'         => $is_measurable ? 'yes' : 'no',
                    'label'         => __( 'Nach Fläche berechnen?', 'wtpc' ),
                    'description'   => __( 'Aktivieren wenn der Produktpreis von Abmessungen abhängt (der aktuelle Preis gilt für einen Quadratmeter)', 'wtpc' ),
                )
            );
        ?>
        
        <p class="form-field dimensions_field show_if_measurable" <?php echo !$is_measurable ? 'style="display:none"' : ''; ?>>
            <?php /* translators: WooCommerce dimension unit*/ ?>
            <label for="wtpc_min_width"><?php echo __( 'Min. Größe (mm)', 'wtpc' ); ?></label>
            <span class="wrap">
                <input id="wtpc_min_width" placeholder="<?php esc_attr_e( 'Breite', 'wtpc' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_wtpc_min_width" value="<?php echo esc_attr( WTPC_Helpers::get_min_width( $product_object->get_id() ) ); ?>" />
                <input id=wtpc_min_height" placeholder="<?php esc_attr_e( 'Höhe', 'wtpc' ); ?>" class="input-text wc_input_decimal last" size="6" type="text" name="_wtpc_min_height" value="<?php echo esc_attr( WTPC_Helpers::get_min_height( $product_object->get_id() ) ); ?>" />
            </span>
            <?php echo wc_help_tip( __( 'BxH in Dezimalform', 'wtpc' ) ); ?>
        </p>
        
        <p class="form-field dimensions_field show_if_measurable" <?php echo !$is_measurable ? 'style="display:none"' : ''; ?>>
            <?php /* translators: WooCommerce dimension unit*/ ?>
            <label for="wtpc_max_width"><?php echo __( 'Max. Größe (mm)', 'wtpc' ); ?></label>
            <span class="wrap">
                <input id="wtpc_max_width" placeholder="<?php esc_attr_e( 'Breite', 'wtpc' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_wtpc_max_width" value="<?php echo esc_attr( WTPC_Helpers::get_max_width( $product_object->get_id() ) ); ?>" />
                <input id=wtpc_max_height" placeholder="<?php esc_attr_e( 'Höhe', 'wtpc' ); ?>" class="input-text wc_input_decimal last" size="6" type="text" name="_wtpc_max_height" value="<?php echo esc_attr( WTPC_Helpers::get_max_height( $product_object->get_id() ) ); ?>" />
            </span>
            <?php echo wc_help_tip( __( 'BxH in Dezimalform', 'wtpc' ) ); ?>
        </p>

        <?php wp_nonce_field( WTPC_Helpers::get_nonce_action( $product_object->get_id() ), WTPC_Helpers::get_nonce_name() ); ?>
    </div>
</div>