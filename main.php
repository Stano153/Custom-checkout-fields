<?php
/**
* Plugin Name: Custom checkout fields 
* Plugin URI: https://mirible.com/
* Description: Adding custom fields to checkout page
* Version: 1.0
* Author: Stanislav Ondrej
* Author URI: https://mirible.com/
**/




add_action( 'woocommerce_after_checkout_billing_form', 'mirible_add_custom_checkout_field' );
  
function mirible_add_custom_checkout_field( $checkout ) { 
   $current_user = wp_get_current_user();
   $saved_ico_field = $current_user->ico_field;
   woocommerce_form_field( 'ico_field', array(        
      'type' => 'text',        
      'class' => array( 'form-row-wide' ),        
      'label' => 'IČO',        
      'required' => false,
      'default' => $saved_ico_field,        
   ), $checkout->get_value( 'ico_field' ) ); 

   $saved_dic_field = $current_user->dic_field;
   woocommerce_form_field( 'dic_field', array(        
      'type' => 'text',        
      'class' => array( 'form-row-wide' ),        
      'label' => 'DIČ',        
      'required' => false,
      'default' => $saved_dic_field,        
   ), $checkout->get_value( 'dic_field' ) ); 

}

add_action( 'woocommerce_checkout_process', 'mirible_validate_new_checkout_field' );
  
function mirible_validate_new_checkout_field() {    
   if ( ! $_POST['ico_field'] ) {
      wc_add_notice( '.', 'error' );
   }

   if ( ! $_POST['dic_field'] ) {
      wc_add_notice( '.', 'error' );
   }
}

add_action( 'woocommerce_checkout_update_order_meta', 'mirible_save_new_checkout_field' );
  
function mirible_save_new_checkout_field( $order_id ) { 
    if ( $_POST['ico_field'] ) update_post_meta( $order_id, '_ico_field', esc_attr( $_POST['ico_field'] ) );
    if ( $_POST['dic_field'] ) update_post_meta( $order_id, '_dic_field', esc_attr( $_POST['dic_field'] ) );

}
 
add_action( 'woocommerce_order_details_after_order_table', 'mirible_show_new_checkout_field_thankyou' );
   
function mirible_show_new_checkout_field_thankyou( $order_id ) {    
   if ( get_post_meta( $order_id, '_ico_field', true ) ) echo '<p style="margin-bottom: 0">IČO: ' . get_post_meta( $order_id, '_ico_field', true ) . '</p>';
   if ( get_post_meta( $order_id, '_dic_field', true ) ) echo '<p style="margin-bottom: 0">DIČ: ' . get_post_meta( $order_id, '_dic_field', true ) . '</p>';
}
  
add_action( 'woocommerce_admin_order_data_after_billing_address', 'mirible_show_new_checkout_field_order' );
   
function mirible_show_new_checkout_field_order( $order ) {    
   $order_id = $order->get_id();
   if ( get_post_meta( $order_id, '_ico_field', true ) ) echo '<p style="margin-bottom: 0">IČO: ' . get_post_meta( $order_id, '_ico_field', true ) . '</p>';
   if ( get_post_meta( $order_id, '_dic_field', true ) ) echo '<p style="margin-bottom: 0">DIČ: ' . get_post_meta( $order_id, '_dic_field', true ) . '</p>';

}
 
add_action( 'woocommerce_email_after_order_table', 'mirible_show_new_checkout_field_emails', 20, 4 );
  
function mirible_show_new_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email ) {
    if ( get_post_meta( $order->get_id(), '_ico_field', true ) ) echo '<p style="margin-bottom: 0">IČO: ' . get_post_meta( $order->get_id(), '_ico_field', true ) . '</p>';
    if ( get_post_meta( $order->get_id(), '_dic_field', true ) ) echo '<p style="margin-bottom: 0">DIČ: ' . get_post_meta( $order->get_id(), '_dic_field', true ) . '</p>';

}


/**DATE AND TIME */

