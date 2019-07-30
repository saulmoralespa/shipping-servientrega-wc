<?php

$this->init_settings();

global $woocommerce;

$wc_main_settings = array();

if(isset($_POST['servientrega_rates_save_changes_button']))
{

    if ( !isset( $_POST['shipping_servientrega_wc_ss_rates'] ) ||
        !current_user_can('administrator')
        || !wp_verify_nonce( $_POST['shipping_servientrega_wc_ss_rates'], 'shipping_servientrega_wc_ss_rates' ))
        return;

    $wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');

   	$wc_main_settings['rate']['weight'] = shipping_servientrega_wc_ss_sanitize($_POST['rate']['weight']);
    $wc_main_settings['rate']['nacional'] = shipping_servientrega_wc_ss_sanitize($_POST['rate']['nacional']);
    $wc_main_settings['rate']['zonal'] = shipping_servientrega_wc_ss_sanitize($_POST['rate']['zonal']);
    $wc_main_settings['rate']['urbano'] = shipping_servientrega_wc_ss_sanitize($_POST['rate']['urbano']);
    $wc_main_settings['rate']['especial'] = shipping_servientrega_wc_ss_sanitize($_POST['rate']['especial']);
    $wc_main_settings['rate']['additional'] = shipping_servientrega_wc_ss_sanitize($_POST['rate']['additional']);
    $wc_main_settings['rate']['freight'] = sanitize_text_field($_POST['rate']['freight']);

    update_option('woocommerce_servientrega_shipping_settings',$wc_main_settings);

}

$general_settings = get_option('woocommerce_servientrega_shipping_settings');

function shipping_servientrega_wc_ss_sanitize($rate){

    $result = [];

    foreach ($rate as $key => $val){
        $result[$key] = sanitize_text_field($val);
    }

    return $result;

}

$htmlRates = '
<table>
    <tr id="rates_options" valign="top">
        <td class="titledesc" colspan="2" style="padding-top:40px;padding-left:0px;">
            <strong>Liquidación y costos de trayectos</strong><br><br>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>Liquidación Kg</th>
                        <th>Costo trayecto Nacional ($)</th>
                        <th>Costo trayecto Zonal ($)</th>
                        <th>Costo trayecto Urbano ($)</th>
                        <th>Costo trayecto Especial ($)</th>
                        <th>Acción ($)</th>
                    </tr>
                </thead>
                <tbody>';
                     if(isset($general_settings['rate']['nacional'])):
                         for ($i = 0; $i < count($general_settings['rate']['nacional']); $i++ ){
                             $action = $i > 0 ? '<td class="remove"><span style="font-size:30px;color:red;cursor:pointer;" class="dashicons dashicons-minus"></span></td>'
                                 : '<td class="add"><span style="font-size:30px;color:green;cursor:pointer;" class="dashicons dashicons-plus-alt""></span></td>';
                             $htmlRates .= '<tr><td><input type="number" name="rate[weight][]" placeholder="3"  min="1" value="' . $general_settings['rate']['weight'][$i].'" size="2"></td>
                        <td><input type="text" name="rate[nacional][]" class="wc_input_price" placeholder="10850" value="'.$general_settings['rate']['nacional'][$i].'" size="10"></td>
                        <td><input type="text" name="rate[zonal][]" class="wc_input_price" placeholder="7400" value="'.$general_settings['rate']['zonal'][$i].'" size="10"></td>
                        <td><input type="text" name="rate[urbano][]" class="wc_input_price" placeholder="6350" value="'.$general_settings['rate']['urbano'][$i].'" size="10"></td>
                        <td><input type="text" name="rate[especial][]" class="wc_input_price" placeholder="20000" value="'.$general_settings['rate']['especial'][$i].'" size="10"></td>
                       '.$action.'
                    </tr>';
                         }
                         else:
                             $htmlRates .= '<tr><td><input type="number" name="rate[weight][]" placeholder="3"  min="1" value="" size="2" required></td>
                        <td><input type="text" name="rate[nacional][]" class="wc_input_price" placeholder="10850" value="" size="10" required></td>
                        <td><input type="text" name="rate[zonal][]" class="wc_input_price" placeholder="7400" value="" size="10" required></td>
                        <td><input type="text" name="rate[urbano][]" class="wc_input_price" placeholder="6350" value="" size="10" required></td>
                        <td><input type="text" name="rate[especial][]" class="wc_input_price" placeholder="20000" value="" size="10" required></td>
                        <td class="add"><span style="font-size:30px;color:green;cursor:pointer;" class="dashicons dashicons-plus-alt"></span></td>
                    </tr>';
                     endif;
                    $additionalNational = isset($general_settings['rate']['additional']['nacional']) ? $general_settings['rate']['additional']['nacional'] : '';
                    $additionalZonal = isset($general_settings['rate']['additional']['zonal']) ? $general_settings['rate']['additional']['zonal'] : '';
                    $additionalUrban = isset($general_settings['rate']['additional']['urbano']) ? $general_settings['rate']['additional']['urbano'] : '';
                    $additionalSpecial = isset($general_settings['rate']['additional']['especial']) ? $general_settings['rate']['additional']['especial'] : '';
                    $htmlRates .= '<tr class="additional">
                        <td><input type="text" placeholder="Kilo adicional" readonly></td>
                        <td><input type="text" name="rate[additional][nacional]" class="wc_input_price" placeholder="3200" value="'.$additionalNational.'" size="10" required></td>
                        <td><input type="text" name="rate[additional][zonal]" class="wc_input_price" placeholder="2600" value="'.$additionalZonal.'" size="10" required></td>
                        <td><input type="text" name="rate[additional][urbano]" class="wc_input_price" placeholder="2400" value="'.$additionalUrban.'" size="10" required></td>
                        <td><input type="text" name="rate[additional][especial]" class="wc_input_price" placeholder="6400" value="'.$additionalSpecial.'" size="10" required></td>
                    </tr>';
                    $freight = isset($general_settings['rate']['freight']) ? $general_settings['rate']['freight'] : '000';
                    $htmlRates .= '<tr class="freight">
                        <td><input type="text" placeholder="SobreFlete Minímo" readonly></td>
                        <td></td>
                        <td><input type="text" name="rate[freight]" class="wc_input_price" placeholder="350" size="6" value="'.$freight.'" required></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center;">' .
             wp_nonce_field( "shipping_servientrega_wc_ss_rates", "shipping_servientrega_wc_ss_rates" ) . '
            <br/>
            <input type="submit" value="Guardar Cambios" class="button button-primary" name="servientrega_rates_save_changes_button">
        </td>
    </tr>';

return $htmlRates;
