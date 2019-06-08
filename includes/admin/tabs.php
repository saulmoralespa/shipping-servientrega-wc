<?php

$tab = (!empty($_GET['subtab'])) ? esc_attr($_GET['subtab']) : 'general';

$html = '
<div class="wrap">
    <style>
        .woocommerce-help-tip{color:darkgray !important;}
        .woocommerce-save-button{display:none !important;}
        .woocommerce-help-tip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .woocommerce-help-tip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
        }

        .woocommerce-help-tip:hover .tooltiptext {
            visibility: visible;
        }
    </style>
    <hr class="wp-header-end">';
$html .= $this->servientrega_shipping_page_tabs($tab);
switch ($tab) {
    case "general":
        $html .= require_once('general.php');
        break;
    case "rates":
        $html .= require_once('rates.php');
        break;
    case "packing":
        $html .= require_once('packing.php');
        break;
    case "licence":
        //$plugin_name = 'dhl';
        //include( WF_DHL_PAKET_EXPRESS_ROOT_PATH . 'wf_api_manager/html/html-wf-activation-window.php' );
        break;
}
$html .= '</div>';
echo $html;