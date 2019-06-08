<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 26/03/19
 * Time: 06:05 PM
 */

class WC_Shipping_Method_Shipping_Servientrega_WC extends WC_Shipping_Method
{

    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);

        $this->id = 'shipping_servientrega_wc';
        $this->instance_id = absint( $instance_id );
        $this->method_title = __( 'Servientrega' );
        $this->method_description = __( 'Servientrega empresa transportadora de Colombia' );
        $this->title = __( 'Servientrega' );

        $wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');

        $this->debug = $this->get_option( 'debug' );
        $this->isTest = isset($wc_main_settings['servientrega_production']) ? $wc_main_settings['servientrega_production'] : false;
        $this->user = isset($wc_main_settings['servientrega_user']) ? $wc_main_settings['servientrega_user'] : '';
        $this->password = isset($wc_main_settings['servientrega_password']) ? $wc_main_settings['servientrega_password'] : '';
        $this->billing_code = isset($wc_main_settings['servientrega_billing_code']) ? $wc_main_settings['servientrega_billing_code'] : '';
        $this->way_pay = isset($wc_main_settings['servientrega_agreement_pay']) ? $wc_main_settings['servientrega_agreement_pay'] : '';
        $this->address_sender = isset($wc_main_settings['servientrega_address_sender']) ? $wc_main_settings['servientrega_address_sender'] : '';
        $this->rates_servientrega = isset($wc_main_settings['rate']) ? $wc_main_settings['rate'] : [];

        $this->supports = array(
            'settings',
            'shipping-zones'
        );

        $this->init();
    }

    public function is_available($package)
    {

        $db = Shipping_Servientrega_WC::getDataShipping('2');

        return parent::is_available($package) &&
            $this->user &&
            $this->password &&
            $this->billing_code &&
            $this->way_pay &&
            $this->address_sender &&
            !empty($this->rates_servientrega) &&
            !empty($db);
    }

    /**
     * Init the class settings
     */
    public function init()
    {
        // Load the settings API.
        $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings.
        $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
        // Save settings in admin if you have any defined.
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function init_form_fields()
    {
        if(isset($_GET['page']) && $_GET['page'] === 'wc-settings')
            $this->form_fields = include( dirname( __FILE__ ) . '/admin/settings.php' );
    }

    public function generate_servientrega_tab_box_html()
    {
        include( dirname( __FILE__ ) . '/admin/tabs.php' );
    }

    public function servientrega_shipping_page_tabs($current = 'general')
    {
        $activation_check = get_option('dhl_activation_status');
        if(!empty($activation_check) && $activation_check === 'active')
        {
            $acivated_tab_html =  "<small style='color:green;font-size:xx-small;'>(Activated)</small>";

        }
        else
        {
            $acivated_tab_html =  "<small style='color:red;font-size:xx-small;'>(Activate)</small>";
        }
        $tabs = array(
            'general' => __("General"),
            'rates' => __("Tiempo de entrega, Liquidación y trayectos"),
            'packing' => __("Matriz Mercancia Premier - Terrestre"),
            //'licence' => __("License ".$acivated_tab_html, 'wf-shipping-dhl')
        );
        $html = '<h2 class="nav-tab-wrapper">';
        foreach ($tabs as $tab => $name) {
            $class = ($tab == $current) ? 'nav-tab-active' : '';
            $style = ($tab == $current) ? 'border-bottom: 1px solid transparent !important;' : '';
            $html .= '<a style="text-decoration:none !important;' . $style . '" class="nav-tab ' . $class . '" href="?page=wc-settings&tab=shipping&section=shipping_servientrega_wc&subtab=' . $tab . '">' . $name . '</a>';
        }
        $html .= '</h2>';
        return $html;
    }

    public function admin_options()
    {
        ?>
        <h3><?php echo $this->title; ?></h3>
        <p><?php echo $this->method_description; ?></p>
        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
        </table>
        <?php
    }

    public function calculate_shipping($package = array())
    {

        global $woocommerce;

        $country = $package['destination']['country'];
        $state_destination = $package['destination']['state'];
        $city_destination  = $package['destination']['city'];
        $city_destination = Shipping_Servientrega_WC::clean_string($city_destination);
        $items = $woocommerce->cart->get_cart();

        if($country !== 'CO')
            return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );

        $name_state_destination = Shipping_Servientrega_WC::name_destination($country, $state_destination);

        $origin = $this->address_sender;
        $address_destine = "$city_destination - $name_state_destination";

        if ($this->debug === 'yes')
            shipping_servientrega_wc_ss()->log("origin: $origin address_destine: $address_destine");

        $cities = include dirname(__FILE__) . '/cities.php';

        $destine = array_search($address_destine, $cities);

        if(!$destine)
            return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );

        $matrix_data = Shipping_Servientrega_WC::getDataShipping($destine);

        if (empty($matrix_data))
            return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );

        $data_products = Shipping_Servientrega_WC::dimensions_weight($items);

        $physical_restriction = json_decode($matrix_data['restriccion_fisica'], true);

        if(!empty($physical_restriction))
            if(!$this->check_restriction($data_products, $physical_restriction)){
                shipping_servientrega_wc_ss()->log("Error restricción fisica:");
                shipping_servientrega_wc_ss()->log($data_products);
                return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );
            }

        $rates = $this->rates_servientrega;
        $weight = $rates['weight'];
        $journey = $matrix_data['tipo_trayecto'];

        $total_weight_products = $data_products['weight'];

        $key_data = [];

        foreach ($weight as $key => $value){

            $key_data = [$key];

            $value = (int)$value;

            if(($value === $total_weight_products) || ($value > $total_weight_products)){
                break;
            }elseif ($total_weight_products > $value) {
                $key_data = [$key, $value];
            }
        }

        $rate_key = $key_data[0];

        $journeyCost = (int)$rates[$journey][$rate_key];

        if (count($key_data) > 1){
            $weight = $weight[$rate_key];

            $remaining = $total_weight_products - $weight;

            //additionals kilos

            $additionalCost = (int)$rates['additional'][$journey];

            $additionalCost = $additionalCost * $remaining;

            $journeyCost += $additionalCost;

        }

        $rate       = array(
            'id'      => $this->id,
            'label'   => $this->title,
            'cost'    => $journeyCost,
            'package' => $package,
        );

        $delivery_commercial = (int)$matrix_data['tiempo_entrega_comercial'];
        $delivery_days = $delivery_commercial > 1 ? "$delivery_commercial días" : "$delivery_commercial día";

        add_filter( 'woocommerce_cart_shipping_method_full_label', function($label) use($delivery_days) {

            $label .= "<br /><small>";
            $label .= "Estimación de entrega: $delivery_days";
            $label .= '</small>';
            return $label;
        }, 1);

        return $this->add_rate( $rate );

    }

    public function check_restriction($data_products, $physical_restriction)
    {
       if ($data_products['length'] > $physical_restriction['largo'])
           return false;
       if ($data_products['high'] > $physical_restriction['alto'])
           return false;
       if ($data_products['width'] > $physical_restriction['ancho'])
           return false;
       if ($data_products['weight'] > $physical_restriction['peso'])
           return false;

       return true;
    }
}