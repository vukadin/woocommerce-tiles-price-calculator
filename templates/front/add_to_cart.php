<div id='wtpc-dimensions-calculator' data-price='<?php echo esc_attr( str_replace( '.-', '', $product->get_price() ) ); ?>'>
    <p><?php 
        printf( 
            __( 'Die Mindestgröße beträgt <span id="wtpc-min-width-label" >%d</span> x <span id="wtpc-min-height-label" >%d</span> mm' ), 
            WTPC_Helpers::get_min_width( $product->get_id() ), 
            WTPC_Helpers::get_min_height( $product->get_id() ) 
        ); ?>
        <br>
        <?php 
        printf( 
            __( 'Die Maximalgröße beträgt <span id="wtpc-max-width-label" >%d</span> x <span id="wtpc-max-height-label" >%d</span> mm' ), 
            WTPC_Helpers::get_max_width( $product->get_id() ), 
            WTPC_Helpers::get_max_height( $product->get_id() ) 
        ); ?>
    </p>
    <table>
        <tbody>
            <tr>
                <td>
                    <label for="wtpc-width"><?php _e( 'Breite (mm)', 'wtpc' ); ?></label>
                </td>
                <td>
                    <input required type="number" name="wtpc_width" id="wtpc-width" value="<?php echo esc_attr( $width ); ?>" autocomplete="off" min="<?php echo esc_attr( WTPC_Helpers::get_min_width( $product->get_id() ) ); ?>" max="<?php echo esc_attr( WTPC_Helpers::get_max_width( $product->get_id() ) ); ?>" step="1">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wtpc-height"><?php _e( 'Höhe (mm)', 'wtpc' ); ?></label>
                </td>
                <td>
                    <input required type="number" name="wtpc_height" id="wtpc-height" value="<?php echo esc_attr( $height ); ?>" autocomplete="off" min="<?php echo esc_attr( WTPC_Helpers::get_min_height( $product->get_id() ) ); ?>" max="<?php echo esc_attr( WTPC_Helpers::get_max_height( $product->get_id() ) ); ?>" step="1">
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Gesamtfläche (m²)', 'wtpc' ); ?></td>
                <td>
                    <span id='wtpc-calculated-area' >0,000</span>
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Produkt Preis', 'wtpc' ); ?></td>
                <td>
                    <span id='wtpc-calculated-price'></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>