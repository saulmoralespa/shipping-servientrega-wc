<?php

use Servientrega\WebService;

$this->init_settings();
global $woocommerce;
$wc_main_settings = [];

if(isset($_POST['servientrega_validate_credentials']))
{
    if ( !isset( $_POST['shipping_servientrega_wc_ss_general'] )
        || !wp_verify_nonce( $_POST['shipping_servientrega_wc_ss_general'], 'shipping_servientrega_wc_ss_general' ))
        return;

    $servientrega_user = sanitize_text_field($_POST['servientrega_user']);
    $servientrega_password = sanitize_text_field($_POST['servientrega_password']);
    $servientrega_billing_code = sanitize_text_field($_POST['servientrega_billing_code']);
    $servientrega_id_client = sanitize_text_field($_POST['servientrega_id_client']);
    $test_mode = (isset($_POST['servientrega_production']) && $_POST['servientrega_production'] ==='yes') ? true : false;
    $guide_free_shipping = (isset($_POST['servientrega_guide_free_shipping']) && $_POST['servientrega_guide_free_shipping'] ==='yes') ? true : false;

    $wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');
    $wc_main_settings['production'] = $test_mode;
    $wc_main_settings['servientrega_guide_free_shipping'] = $guide_free_shipping;
    $wc_main_settings['servientrega_user'] = (isset($_POST['servientrega_user'])) ? sanitize_text_field($_POST['servientrega_user']) : 'testajagroup';
    $wc_main_settings['servientrega_password']        = (isset($_POST['servientrega_password'])) ? sanitize_text_field($_POST['servientrega_password']) : 'Colombia1';
    $wc_main_settings['servientrega_billing_code']  = (isset($_POST['servientrega_billing_code'])) ? sanitize_text_field($_POST['servientrega_billing_code']) : 'Cargue SMP';
    $wc_main_settings['servientrega_id_client'] = (isset($_POST['servientrega_id_client'])) ? sanitize_text_field($_POST['servientrega_id_client']) : '900917801';

    update_option('woocommerce_servientrega_shipping_settings',$wc_main_settings);

    servientrega_validate_credentials($test_mode,$servientrega_user,$servientrega_password,$servientrega_billing_code,$servientrega_id_client);
}

function servientrega_validate_credentials($test_mode,$servientrega_user,$servientrega_password,$servientrega_billing_code,$servientrega_id_client)
{

    if(strpos($servientrega_billing_code, 'SER') === false){
        update_option('servientrega_validation_error','<small style="color:red">Revise que este ingresando el Código de facturación correctamente</small>');
        $wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');
        $wc_main_settings['servientrega_billing_code'] = '';
        update_option('woocommerce_servientrega_shipping_settings',$wc_main_settings);
        return false;
    }

    try{
        $servientrega = new WebService($servientrega_user, $servientrega_password, $servientrega_billing_code, $servientrega_id_client, get_bloginfo('name') );
        $params = [
            'num_Guia' => '292710965',
            'num_GuiaFinal' => '292710965'
        ];
        $servientrega->AnularGuias($params);
    }catch (Exception $exception){
        update_option('servientrega_validation_error', '<small style="color:red">' . $exception->getMessage() . '</small>');
        return false;
    }

    update_option('servientrega_validation_error', '');
    return true;

}

