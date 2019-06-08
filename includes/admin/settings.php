<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 26/03/19
 * Time: 06:14 PM
 */



/*ID TIPO TRAYECTO

1 NORMAL
2 HOY MISMO
3 CERO HORAS
4 48 HORAS
5 72 HORAS*/


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Array of settings
 */
wp_enqueue_media();
return array(
    'servientrega_tab_box_key' => array(
        'type' => 'servientrega_tab_box'
    ),

);

/*$address_sender = array(
    'address_sender' => array(
        'title' => __('Ciudad del remitente (donde se encuentra ubicada la tienda)'),
        'type'        => 'select',
        'class'       => 'wc-enhanced-select',
        'description' => __('Se recomienda selecionar ciudadades centrales'),
        'desc_tip' => true,
        'default' => true,
        'options' => include dirname(__FILE__) . '/../cities.php'
    )
);

return array_merge(
    array(
        'enabled' => array(
            'title' => __('Activar/Desactivar'),
            'type' => 'checkbox',
            'label' => __('Activar Servientrega'),
            'default' => 'no'
        ),
        'title'        => array(
            'title'       => __( 'Título método de envío' ),
            'type'        => 'text',
            'description' => __( 'Esto controla el título que el usuario ve durante el pago' ),
            'default'     => __( 'Servientrega' ),
            'desc_tip'    => true,
        ),
        'debug'        => array(
            'title'       => __( 'Depurador' ),
            'label'       => __( 'Habilitar el modo de desarrollador' ),
            'type'        => 'checkbox',
            'default'     => 'yes',
            'description' => __( 'Enable debug mode to show debugging information on your cart/checkout.' ),
            'desc_tip' => true,
        ),
        'environment' => array(
            'title' => __('Enntorno'),
            'type'        => 'select',
            'class'       => 'wc-enhanced-select',
            'description' => __('Entorno de pruebas o producción'),
            'desc_tip' => true,
            'default' => '1',
            'options'     => array(
                '0'    => __( 'Producción'),
                '1' => __( 'Pruebas'),
            ),
        ),
        'user'      => array(
            'title' => __( 'Usuario' ),
            'type'  => 'text',
            'description' => __( 'Login del Usuario que realiza la operación' ),
            'desc_tip' => true,
        ),
        'password' => array(
            'title' => __( 'Contraseña' ),
            'type'  => 'password',
            'description' => __( 'La contraseña' ),
            'desc_tip' => true,
        ),
        'billing_code'      => array(
            'title' => __( 'Código Facturación' ),
            'type'  => 'text',
            'description' => __( 'Código de facturación del cliente' ),
            'desc_tip' => true,
        ),
        'way_pay' => array(
            'title' => __( 'Forma de pago' ),
            'type'        => 'select',
            'class'       => 'wc-enhanced-select',
            'description' => __( 'El acuerdo que manejara los pagos de los envíos' ),
            'desc_tip' => true,
            'default' => 2,
            'options'     => array(
                2    => __( 'Crédito'),
                4 => __( 'Pago contra entrega'),
            )
        )
    ),
    $address_sender
);*/
