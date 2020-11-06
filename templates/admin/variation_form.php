<?php 
    if( !defined( 'ABSPATH' ) ) exit; 
    $is_measurable = WTPC_Helpers::is_measurable( $variation->ID );
?>

<div class="wtpc-variation-form">
    <?php
        woocommerce_wp_checkbox(
            array(
                'id'            => '_wtpc_is_measurable_'.$loop,
                'name'          => '_wtpc_is_measurable['.$loop.']',
                'wrapper_class' => 'form-row form-row-full',
                'class'         => 'wtpc_is_measurable',
                'value'         => $is_measurable ? 'yes' : 'no',
                'label'         => __( 'Nach Fläche berechnen?', 'wtpc' ),
                'description'   => __( 'Aktivieren wenn der Produktpreis von Abmessungen abhängt (der aktuelle Preis gilt für einen Quadratmeter)', 'wtpc' ),
            )
        );
    ?>
    
    <p class="form-field form-row dimensions_field show_if_measurable form-row-first" <?php echo !$is_measurable ? 'style="display:none"' : ''; ?>>
        <?php /* translators: WooCommerce dimension unit*/ ?>
        <label for="wtpc_min_width_<?php echo $loop; ?>"><?php echo __( 'Min. Größe (mm)', 'wtpc' ); ?></label>
        <span class="wrap">
            <input id="wtpc_min_width_<?php echo $loop; ?>" placeholder="<?php esc_attr_e( 'Breite', 'wtpc' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_wtpc_min_width[<?php echo $loop; ?>]" value="<?php echo esc_attr( WTPC_Helpers::get_min_width( $variation->ID ) ); ?>" />
            <input id=wtpc_min_height_<?php echo $loop; ?>" placeholder="<?php esc_attr_e( 'Höhe', 'wtpc' ); ?>" class="input-text wc_input_decimal last" size="6" type="text" name="_wtpc_min_height[<?php echo $loop; ?>]" value="<?php echo esc_attr( WTPC_Helpers::get_min_height( $variation->ID ) ); ?>" />
        </span>
        <?php echo wc_help_tip( __( 'BxH in Dezimalform', 'wtpc' ) ); ?>
    </p>
    
    <p class="form-field form-row dimensions_field show_if_measurable form-row-last" <?php echo !$is_measurable ? 'style="display:none"' : ''; ?>>
        <?php /* translators: WooCommerce dimension unit*/ ?>
        <label for="wtpc_max_width"><?php echo __( 'Max. Größe (mm)', 'wtpc' ); ?></label>
        <span class="wrap">
            <input id="wtpc_max_width" placeholder="<?php esc_attr_e( 'Breite', 'wtpc' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_wtpc_max_width[<?php echo $loop; ?>]" value="<?php echo esc_attr( WTPC_Helpers::get_max_width( $variation->ID ) ); ?>" />
            <input id=wtpc_max_height" placeholder="<?php esc_attr_e( 'Höhe', 'wtpc' ); ?>" class="input-text wc_input_decimal last" size="6" type="text" name="_wtpc_max_height[<?php echo $loop; ?>]" value="<?php echo esc_attr( WTPC_Helpers::get_max_height( $variation->ID ) ); ?>" />
        </span>
        <?php echo wc_help_tip( __( 'BxH in Dezimalform', 'wtpc' ) ); ?>
    </p>

</div>
