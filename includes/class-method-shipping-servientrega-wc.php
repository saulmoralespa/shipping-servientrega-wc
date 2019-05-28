<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 26/03/19
 * Time: 06:05 PM
 */

class WC_Shipping_Method_Shipping_Servientrega_WC extends WC_Shipping_Method
{
    const URL_QUOTING_CITY = 'https://mobile.servientrega.com/Services/RateQuote/api/Cotizador/AutoCompleteCiudadesOrigen/1/2/es/';
    const URL_QUOTING_RATES = 'https://mobile.servientrega.com/Services/RateQuote/api/Cotizador/Tarifas/';

    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);

        $this->id                 = 'shipping_servientrega_wc';
        $this->instance_id        = absint( $instance_id );
        $this->method_title       = __( 'Servientrega' );
        $this->method_description = __( 'Servientrega empresa transportadora de Colombia' );
        $this->title              = __( 'Servientrega' );
        $this->debug = $this->get_option( 'debug' );
        $this->isTest = (bool)$this->get_option( 'environment' );
        $this->address_sender     = $this->get_option('address_sender');

        $this->supports = array(
            'settings',
            'shipping-zones'
        );

        $this->init();
    }

    public function is_available($package)
    {
        return parent::is_available($package);
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
        $this->form_fields = include( dirname( __FILE__ ) . '/admin/settings.php' );
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
        $city_destination = $this->cleanString($city_destination);
        $items = $woocommerce->cart->get_cart();

        if($country !== 'CO')
            return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );

        $countries_obj = new WC_Countries();
        $country_states_array = $countries_obj->get_states();
        $name_state_destination = $country_states_array[$country][$state_destination];
        $name_state_destination = $this->cleanString($name_state_destination);
        $name_state_destination = $this->shortNameLocation($name_state_destination);
        $origin = $this->address_sender;
        $address_destine = "$city_destination - $name_state_destination";

        if ($this->debug === 'yes')
            shipping_servientrega_wc_ss()->log("origin: $origin address_destine: $address_destine");

        $cities = include dirname(__FILE__) . '/cities.php';

        $destine = array_search($address_destine, $cities);

        if(!$destine)
            return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );

        $total_valorization = 0;
        $high = 0;
        $length = 0;
        $width = 0;
        $weight = 0;

        foreach ( $items as $item => $values ) {
            $_product_id = $values['data']->get_id();
            $_product = wc_get_product( $_product_id );

            if ( $_product->get_weight() && $_product->get_length()
                && $_product->get_width() && $_product->get_height() ) {

                $custom_price_product = get_post_meta($_product_id, '_shipping_custom_price_product_smp', true);
                $total_valorization += $custom_price_product ? $custom_price_product : $_product->get_price();

                $quantity = $values['quantity'];

                $total_valorization = $total_valorization * $quantity;

                $high += $quantity > 1 ? $_product->get_height() * $quantity : $_product->get_height();
                $length += (int)$_product->get_length();
                $width += (int)$_product->get_width();
                $weight += $quantity > 1 ? $_product->get_weight() * $quantity : $_product->get_weight();

            } else {
                break;
            }
        }

        $path_url = "$origin/$destine/$length/$high/$width/$weight/$total_valorization/2/es";

        try{
            $data = $this->getDataQuote($path_url);
        }catch (\Exception $exception){
            shipping_servientrega_wc_ss()->log($exception->getMessage());
            return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', false, $package, $this );
        }

        $rate       = array(
            'id'      => $this->id,
            'label'   => $this->title,
            'cost'    => $data['Results'][0]['tarifa'],
            'package' => $package,
        );
        add_filter( 'woocommerce_cart_shipping_method_full_label', function($label) use($data) {
            $label .= "<br /><small>";
            $label .= "Estimación de entrega: {$data['Results'][0]['duracion']} horas";
            $label .= '</small>';
            return $label;
        }, 1);

        return $this->add_rate( $rate );

    }

    /**
     * @param $location
     * @return array|bool|mixed|object|string|null
     * @throws Exception
     */
    public function getDataQuote($location)
    {

        $url =  self::URL_QUOTING_RATES . $location;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !$this->isTest);

        $result = curl_exec($ch);

        if (curl_errno($ch))
            throw new  \Exception(curl_error($ch));

        curl_close ($ch);

        $result = json_decode($result, true);

        return $result;
    }

    public function cleanString($string)
    {
        $not_permitted = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitted = array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $text = str_replace($not_permitted, $permitted, $string);
        return $text;
    }

    public function shortNameLocation($name_location)
    {
        if ( 'Valle del Cauca' === $name_location )
            $name_location =  'Valle';
        return $name_location;
    }
}