if(isset($_POST['servientrega_genaral_save_changes_button']))
{

    if ( !isset( $_POST['shipping_servientrega_wc_ss_general'] )
        || !wp_verify_nonce( $_POST['shipping_servientrega_wc_ss_general'], 'shipping_servientrega_wc_ss_general' ))
        return;

    $servientrega_user = sanitize_text_field($_POST['servientrega_user']);
    $servientrega_password = sanitize_text_field($_POST['servientrega_password']);
    $servientrega_billing_code = sanitize_text_field($_POST['servientrega_billing_code']);
    $servientrega_id_client = sanitize_text_field($_POST['servientrega_id_client']);
    $test_mode = (isset($_POST['servientrega_production']) && $_POST['servientrega_production'] ==='yes') ? true : false;
    $guide_free_shipping = (isset($_POST['servientrega_guide_free_shipping']) && $_POST['servientrega_guide_free_shipping'] ==='yes') ? true : false;

    $wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');
    $wc_main_settings['production']     = $test_mode;
    $wc_main_settings['servientrega_guide_free_shipping'] = $guide_free_shipping;
    $wc_main_settings['servientrega_user'] = (isset($_POST['servientrega_user'])) ? sanitize_text_field($_POST['servientrega_user']) : 'testajagroup';
    $wc_main_settings['servientrega_password']        = (isset($_POST['servientrega_password'])) ? sanitize_text_field($_POST['servientrega_password']) : 'Colombia1';
    $wc_main_settings['servientrega_billing_code']  = (isset($_POST['servientrega_billing_code'])) ? sanitize_text_field($_POST['servientrega_billing_code']) : 'Cargue SMP';
    $wc_main_settings['servientrega_id_client'] = (isset($_POST['servientrega_id_client'])) ? sanitize_text_field($_POST['servientrega_id_client']) : '900917801';
    $wc_main_settings['servientrega_address_sender'] = (isset($_POST['servientrega_address_sender'])) ? sanitize_text_field($_POST['servientrega_address_sender']) : '';
    $wc_main_settings['servientrega_agreement_pay'] = (isset($_POST['servientrega_agreement_pay'])) ? sanitize_text_field($_POST['servientrega_agreement_pay']) : '';
    $wc_main_settings['servientrega_product_type'] = (isset($_POST['servientrega_product_type'])) ? sanitize_text_field($_POST['servientrega_product_type']) : '';

    servientrega_validate_credentials($test_mode,$servientrega_user,$servientrega_password,$servientrega_billing_code,$servientrega_id_client);
    
    update_option('woocommerce_servientrega_shipping_settings',$wc_main_settings);
}

$general_settings = get_option('woocommerce_servientrega_shipping_settings');
$general_settings = empty($general_settings) ? array() : $general_settings;
$validation = get_option('wf_dhl_shipping_validation_data');

$agreementPay = [
    2    => __( 'Crédito'),
    4 => __( 'Pago contra entrega')
];

$address_sender = include dirname(__FILE__) . '/../cities.php';


$products_type = [
    1 => __('Documento unitario'),
    2 => __('Mercancia premier'),
    6 => __('Mercancia industrial')
];

$htmlGeneral = '<img style="float:right;" src="' . shipping_servientrega_wc_ss()->assets . trailingslashit('img') . 'servientrega.png' . '" width="80" height="80" />';

