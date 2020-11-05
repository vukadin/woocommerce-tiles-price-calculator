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
        add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'output_dimensions_form' ) );
        add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'output_dimensions_hidden_input' ) );
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
            'label'    => __( 'Measures', 'wtpc' ),
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

        if( $product->get_type() == 'simple' && WTPC_Helpers::is_measurable( $product->get_id() ) ) :

            include WTPC_DIR.'/templates/front/simple_add_to_cart.php';

        endif;
    }

    public function output_dimensions_hidden_input()
    {
        global $product;

        if( $product->get_type() == 'simple' && WTPC_Helpers::is_measurable( $product->get_id() ) ) :

            include WTPC_DIR.'/templates/front/simple_inputs.php';

        endif;
    }

    
}