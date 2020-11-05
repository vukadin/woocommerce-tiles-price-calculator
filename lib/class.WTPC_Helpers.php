<?php if( !defined( 'ABSPATH' ) ) exit;

class WTPC_Helpers
{
    public static function is_measurable( $product_id )
    {
        return get_post_meta( $product_id, '_wtpc_is_measurable', true ) === 'yes';
    }

    public static function set_measurable( $product_id, $value )
    {
        $value = $value ? 'yes' : '';
        update_post_meta( $product_id, '_wtpc_is_measurable', $value );
    }

    public static function get_max_width( $product_id )
    {
        return get_post_meta( $product_id, '_wtpc_max_width', true );
    }

    public static function set_max_width( $product_id, $value )
    {
        $value = $value > 0 ? $value : '';
        update_post_meta( $product_id, '_wtpc_max_width', $value );
    }

    public static function get_max_height( $product_id )
    {
        return get_post_meta( $product_id, '_wtpc_max_height', true );
    }

    public static function set_max_height( $product_id, $value )
    {
        $value = $value > 0 ? $value : '';
        update_post_meta( $product_id, '_wtpc_max_height', $value );
    }

    public static function get_nonce_name()
    {
        return 'wtpc_nonce';
    }

    public static function get_nonce_action( $product_id )
    {
        return sprintf(
            'wtpc-measures-metabox-%d',
            $product_id
        );
    }
}