$htmlGeneral .= '
<table>
    <tr valign="top">
        <td style="width:25%;padding-top:40px;font-weight:bold;">
            <label for="servientrega_production">' .  __('Información cuenta de Servientrega') . '</label><span class="woocommerce-help-tip" data-tip="' . __('La información suministrada por Servientrega, relacionada con el acuerdo como Usuario, Contraseña, Código de Facturación, Forma de pago') . '"></span>
        </td>' . $this->get_option('woocommerce_servientrega_production') . '
        <td scope="row" class="titledesc" style="display:block;margin-bottom:20px;margin-top:3px;padding-top:40px;">
            <fieldset style="padding:3px;">';
                if(isset($general_settings['production']) && $general_settings['production'] === true)
                {
                    $htmlGeneral .= '<input class="input-text regular-input " type="radio" name="servientrega_production"  id="servientrega_production" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable;  $htmlGeneral .= 'value="no">' . __('Pruebas');
                    $htmlGeneral .= '<input class="input-text regular-input " type="radio"  name="servientrega_production" checked="true" id="servientrega_production" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable; $htmlGeneral .= 'value="yes">' . __('Producción');
                 }else {
                    $htmlGeneral .= '<input class="input-text regular-input" type="radio" name="servientrega_production" checked="true" id="servientrega_production" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable;
                    $htmlGeneral .= 'value="no">' . __('Pruebas');
                    $htmlGeneral .= '<input class="input-text regular-input" type="radio" name="servientrega_production" id="servientrega_production" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable;
                    $htmlGeneral .= 'value="yes">' . __('Producción') . '</br></fieldset>';
                }
            $htmlGeneral .= '<fieldset style="padding:3px;">
                <input class="input-text regular-input" required type="text" name="servientrega_user" id="servientrega_user" ';
                $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                $htmlGeneral .= $disable; $htmlGeneral .= 'value="';
                $value = (isset($general_settings['servientrega_user'])) ? $general_settings['servientrega_user'] : 'testajagroup';
                $htmlGeneral .= "$value\" ";
                $htmlGeneral .= 'placeholder="testajagroup"> <label for="servientrega_user">' . __('Usuario') . '</label> <span class="woocommerce-help-tip" data-tip="' . __('El usario con el que ingresa al SISCLINET') . '"></span>
            </fieldset>';
            $htmlGeneral .= '<fieldset style="padding:3px;">
                <input class="input-text regular-input" required type="password" name="servientrega_password" id="servientrega_password" ';
            $disable =  ($validation === 'done') ? 'disabled="true" ' : ' ';
            $htmlGeneral .= $disable;
            $htmlGeneral .= 'value="';
            $value = (isset($general_settings['servientrega_password'])) ? $general_settings['servientrega_password'] : 'Colombia1';
            $htmlGeneral .= "$value\">"; $htmlGeneral .= '<label for="servientrega_password">' . __('Contraseña') .'</label> <span class="woocommerce-help-tip" data-tip="' . __('La contraseña con la que ingresa SISCLINET') . '"></span>
            </fieldset>';
            $htmlGeneral .= '<fieldset style="padding:3px;">
                <input class="input-text regular-input" required type="text" name="servientrega_billing_code" id="servientrega_billing_code "'; $disable =  ($validation === 'done') ? 'disabled="true "' : ' '; $htmlGeneral .= $disable;  $htmlGeneral .= 'value= "'; $value = (isset($general_settings['servientrega_billing_code'])) ? $general_settings['servientrega_billing_code'] : 'SER408'; $htmlGeneral .= "$value\">"; $htmlGeneral .= '<label for="servientrega_billing_code">' . __('Código de Facturación') . '</label> <span class="woocommerce-help-tip" data-tip="' . __('Código de Facturación lo encuentro dentro del panel de SISCLINET') . '"></span>
            </fieldset>';
            $htmlGeneral .= '<fieldset style="padding:3px;">
                <input class="input-text regular-input" required type="number" name="servientrega_id_client" id="servientrega_id_client" '; $disable =  ($validation === 'done') ? 'disabled="true "' : ' '; $htmlGeneral .= $disable;  $htmlGeneral .= 'value= "'; $value = (isset($general_settings['servientrega_id_client'])) ? $general_settings['servientrega_id_client'] : '900917801'; $htmlGeneral .= "$value\">"; $htmlGeneral .= '<label for="servientrega_id_client">' . __('ID cliente o NIT') . '</label> <span class="woocommerce-help-tip" data-tip="' . __('Es el mismo número NIT, lo encuentro dentro del panel de SISCLINET') . '"></span>
            </fieldset>';
            $htmlGeneral .= get_option('servientrega_validation_error') . '<fieldset style="padding:3px;">
                <input type="submit" value="Validar Credenciales" class="button button-secondary" name="servientrega_validate_credentials">
            </fieldset>
        </td>
    </tr>
    <tr valign="top">
        <td style="width:25%;font-weight:bold;">
            <label for="servientrega_address_sender">' . __('Ciudad Remitente') . '</label><span class="woocommerce-help-tip" data-tip="' . __('La ciudad de Origen conforme el acuerdo con Servientrega') . '"></span>
        </td>
        <td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">';
        $htmlGeneral .= '<fieldset style="padding:3px;">
                        <select class="wc-enhanced-select" name="servientrega_address_sender" id="servientrega_address_sender">';
        $htmlGeneral .= '<option value="">Seleccione ciudad de origen</option>';
        foreach ($address_sender as $key => $address):
            $selected = isset($general_settings['servientrega_address_sender']) && $general_settings['servientrega_address_sender'] === (string)$key ? 'selected' : '';
            $htmlGeneral .= "<option value='{$key}' $selected>{$address}</option>";
        endforeach;
        $htmlGeneral .= '</select>
            </fieldset>
       </td>
    </tr>';
    $htmlGeneral .= '<tr valign="top">
        <td style="width:25%;font-weight:bold;">
            <label for="servientrega_agreement_pay">' . __('Forma de pago') . '</label><span class="woocommerce-help-tip" data-tip="' . __('El acuerdo que manejara los pagos de los envíos') . '"></span>
        </td>
        <td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
            <fieldset style="padding:3px;">
                <select class="wc-enhanced-select" name="servientrega_agreement_pay" id="servientrega_agreement_pay">';
                    foreach ($agreementPay as $key => $value):
                        $selected = isset($general_settings['servientrega_agreement_pay']) && $general_settings['servientrega_agreement_pay'] === (string)$key ? 'selected' : '';
                        $htmlGeneral .= "<option value='{$key}' $selected>{$value}</option>";
                    endforeach;
                $htmlGeneral .= '</select>
            </fieldset>
        </td>
     </tr>';
    $htmlGeneral .= '<tr valign="top">
        <td style="width:25%;font-weight:bold;">
            <label for="servientrega_product_type">' . __('Tipo de producto') . '</label><span class="woocommerce-help-tip" data-tip="' . __('El tipo de producto por defecto premier') . '"></span>
        </td>
        <td scope="row" class="titledesc" style="display: block;margin-bottom: 20px;margin-top: 3px;">
            <fieldset style="padding:3px;">
                <select class="wc-enhanced-select" name="servientrega_product_type" id="servientrega_product_type">';
                    foreach ($products_type as $key => $value):
                        $selected = isset($general_settings['servientrega_product_type']) && $general_settings['servientrega_product_type'] === (string)$key ? 'selected' : '';
                        $htmlGeneral .= "<option value='{$key}' $selected>{$value}</option>";
                    endforeach;
                    $htmlGeneral .= '</select>
            </fieldset>
        </td>
     </tr>';
     $htmlGeneral .= '<tr valign="top">
 <td style="width:25%;font-weight:bold;">
            <label for="servientrega_guide_free_shipping">' . __('Generar guías cuando el envío es gratuito') . '</label><span class="woocommerce-help-tip" data-tip="' . __('Permitir generar gúias cuando el costo del envío es gratuito') . '"></span>
        </td>
        <td scope="row" class="titledesc" style="display:block;margin-bottom:20px;">