add_action( 'woocommerce_review_order_before_payment', 'mirible_add_custom_checkout_field_shipping' );

function mirible_add_custom_checkout_field_shipping($checkout){

    $datum_field_value = isset($_POST['datum_field']) ? sanitize_text_field($_POST['datum_field']) : '';
    woocommerce_form_field('datum_field', array(
        'type'     => 'text',
        'class'    => array('form-row-wide checkout-additional-fields'),
        'label'    => 'Želaný dátum doručenia',
        'required' => true,
        'default'  => $datum_field_value,
    ));

    $cas_field_value = isset($_POST['cas_field']) ? sanitize_text_field($_POST['cas_field']) : '';
    woocommerce_form_field('cas_field', array(
        'type'     => 'text',
        'class'    => array('form-row-wide checkout-additional-fields'),
        'label'    => 'Želaný čas doručenia',
        'required' => true,
        'default'  => $cas_field_value,
    ));
}


add_action( 'woocommerce_checkout_process', 'mirible_validate_new_checkout_field_shipping' );

function mirible_validate_new_checkout_field_shipping() {   
   if ( ! $_POST['datum_field'] ) {
      wc_add_notice( 'Zadajte prosím vami želaný dátum doručenia', 'error' );
   }

   if ( ! $_POST['cas_field'] ) {
      wc_add_notice( 'Zadajte prosím vami želaný čas doručenia', 'error' );
   }
}


add_action( 'woocommerce_checkout_update_order_meta', 'mirible_save_new_checkout_field_shipping' );
  
function mirible_save_new_checkout_field_shipping( $order_id ) { 
    if ( $_POST['datum_field'] ) update_post_meta( $order_id, '_datum_field', esc_attr( $_POST['datum_field'] ) );
    if ( $_POST['cas_field'] ) update_post_meta( $order_id, '_cas_field', esc_attr( $_POST['cas_field'] ) );

}


add_action( 'woocommerce_thankyou', 'mirible_show_new_checkout_field_thankyou_shipping' );
   
function mirible_show_new_checkout_field_thankyou_shipping( $order_id ) {    
   if ( get_post_meta( $order_id, '_datum_field', true ) ) echo '<p style="margin-bottom: 0"><strong>Želaný dátum doručenia:</strong> ' . get_post_meta( $order_id, '_datum_field', true ) . '</p>';
   if ( get_post_meta( $order_id, '_cas_field', true ) ) echo '<p style="margin-bottom: 0"><strong>Želaný čas doručenia:</strong> ' . get_post_meta( $order_id, '_cas_field', true ) . '</p>';
}
  
add_action( 'woocommerce_admin_order_data_after_billing_address', 'mirible_show_new_checkout_field_order_shipping' );
   
function mirible_show_new_checkout_field_order_shipping( $order ) {    
   $order_id = $order->get_id();
   if ( get_post_meta( $order_id, '_datum_field', true ) ) echo '<p style="margin-bottom: 0">Želaný dátum doručenia: ' . get_post_meta( $order_id, '_datum_field', true ) . '</p>';
   if ( get_post_meta( $order_id, '_cas_field', true ) ) echo '<p style="margin-bottom: 0">Želaný čas doručenia: ' . get_post_meta( $order_id, '_cas_field', true ) . '</p>';

}
 
add_action( 'woocommerce_email_customer_details', 'mirible_show_new_checkout_field_emails_shipping', 20, 4 );

function mirible_show_new_checkout_field_emails_shipping( $order, $sent_to_admin, $plain_text, $email ) {
    if ( get_post_meta( $order->get_id(), '_datum_field', true ) ) echo '<p style="margin-bottom: 0"><strong>Želaný dátum doručenia:</strong> ' . get_post_meta( $order->get_id(), '_datum_field', true ) . '</p>';
    if ( get_post_meta( $order->get_id(), '_cas_field', true ) ) echo '<p style="margin-bottom: 0"><strong>Želaný čas doručenia:</strong> ' . get_post_meta( $order->get_id(), '_cas_field', true ) . '</p>';
}