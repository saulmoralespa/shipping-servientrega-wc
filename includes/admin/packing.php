<?php

$general_settings = get_option('woocommerce_servientrega_shipping_settings');

$title = isset($general_settings['shipping_servientrega_matriz']) && $general_settings['shipping_servientrega_matriz'] === true ? 'Resubir Archivo .xls (Matriz Mercancia)' : 'Subir Archivo .xls (Matriz Mercancia)';

$htmlPacking = '
<table>
    <tr valign="top">
        <td style="width:25%;font-weight:bold;padding-top:40px;">
            <label for="servientrega_upload_matriz">' . $title . '</label><span class="woocommerce-help-tip" data-tip="' . __('Suba el archivo excel con extensión .xsl, este archivo sube información a a la base de datos (id_ciudad_destino, tiempo_entrega_comercial, tipo_trayecto, restriccion_fisica)') . '"></span>
        </td>
        <td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
            <fieldset style="padding:3px;">
                <input id="servientrega_upload_matriz" accept=".xls" type="file">
            </fieldset>
        </td>
     </tr>';

return $htmlPacking;