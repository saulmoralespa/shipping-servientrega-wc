=== Shipping Servientrega Woocommerce ===
Contributors: saulmorales
Donate link: https://shop.saulmoralespa.com/producto/plugin-shipping-servientrega-woocommerce/
Tags: commerce, e-commerce, commerce, wordpress ecommerce, store, sales, sell, shop, shopping, cart, checkout, configurable, Colombia, servientrega
Requires at least: 5.0
Tested up to: 5.2
Requires PHP: 7.1
Stable tag: 3.0.9
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

servientrega empresa transportadora de Colombia

== Description ==

Integración de Servientrega como método de envío para woocommerce podrá usar los servicios: cotización, generacion de guias

== Installation ==

1. Descargar el plugin
2. Ingrese a su administrador de wordpress
3. Ingrese al menu plugins / añadir nuevo
4. Busque por el nombre **Shipping Servientrega Woocommerce**
5. Instale y active el plugin y siga el proceso de configuración.
6. Establezca los detalles del envío del producto, ver captura de pantalla.


[youtube https://www.youtube.com/watch?v=l04z0McvRqs]


== Frequently Asked Questions ==

= ¿ Como tener el servcio de Servientrega ? =

Debe solicitar el servicio desde el portal de Servientrega [ver más al detalle](https://www.servientrega.com/wps/portal/Colombia/personas/soluciones/mercancias)

= ¿ Funciona para enviós internacionales ? =

Actualmente solamente Colombia, no se descarta que en el futuro con la demanda se implemente

= SOAP-ERROR: Parsing WSDL: Couldn’t load =

Si tiene el siguiente error al validar las credenciales o guardar cambios:
SOAP-ERROR: Parsing WSDL: Couldn’t load from ‘http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl’ :
failed to load external entity “http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl”
Contacte con su proveedor de alojamiento web y solicite que abran el puerto 8081 y
aclare que va realizar solicitudes a esta url http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl

= ¿ Algo más que no me hayas dicho ? =

La versión actual esta limitada y requiere uso de licencia, [versión completa](https://shop.saulmoralespa.com/producto/plugin-shipping-servientrega-woocommerce/)

== Screenshots ==

1. Configuración general screenshot-1.png
2. Configuración liquidación y costos de trayectos screenshot-2.png
3. Subir archivo excel de red operativa screenshot-3.png
4. Añadir método de envío Servientrega en zonas de envíos screenshot-4.png
5. Configurar producto con dimensiones, peros y opcional valor declarador del producto screenshot-5.png
6. Cotización costo del envío en funcion screenshot-6.png

== Changelog ==

= 1.0.1 =
* Initial stable release
= 1.0.2 =
* Added generate guide
= 1.0.3 =
* Fixed SSL tracing
= 2.0.0 =
* Updated to upload information shipping costs
= 2.0.1 =
* Fixed SSL tracing
= 2.0.2 =
* Updated verification upload excel
= 2.0.3 =
* Added documentation
= 3.0.0 =
* Updated tags version
= 3.0.1 =
* Added permitted add tabs
= 3.0.2 =
* Added filter servientrega_shipping_calculate_cost
= 3.0.3 =
* Fixed round weight and length
= 3.0.4 =
* Fixed generate, show errors
= 3.0.5 =
* Fixed static method generate_guide
= 3.0.6 =
* Fixed check error generate guide
= 3.0.7 =
* Added generate guide shipping free
= 3.0.8 =
* Added product type
= 3.0.9 =
* Fixed total valorization shipping
= 3.0.10 =
* Fixed when is minuos valorization shipping

== Additional Info ==
**Contribute** [repository on github](https://github.com/saulmoralespa/shipping-servientrega-wc)

== Credits ==
*  [Saul Morales Pacheco](http://saulmoralespa) [@saulmoralespa](http://twitter.com/saulmoralespa)