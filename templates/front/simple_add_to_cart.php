<div id='wtpc-dimensions-calculator' data-price='<?php echo esc_attr( str_replace( '.-', '', $product->get_price() ) ); ?>'>
    <p><?php _e( 'Maximum (H)x(W) per tile' ); ?> = <?php echo WTPC_Helpers::get_max_height( $product->get_id() ); ?> x <?php echo WTPC_Helpers::get_max_width( $product->get_id() ); ?> mm</p>
    <table>
        <tbody>
            <tr>
                <td>
                    <label for="wtpc-height"><?php _e( 'Height (mm)', 'wtpc' ); ?></label>
                </td>
                <td>
                    <input type="number" id="wtpc-height" value="<?php echo esc_attr( $height ); ?>" autocomplete="off" min="<?php echo esc_attr( WTPC_Helpers::get_min_height( $product->get_id() ) ); ?>" max="<?php echo esc_attr( WTPC_Helpers::get_max_height( $product->get_id() ) ); ?>" step="1">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wtpc-width"><?php _e( 'Width (mm)', 'wtpc' ); ?></label>
                </td>
                <td>
                    <input type="number" id="wtpc-width" value="<?php echo esc_attr( $width ); ?>" autocomplete="off" min="<?php echo esc_attr( WTPC_Helpers::get_min_width( $product->get_id() ) ); ?>" max="<?php echo esc_attr( WTPC_Helpers::get_max_width( $product->get_id() ) ); ?>" step="1">
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Total area (m²)', 'wtpc' ); ?></td>
                <td>
                    <span id='wtpc-calculated-area' >0,000</span>
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Product price', 'wtpc' ); ?></td>
                <td>
                    <span id='wtpc-calculated-price'></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>