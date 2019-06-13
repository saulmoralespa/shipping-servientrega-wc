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
return [
    'servientrega_tab_box_key' => [
        'type' => 'servientrega_tab_box'
    ]
];