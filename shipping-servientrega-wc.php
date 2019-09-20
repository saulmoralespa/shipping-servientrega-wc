<?php
/**
 * Plugin Name: Shipping Servientrega Woocommerce
 * Description: Shipping Servientrega Woocommerce is available for Colombia
 * Version: 3.0.18
 * Author: Saul Morales Pacheco
 * Author URI: https://saulmoralespa.com
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * WC tested up to: 3.5
 * WC requires at least: 2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if(!defined('SHIPPING_SERVIENTREGA_WC_SS_VERSION')){
    define('SHIPPING_SERVIENTREGA_WC_SS_VERSION', '3.0.18');
}

add_action( 'plugins_loaded', 'shipping_servientrega_wc_ss_init', 1 );

function shipping_servientrega_wc_ss_init()
{
    if ( ! shipping_servientrega_wc_ss_requirements() )
        return;

    shipping_servientrega_wc_ss()->run_servientrega_wc();

}

function shipping_servientrega_wc_ss_notices( $notice ) {
    ?>
    <div class="error notice">
        <p><?php echo esc_html( $notice ); ?></p>
    </div>
    <?php
}

function shipping_servientrega_wc_ss_requirements(){

    if ( version_compare( '7.1.0', PHP_VERSION, '>' ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    shipping_servientrega_wc_ss_notices( 'Shipping Servientrega Woocommerce: Requiere la versión de php 7.1 o superior' );
                }
            );
        }
        return false;
    }

    $openssl_warning = 'Shipping Servientrega Woocommerce: Requiere la extensión OpenSSL 1.0.1 o superior se encuentre instalada';

    if ( ! defined( 'OPENSSL_VERSION_TEXT' ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() use ( $openssl_warning ) {
                    shipping_servientrega_wc_ss_notices($openssl_warning );
                }
            );
        }
        return false;
    }

    preg_match( '/^(?:Libre|Open)SSL ([\d.]+)/', OPENSSL_VERSION_TEXT, $matches );
    if ( empty( $matches[1] ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() use ( $openssl_warning ) {
                    shipping_servientrega_wc_ss_notices( $openssl_warning );
                }
            );
        }
        return false;
    }

    if ( ! version_compare( $matches[1], '1.0.1', '>=' ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() use ( $openssl_warning ) {
                    shipping_servientrega_wc_ss_notices( $openssl_warning );
                }
            );
        }
        return false;
    }

    if ( ! extension_loaded( 'soap' ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    shipping_servientrega_wc_ss_notices( 'Shipping Servientrega Woocommerce: Requiere la extensión soap se encuentre instalada' );
                }
            );
        }
        return false;
    }

    if ( ! extension_loaded( 'xml' ) ){
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    shipping_servientrega_wc_ss_notices( 'Shipping Servientrega Woocommerce: Requiere la extensión xml se encuentre instalada' );
                }
            );
        }
        return false;
    }

    if ( ! extension_loaded( 'simplexml' ) ){
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    shipping_servientrega_wc_ss_notices( 'Shipping Servientrega Woocommerce: Requiere la extensión simplexml se encuentre instalada' );
                }
            );
        }
        return false;
    }

    if ( !in_array(
        'woocommerce/woocommerce.php',
        apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
        true
    ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    shipping_servientrega_wc_ss_notices( 'Shipping Servientrega Woocommerce: Requiere que se encuentre instalado y activo el plugin: Woocommerce' );
                }
            );
        }
        return false;
    }

    if ( ! in_array(
        'departamentos-y-ciudades-de-colombia-para-woocommerce/departamentos-y-ciudades-de-colombia-para-woocommerce.php',
        apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
        true
    ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    shipping_servientrega_wc_ss_notices( 'Shipping Servientrega Woocommerce: Requiere que se encuentre instalado y activo el plugin: Departamentos y ciudades de Colombia para Woocommerce' );
                }
            );
        }
        return false;
    }

    $woo_countries   = new WC_Countries();
    $default_country = $woo_countries->get_base_country();

    if ( ! in_array( $default_country, array( 'CO' ), true ) ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action(
                'admin_notices',
                function() {
                    $country = 'Shipping Servientrega Woocommerce: Requiere que el país donde se encuentra ubicada la tienda sea Colombia '  .
                        sprintf(
                            '%s',
                            '<a href="' . admin_url() .
                            'admin.php?page=wc-settings&tab=general#s2id_woocommerce_currency">' .
                            'Click para establecer</a>' );
                    shipping_servientrega_wc_ss_notices( $country );
                }
            );
        }
        return false;
    }

    return true;
}

function shipping_servientrega_wc_ss(){
    static $plugin;
    if (!isset($plugin)){
        require_once('includes/class-shipping-servientrega-wc-plugin.php');
        $plugin = new Shipping_Servientrega_WC_Plugin(__FILE__, SHIPPING_SERVIENTREGA_WC_SS_VERSION);
    }
    return $plugin;
}

add_action( 'woocommerce_product_options_shipping', array('Shipping_Servientrega_WC_Plugin', 'add_custom_shipping_option_to_products'));