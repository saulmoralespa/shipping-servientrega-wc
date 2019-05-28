<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 26/03/19
 * Time: 06:14 PM
 */


$address_sender = array(
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
            'default'     => 'no',
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
        )
    ),
    $address_sender
);
