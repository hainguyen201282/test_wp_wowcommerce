<?php
/*
Plugin Name:  WP Admin Form - Synchronize product data between wowcommerce websites
Plugin URI:   https://www.demo.example.com/
Description:  Synchronize product data between wowcommerce websites
Version:      1.0
Author:       Hai Nguyen
Author URI:   https://www.demo.example.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpb-sync-wowcommerce-products
Domain Path:  /languages
*/

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
  die;
}

define( 'WP_ADMIN_SYNC_PRODUCT_SETTING', 'sync-wowcommerce-product' );

define( 'ACTION_SUBMIT_WOWCOMMERCE_SETTING', 'Submit_settings');
define( 'ACTION_RESET_WOWCOMMERCE_SETTING', 'Reset_settings');
define( 'ACTION_SYNC_WOWCOMMERCE_PRODUCT', 'Sync_products');

global $requestingTime;
$requestingTime = 0;
/**
 * autoload
 */
require plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

add_action( 'admin_menu', 'sync_product_admin_menu' );

function sync_product_admin_menu() {
	add_menu_page( 'Sync Product Admin Menu', 'Sync Product', 'manage_options', WP_ADMIN_SYNC_PRODUCT_SETTING .'/'. WP_ADMIN_SYNC_PRODUCT_SETTING .'.php', 'sync_wowcommerce_product_admin_page', 'dashicons-update-alt', 3);
}

function sync_wowcommerce_product_admin_page(){
  $buttonText = 'Save settings';
  $buttonName = ACTION_SUBMIT_WOWCOMMERCE_SETTING;
  if (!empty(get_option( 'request_url' ))) {
    $buttonText = 'Reset Settings';
    $buttonName = ACTION_RESET_WOWCOMMERCE_SETTING;
  }
	?>
  <h1>Synchronize WowCommerce Products</h1>
  <h2></h2>
  <form method="post" action="options.php">
    <?php settings_fields( WP_ADMIN_SYNC_PRODUCT_SETTING ); ?>
    <?php do_settings_sections( WP_ADMIN_SYNC_PRODUCT_SETTING ); ?>
      <input type="hidden" name="registered_remote_sync_settings_mode" value="<?php echo get_option( 'registered_remote_sync_settings_mode' ) ?? 'sync'; ?>" />
      <table class="form-table">
          <tr valign="top">
            <th scope="row" colspan="2">
              Please input remote WowCommerce Website Settings:
              <?=empty(get_option( 'wowcommerce_setting_form_error' )) ? '' : '<br><font color="red">'.get_option( 'wowcommerce_setting_form_error' ).'</font>'?>
            </th>
          </tr>
          <tr valign="top">
            <th scope="row">WowCommerce Site URL:</th>
            <td>
              <input type="text" name="request_url" required value="<?php echo get_option( 'request_url' ); ?>" <?=empty(get_option( 'request_url' )) ? '' : 'readonly="true"'?>/>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">API Key:</th>
            <td>
              <input type="text" name="api_key" required value="<?php echo !empty(get_option( 'api_key' )) ? get_option( 'api_key' ) : 'ck_2bbdabf521b5ff91db6802c9d427782a6b46cf91'; ?>" <?=empty(get_option( 'api_key' )) ? '' : 'readonly="true"'?>/>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">Secret:</th>
            <td>
              <input type="password" name="api_secret" required value="<?php echo !empty(get_option( 'api_secret' )) ? get_option( 'api_secret' ) : 'cs_2c935a88e86d5a9b7021ffcbf5762abed3e059cf'; ?>" <?=empty(get_option( 'api_secret' )) ? '' : 'readonly="true"'?>/>
            </td>
          </tr>
          <?php if ( in_array(get_option( 'registered_remote_sync_settings_mode' ), array(ACTION_SUBMIT_WOWCOMMERCE_SETTING, ACTION_SYNC_WOWCOMMERCE_PRODUCT)) &&
                      get_option( 'wowcommerce_setting_form_error' ) == '') : ?>
          <tr valign="top">
            <th scope="row" colspan="2"><?php submit_button("Synchronize Products to this website", 'primary', ACTION_SYNC_WOWCOMMERCE_PRODUCT);?></th>
          </tr>
          <?php endif; ?>

        </table>
      
    <?php submit_button($buttonText, 'primary', $buttonName);?>
    <script type="text/javascript">
      jQuery(function() {
        jQuery('input[type=submit]').click(function() {
            jQuery('input[name=registered_remote_sync_settings_mode]').val(jQuery(this).attr('name'));
        });

        jQuery('form').submit(function() {
          if (jQuery('input[name=registered_remote_sync_settings_mode]')[0].value == '<?=ACTION_SYNC_WOWCOMMERCE_PRODUCT?>') {
            if (!confirm('Are you sure to synchronize, the product data may be lost?')){
              return false;
            }
          }
          return true;
        })
      });
    </script>
  </form>
	<?php
}

add_action( 'admin_init', 'update_remote_wowcommerce_settings' );

function update_remote_wowcommerce_settings() {
  global $requestingTime;
    if (get_option( 'registered_remote_sync_settings_mode' ) !== ACTION_RESET_WOWCOMMERCE_SETTING) {
      $woocommerce = new Client(
        get_option('request_url'),
        get_option('api_key'),
        get_option('api_secret'),
        [
          'version' => 'wc/v3',
          'wp_json' => true,
        ]
      );


      $requestingTime++;

      try {
        // get products
        $products = $woocommerce->get('products');

        if (get_option( 'registered_remote_sync_settings_mode' ) == ACTION_SYNC_WOWCOMMERCE_PRODUCT && $requestingTime == 1) {
          foreach ($products as $productData) {
            $productDataArr = json_decode(json_encode($productData), true);
            // get existing product for updating
            $wc_product = wc_get_product($productDataArr['id']);
            unset($productDataArr['id']);
            // Create a new product
            if (!$wc_product) {
              $wc_product = new WC_Product_Simple();
            }
            // Set the product data
            $wc_product->set_props($productDataArr);
            // Save the product
            $wc_product->save();
          }
        }
        update_option('wowcommerce_setting_form_error', '');
      } catch (HttpClientException $e) {
        update_option('wowcommerce_setting_form_error', 'Invalid remote Wowcommerce setting, please check again');
      } finally {
        update_option('registered_remote_sync_settings_mode', ACTION_SUBMIT_WOWCOMMERCE_SETTING);
      }
    }
    else {
      update_option('registered_remote_sync_settings_mode', ACTION_RESET_WOWCOMMERCE_SETTING);
      update_option('wowcommerce_setting_form_error', '');
      update_option('request_url', '');
      update_option('api_key', '');
      update_option('api_secret', '');
    }

    if ($requestingTime > 1) {
      $requestingTime = 1;
    }
    register_setting( WP_ADMIN_SYNC_PRODUCT_SETTING, 'registered_remote_sync_settings_mode' );
    register_setting( WP_ADMIN_SYNC_PRODUCT_SETTING, 'wowcommerce_setting_form_error' );
    register_setting( WP_ADMIN_SYNC_PRODUCT_SETTING, 'request_url' );
    register_setting( WP_ADMIN_SYNC_PRODUCT_SETTING, 'api_key' );
    register_setting( WP_ADMIN_SYNC_PRODUCT_SETTING, 'api_secret' );
}