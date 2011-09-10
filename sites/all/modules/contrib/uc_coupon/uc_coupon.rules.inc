<?php
/**
 * @file
 * Rules integration for uc_coupon
 */

/**
 * Implements hook_rules_data_info().
 */
function uc_coupon_rules_data_info() {
  $types['uc_coupon'] = array(
    'label' => t('Ubercart discount coupon'),
    'wrap' => FALSE,
    'group' => t('Ubercart'),
  );
}

/**
 * Implements hook_rules_condition_info().
 */
function uc_coupon_rules_condition_info() {
  $conditions['uc_coupon_condition_order_has_coupon'] = array(
    'label' => t('Check if an order has a coupon applied'),
    'group' => t('Order'),
    'parameter' => array(
      'order' => array(
        'type' => 'uc_order',
        'label' => t('Order'),
        'optional' => TRUE,
        'default value' => NULL,
        'allow null' => TRUE
      ),
      'codes' => array(
        'type' => 'list<text>',
        'label' => t('Codes'),
        'description' => t('Enter coupon codes that this condition will apply to, one per line. Wildcards are allowed, e.g. COUPON* will match all codes beginning with COUPON. Leave blank to apply to any order with a coupon.'),
        'restriction' => 'input',
      ),
      'check_current' => array(
        'type' => 'boolean',
        'label' => t('Include currently applied coupon (if any).'),
        'description' => t('Check this box if this condition will apply only to orders that
    			are currently in checkout, in order to catch coupons applied before an order reaches
    			the review stage (e.g. for shipping quotes).  Leave unchecked if the condition will
    			apply to orders after checkout is complete (e.g. when payment notification is received 
    			or when order status is updated manually).'),
        'restriction' => 'input',
        'default value' => TRUE,
      ),
    ),
  );
  
  $conditions['uc_coupon_condition_is_bulk'] = array(
    'label' => t('Check if a coupon is a bulk coupon'),
    'group' => t('Coupon'),
    'parameter' => array(
      'coupon' => array(
        'type' => 'uc_coupon',
        'label' => t('Coupon'),
      ),
    ),
  );

  return $conditions;
}

/**
 * Check if an order has a coupon applied.
 */
function uc_coupon_condition_order_has_coupon($order, $codes=array(), $check_current) {
  $check_codes = array();
  // We start with current valid codes, if specified
  if ($check_current) {
    $coupons = uc_coupon_session_validate();
    foreach ($coupons as $coupon) {
      $check_codes[$coupon->code] = $coupon->code;
    }
  }
  // Now add any codes alreay in the order object (these take precedence).
  if (isset($order->data['coupons'])) {
    $check_codes = array_merge($check_codes, drupal_map_assoc(array_keys($order->data['coupons'])));
  }
  //dpm($check_codes,'here');
  if (count($check_codes) > 0) {
    $codes = array_filter($codes);
    // If no codes specified, match any coupon.
    if (count($codes)==0) {
      return TRUE;
    }

    // Check codes for exact or wildcard matches.
    foreach ($codes as $code) {
      foreach (array_keys($check_codes) as $check_code) {
        if (preg_match('/^' . str_replace('\*', '.*?', preg_quote(strtoupper(trim($code)), '/')) . '$/', $check_code)) {
          return TRUE;
        }
      }
    }
  }

  return FALSE;
}

/**
 * Check that a coupon is a bulk coupon.
 */
function uc_coupon_condition_is_bulk($coupon) {
  return $coupon->bulk;
}



