<?php

namespace App;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
  // Unenqueue files from parent theme
  wp_dequeue_style('sage/main.css');
  wp_dequeue_script('sage/main.js');

  // Enqueue files for child theme (which include the above as imports)
  wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
  wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

  // Set array of theme customizations for JS
  wp_localize_script( 'sage/main.js', 'simple_options', array('fonts' => get_theme_mod('theme_fonts'), 'colors' => get_theme_mod('theme_color')) );
}, 100);

/**
 * Register navigation menus
 */
register_nav_menus([
  'top_bar' => __('Topbar Navigation', 'sage')
]);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
  $config = [
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ];
  register_sidebar([
    'name'          => __('Footer-Social-Left', 'sage'),
    'id'            => 'footer-social-left'
  ] + $config);
  register_sidebar([
    'name'          => __('Footer-Social-Right', 'sage'),
    'id'            => 'footer-social-right'
  ] + $config);
  register_sidebar([
    'name'          => __('Footer-Utility-Left', 'sage'),
    'id'            => 'footer-utility-left'
  ] + $config);
  register_sidebar([
    'name'          => __('Footer-Utility-Right', 'sage'),
    'id'            => 'footer-utility-right'
  ] + $config);
});

/**
 * Salesforce integration for CF7
 */
add_filter( 'wpcf7_before_send_mail', 'unity_wpcf7_salesforce' );

function unity_wpcf7_salesforce( $contact_form ) {
  global $wpdb;
  error_log(print_r($contact_form, true));

  if ( ! isset( $contact_form->posted_data ) && class_exists( 'WPCF7_Submission' ) ) {
    $submission = WPCF7_Submission::get_instance();

    if ( $submission ) {
      $form_data = $submission->get_posted_data();
    }
  } else {
    return $contact_form;
  }

  // Set up variables
  $url = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
  $org_id = '00D36000001EFrk';

  // General form
  if ($contact_form->id == "5") {

    $body = array(
      'oid' => $org_id,
      'recordType' => '0121R0000012VRHQA2',
      'retURL' => '/',
      'first_name' => $form_data['first-name'],
      'last_name' => $form_data['last-name'],
      'email' => $form_data['email'],
      '00N1R00000TXgdb' => $form_data['your-message'],
    );

    $params = array(
      'headers' => array(
        'Content-Type' => 'application/x-www-form-urlencoded'
      ),
      'body' => $body
    );

    error_log(print_r($params, true));

    $response = wp_remote_post( $url,  $params );

    error_log(print_r($response, true));

    if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();

      $to = 'admin@unitymakes.us';
      $subject = 'Boxyard CF7 #5 -> Salesforce POST Failed';
      $body = 'Error message: '.$error_message;
      $headers = array( 'Content-Type: text/html; charset=UTF-8' );

      wp_mail( $to, $subject, $body, $headers );
    }
  }
}
