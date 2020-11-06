<?php if( !defined( 'ABSPATH' ) ) exit;

class WTPC_Plugin
{
    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_assets' ) );
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'register_product_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'output_product_tab' ) );
        add_action( 'save_post', array( $this, 'save_simple_product_data' ) );
        add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'output_dimensions_form' ) );
        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'maybe_add_to_cart'), 10, 4 );
        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data'), 10, 3 );
        add_filter( 'woocommerce_get_item_data', array( $this, 'get_cart_item_data'), 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_order_meta' ), 10, 4 );
        add_filter( 'woocommerce_order_item_get_formatted_meta_data', array( $this, 'format_order_meta' ), 10, 2 );
        add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );
        add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_product_price' ), 10 ); 
        add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'output_variation_form' ), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_product_data' ), 10, 2 );
        add_filter( 'woocommerce_available_variation', array( $this, 'add_variation_data' ), 10, 3 );
    }

    public function enqueue_admin_assets()
    {
        wp_enqueue_script( 'wtpc', plugins_url( 'assets/js/admin.js', WTPC_FILE ), array( 'jquery' ), WTPC_VERSION );
        wp_enqueue_style( 'wtpc', plugins_url( 'assets/css/admin.css', WTPC_FILE ), null, WTPC_VERSION );
    }

    public function enqueue_front_assets()
    {
        wp_enqueue_script( 'wtpc', plugins_url( 'assets/js/scripts.js', WTPC_FILE ), array( 'jquery' ), WTPC_VERSION );
        wp_enqueue_style( 'wtpc', plugins_url( 'assets/css/style.css', WTPC_FILE ), null, WTPC_VERSION );
    }

    public function register_product_tab( $tabs )
    {
        $tabs['measures']= array(
            'label'    => __( 'Fläche', 'wtpc' ),
            'target'   => 'measures',
            'wrapper_class' => 'show_if_simple',
            'class'    => array(),
            'priority' => 15
        );

        return $tabs;
    }

    public function output_product_tab()
    {
        global $product_object;

        include WTPC_DIR.'/templates/admin/product_tab_measures.php';
    }

    public function save_simple_product_data( $product_id )
    {
        if( get_post_type( $product_id ) !== 'product' ) return;
        if( !current_user_can( 'edit_post', $product_id ) ) return;
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        $nonce = isset( $_POST[ WTPC_Helpers::get_nonce_name() ] ) ? $_POST[ WTPC_Helpers::get_nonce_name() ] : '';

        if( !$nonce || !wp_verify_nonce( $nonce, WTPC_Helpers::get_nonce_action( $product_id) ) ) return;

        $is_measurable = isset( $_POST['_wtpc_is_measurable'] );

        $min_width = isset( $_POST['_wtpc_min_width'] ) ? (int)$_POST['_wtpc_min_width'] : '';
        $max_width = isset( $_POST['_wtpc_max_width'] ) ? (int)$_POST['_wtpc_max_width'] : '';

        $min_height = isset( $_POST['_wtpc_min_height'] ) ? (int)$_POST['_wtpc_min_height'] : '';
        $max_height = isset( $_POST['_wtpc_max_height'] ) ? (int)$_POST['_wtpc_max_height'] : '';

        WTPC_Helpers::set_measurable( $product_id, $is_measurable );

        WTPC_Helpers::set_min_width( $product_id, $min_width );
        WTPC_Helpers::set_max_width( $product_id, $max_width );

        WTPC_Helpers::set_min_height( $product_id, $min_height );
        WTPC_Helpers::set_max_height( $product_id, $max_height );
    }

    public function output_dimensions_form()
    {
        global $product;

        if( ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) && WTPC_Helpers::is_measurable( $product->get_id() ) ) :

            $width = isset( $_POST['wtpc_width'] ) && (int)$_POST['wtpc_width'] > 0 ? (int)$_POST['wtpc_width'] : '';
            $height = isset( $_POST['wtpc_height'] ) && (int)$_POST['wtpc_height'] > 0  ? (int)$_POST['wtpc_height'] : '';

            include WTPC_DIR.'/templates/front/add_to_cart.php';

        endif;
    }

    public function maybe_add_to_cart( $valid, $product_id, $quantity, $variation_id = 0 )
    {
        if( !$valid ) return false;

        $real_product_id = $variation_id ? $variation_id : $product_id;

        if( WTPC_Helpers::is_measurable( $real_product_id ) ) :

            $width = isset( $_POST['wtpc_width'] ) ? (int)$_POST['wtpc_width'] : 0;
            $height = isset( $_POST['wtpc_height'] ) ? (int)$_POST['wtpc_height'] : 0;

            $min_width = WTPC_Helpers::get_min_width( $real_product_id );
            $max_width = WTPC_Helpers::get_max_width( $real_product_id );
            
            $min_height = WTPC_Helpers::get_min_height( $real_product_id );
            $max_height = WTPC_Helpers::get_max_height( $real_product_id );

            if( $width < $min_width ):
                wc_add_notice( 
                    sprintf(
                        __( '%s value must be greater than or equal to %s.', 'wtpc' ),   
                        __( 'Breite', 'wtpc' ),
                        $min_width
                    ), 
                    'error' 
                );
                $valid = false;
            elseif( $width > $max_width ):
                wc_add_notice( 
                    sprintf(
                        __( '%s value must be less than or equal to %s.', 'wtpc' ),   
                        __( 'Breite', 'wtpc' ),
                        $max_width
                    ), 
                    'error' 
                );
                $valid = false;
            endif;

            if( $height < $min_height ):
                wc_add_notice( 
                    sprintf(
                        __( '%s value must be greater than or equal to %s.', 'wtpc' ),   
                        __( 'Höhe', 'wtpc' ),
                        $min_height
                    ), 
                    'error' 
                );
                $valid = false;
            elseif( $height > $max_height ):
                wc_add_notice( 
                    sprintf(
                        __( '%s value must be less than or equal to %s.', 'wtpc' ),   
                        __( 'Höhe', 'wtpc' ),
                        $max_height
                    ), 
                    'error' 
                );
                $valid = false;
            endif;
                
        endif;

        return $valid;
    }

    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id = 0 )
    {
        $real_product_id = $variation_id ? $variation_id : $product_id;

        if( WTPC_Helpers::is_measurable( $real_product_id ) ) :

            $width = isset( $_POST['wtpc_width'] ) ? (int)$_POST['wtpc_width'] : 0;
            $height = isset( $_POST['wtpc_height'] ) ? (int)$_POST['wtpc_height'] : 0;

            $cart_item_data['wtpc_width'] = $width;
            $cart_item_data['wtpc_height'] = $height;
            $cart_item_data['wtpc_area'] =  preg_replace( '#\.?0+$#', '', round( ( $width * $height ) / ( 1000 * 1000 ), 3 ) );

        endif;

        return $cart_item_data;
    }

    public function get_cart_item_data( $item_data, $cart_item_data )
    {
        if( isset( $cart_item_data['wtpc_width'] ) ) :
            $item_data[] = array(
                'key' => __( 'Breite (mm)', 'wtpc' ),
                'value' => $cart_item_data['wtpc_width']
            );
        endif;

        if( isset( $cart_item_data['wtpc_height'] ) ) :
            $item_data[] = array(
                'key' => __( 'Höhe (mm)', 'wtpc' ),
                'value' => $cart_item_data['wtpc_height']
            );
        endif;

        if( isset( $cart_item_data['wtpc_area'] ) ) :
            $item_data[] = array(
                'key' => __( 'Gesamtfläche (m²)', 'wtpc' ),
                'value' => $cart_item_data['wtpc_area']
            );
        endif;

        return $item_data;
    }    

    public function add_order_meta( $item, $cart_item_key, $values, $order )
    {
        if( isset( $values['wtpc_width'] ) ) :
            $item->add_meta_data(
                'wtpc_width',
                $values['wtpc_width']
            );
        endif;
        
        if( isset( $values['wtpc_height'] ) ) :
            $item->add_meta_data(
                'wtpc_height',
                $values['wtpc_height']
            );
        endif;
        
        if( isset( $values['wtpc_area'] ) ) :
            $item->add_meta_data(
                'wtpc_area',
                $values['wtpc_area']
            );
        endif;
    }

    public function format_order_meta( $formatted_meta, $order )
    {   
        foreach( $formatted_meta as $key=>$value ) :
            switch( $value->key ) :
                case 'wtpc_width' :
                    $formatted_meta[$key]->display_value = $value->value;
                    $formatted_meta[$key]->display_key = __( 'Breite (mm)', 'wtpc' );
                break;
                case 'wtpc_height' :
                    $formatted_meta[$key]->display_value = $value->value;
                    $formatted_meta[$key]->display_key = __( 'Höhe (mm)', 'wtpc' );
                break;
                case 'wtpc_area' :
                    $formatted_meta[$key]->display_value = $value->value;
                    $formatted_meta[$key]->display_key = __( 'Gesamtfläche (m²)', 'wtpc' );
                break;
            endswitch;
        endforeach;

        return $formatted_meta;
    }

    public function get_cart_item_from_session( $cart_item, $values ) 
    {
		if ( isset( $values['wtpc_width'] ) ) :
			$cart_item['wtpc_width'] = $values['wtpc_width'];
        endif;

		if ( isset( $values['wtpc_height'] ) ) :
			$cart_item['wtpc_height'] = $values['wtpc_height'];
        endif;

		if ( isset( $values['wtpc_area'] ) ) :
			$cart_item['wtpc_area'] = $values['wtpc_area'];
        endif;

		return $cart_item;
    }
    
    public function calculate_product_price( $cart_object )
    {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;
        
        foreach ( $cart_object->get_cart() as $cart_item ) :

            if( isset( $cart_item['wtpc_area'] ) ) :
                $cart_item['data']->set_price( $cart_item['data']->get_price() * $cart_item['wtpc_area'] );
            endif;

        endforeach;
    }

    public function output_variation_form( $loop, $variation_data, $variation )
    {
        include WTPC_DIR.'/templates/admin/variation_form.php';
    }

    public function save_variation_product_data( $variation_id, $i )
    {
        $is_measurable = isset( $_POST['_wtpc_is_measurable'][$i] );

        $min_width = isset( $_POST['_wtpc_min_width'][$i] ) ? (int)$_POST['_wtpc_min_width'][$i] : '';
        $max_width = isset( $_POST['_wtpc_max_width'][$i] ) ? (int)$_POST['_wtpc_max_width'][$i] : '';

        $min_height = isset( $_POST['_wtpc_min_height'][$i] ) ? (int)$_POST['_wtpc_min_height'][$i] : '';
        $max_height = isset( $_POST['_wtpc_max_height'][$i] ) ? (int)$_POST['_wtpc_max_height'][$i] : '';

        WTPC_Helpers::set_measurable( $variation_id, $is_measurable );

        WTPC_Helpers::set_min_width( $variation_id, $min_width );
        WTPC_Helpers::set_max_width( $variation_id, $max_width );

        WTPC_Helpers::set_min_height( $variation_id, $min_height );
        WTPC_Helpers::set_max_height( $variation_id, $max_height );
    }

    public function add_variation_data( $variations, $product, $variation )
    {
        $variations[ 'wtpc_is_measurable' ] = WTPC_Helpers::is_measurable( $variation->get_id() );
        
        $variations[ 'wtpc_min_width' ] = WTPC_Helpers::get_min_width( $variation->get_id() );
        $variations[ 'wtpc_max_width' ] = WTPC_Helpers::get_max_width( $variation->get_id() );
        $variations[ 'wtpc_min_height' ] = WTPC_Helpers::get_min_height( $variation->get_id() );
        $variations[ 'wtpc_max_height' ] = WTPC_Helpers::get_max_height( $variation->get_id() );

        return $variations;
    }
}