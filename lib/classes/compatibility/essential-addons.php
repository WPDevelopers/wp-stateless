<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Plugin URI: https://wpdeveloper.com/
 *
 * Compatibility Description: Enables support for Essential Addons for Elementor
 *
 */

namespace wpCloud\StatelessMedia {

  if( !class_exists( 'wpCloud\StatelessMedia\EAEL' ) ) {

    class EAEL extends ICompatibility {
      protected $id = 'eael';
      protected $title = 'Essential Addons for Elementor';
      protected $constant = 'WP_STATELESS_COMPATIBILITY_EAEL';
      protected $description = 'Enables support for Essential Addons for Elementor.';
      protected $plugin_file = [ 'essential-addons-for-elementor-lite/essential_adons_elementor.php' ];
      protected $sm_mode_not_supported = [ 'stateless' ];

      public function module_init( $sm ) {
        add_filter( 'eael_css_asset_url', array($this, 'eael_frontend'), 10, 2 );
        add_filter( 'eael_js_asset_url',  array($this, 'eael_frontend'), 10, 2 );
        add_action( 'eael_generate_assets', array($this, 'eael_frontend'), 10, 2 );
        add_action( 'eael_remove_assets', array($this, 'eael_remove_assets'), 10, 2 );
      }

      /**
       *
       *
       */
      public function eael_frontend( $url, $uid ) {
        $forced = false;
        $wp_uploads_dir = wp_get_upload_dir();
        if(current_action() == 'eael_generate_assets'){
          $forced = true;
        }

        $name = apply_filters('wp_stateless_file_name', $url, 0);
        $absolutePath = $wp_uploads_dir['basedir'] . '/' . $name;
        do_action('sm:sync::syncFile', $name, $absolutePath, $forced);

        if (!in_array(ud_get_stateless_media()->get('sm.mode'), ['disabled', 'backup'])) {
          $url = ud_get_stateless_media()->get_gs_host() . '/' . $name;
        }
        return $url;
      }

      /**
       *
       *
       */
      public function eael_remove_assets( $uid, $paths ) {
        foreach ($paths as $path) {
          $name = apply_filters('wp_stateless_file_name', $path, 0);
          do_action('sm:sync::deleteFile', $name);
        }
      }


    }

  }

}