<fieldset style="padding:3px;">';
                if(isset($general_settings['servientrega_guide_free_shipping']) && $general_settings['servientrega_guide_free_shipping'] === true)
                {
                    $htmlGeneral .= '<input class="input-text regular-input " type="radio" name="servientrega_guide_free_shipping"  id="servientrega_guide_free_shipping" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable;  $htmlGeneral .= 'value="no">' . __('No');
                    $htmlGeneral .= '<input class="input-text regular-input " type="radio"  name="servientrega_guide_free_shipping" checked="true" id="servientrega_guide_free_shipping" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable; $htmlGeneral .= 'value="yes">' . __('Sí');
                 }else {
                    $htmlGeneral .= '<input class="input-text regular-input" type="radio" name="servientrega_guide_free_shipping" checked="true" id="servientrega_guide_free_shipping" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable;
                    $htmlGeneral .= 'value="no">' . __('No');
                    $htmlGeneral .= '<input class="input-text regular-input" type="radio" name="servientrega_guide_free_shipping" id="servientrega_guide_free_shipping" ';
                    $disable = ($validation === 'done') ? 'disabled="true" ' : ' ';
                    $htmlGeneral .= $disable;
                    $htmlGeneral .= 'value="yes">' . __('Sí') . '</br></fieldset>';
                }
$htmlGeneral .= '</fieldset>
       </td>
    </tr>';
    $htmlGeneral .= '<tr>
        <td colspan="2" style="text-align:center;">' .
        wp_nonce_field( "shipping_servientrega_wc_ss_general", "shipping_servientrega_wc_ss_general" ) . '
            <button type="submit" class="button button-primary" name="servientrega_genaral_save_changes_button">' . __('Guardar cambios') . '</button>
        </td>
    </tr>';

return $htmlGeneral;