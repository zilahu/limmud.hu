<?php
/**
 * @file
 * 
 * Ubercart Discount Coupon module api/hooks.
 * Version 7.x-2.x
 */

/**
 * hook_uc_coupon_presave().
 * 
 * Allow modules to alter a coupon object just before it is saved.
 * 
 * @param $coupon
 * 		The coupon object that is about to be saved.
 * @param $edit
 * 		The coupon add/edit form values that were submitted.
 */
function hook_uc_coupon_presave(&$coupon, $edit) {
  // Add a category to this coupon
  if (isset($edit['category'])) {
    $coupon->data['category'] = $edit['category'];
  }
}

/**
 * hook_uc_coupon_save().
 * 
 * Invoked after a coupon is saved to the database.
 * 
 * @param $coupon
 * 		The coupon that was just saved.
 */
function hook_uc_coupon_save($coupon) {
  // Alert the user that the coupon needs to be ativated.
  if (!$coupon->status) {
    drupal_set_message(t('The coupon you just saved must be activated before it can be used.'));
  }
}

/**
 * hook_uc_coupon_delete().
 * 
 * Invoked just before a coupon is going to be deleted.  Allows modules to clean up any
 * additinal coupon data in their tables.
 * 
 * @param $coupon
 * 		The coupon object which is about to be deleted.
 */
function hook_uc_coupon_delete($coupon) {
  // Delete extra usage information 
  db_delete('extra_coupon_usage')
    ->condition('cid', $coupon->cid)
    ->execute();
}

/**
 * hook_uc_coupon_alter().
 * 
 * Allow modules to alter a coupon after it is loaded.
 * 
 * @param $coupon
 * 		The coupon object that was just laoded.
 */
function hook_uc_coupon_alter(&$coupon) {
  // Allow certain users to get double the discount.
  if (user_access('get double discount')) {
    $coupon->value *= 2;
  }
}


/**
 * hook_uc_coupon_usage_alter().
 * 
 * Allow modules to alter the usage count for a coupon.
 * 
 * @param $usage
 * 		An associative array consiting of the following keys:
 * 		- 'user' => The number of times this coupon has been used by the specified user.
 * 		- 'codes' => An associative array listing the total number of uses for each code (for all users).
 * @param $cid
 * 		The coupon-id of the coupon in question.
 * @param $uid
 * 		The user-id whose usage is to be checked.
 */
function hook_uc_coupon_usage_alter(&$usage, $cid, $uid) {
  // See if this coupon has been used in our table.
  $rows = db_query('SELECT code, uses FROM {extra_coupon_usage} WHERE cid = :cid', array(':cid' => $cid));
  foreach ($rows as $row) {
    if (isset($usage[$row->code])) {
      $usage[$row->code] += $row->uses;
    }
    else {
      $usage[$row->code] = $row->uses;
    }
  }
}

/**
 * hook_uc_coupon_actions().
 * 
 * Allows modules to add to the list of actions available when coupons are listed in a table.
 * 
 * @param $coupon
 * 		The coupon being displayed.
 * @return 
 * 		An associative array describing the actions available. Must contain the followoing keys:
 * 		- 'url': The url where the action is processed.
 * 		- 'icon': The icon to display for this action.
 * 		- 'title': The text to display as a title for the action (usually as hover text over the icon).
 */
function hook_uc_coupon_actions($coupon) {
  $actions = array();
  // Provide a "mark coupon as used" action.

  if (user_access('mark coupon as used')) {
    $actions[] = array(
      'url' => 'admin/store/coupons/mark-as-used/' . $coupon->cid,
      'icon' => drupal_get_path('module', 'mymodule') . 'mark_as_used.gif',
      'title' => t('Mark coupon: @name as used', array('@name' => $coupon->name)),
    );
  };
  return $actions;
}

/**
 * hook_uc_coupon_revalidate().
 * 
 * Invoked whenever the coupons added to the current session are about to be validated. 
 * 
 * Modules implementing this hook may add or remove coupon codes from the session via calls to
 * uc_coupon_session_add() or uc_coupon_session_clear().
 * 
 * @param $products
 * 		The array of products against which the coupon will be validated - usually the current cart contents.
 */
function hook_uc_coupon_revalidate($products) {
  // Add a code if there are both a widget and a doohickey in the cart.
  $gotwidget = FALSE;
  $gotdoohickey = FALSE;
  foreach ($products as $product) {
    $node = node_load($product->nid);
    if ($node) {
      if ($node->type == 'widget') {
        $gotwidget = TRUE;
      }
      elseif ($node->type == 'doohickey') {
        $gotdoohickey = TRUE;
      }
      if ($gotwidget && $gotdoohickey) {
        uc_coupon_session_add('JACKPOT', 'auto');
        return;
      }
    } 
  }
}

/**
 * hook_uc_coupon_apply().
 * 
 * Invoked whenever a valid coupon is applied to an order.  May be invoked than once for the same 
 * coupon if it becomes invalid due to change in cart contents.
 * 
 * Note that you should not do anything in your hoook implementation which causes the
 * current cart contents to be rebuilt.  This includes calls to uc_cart_add_item() without
 * explicitly setting the $rebuild argument to false.  And, since coupons may be submitted
 * on the checkout page after the cart contents are frozen, updates to the cart contents
 * may fail under some circumstances anyway.
 * 
 * @param $coupon
 * 		The fully validated coupon which was applied.
 */
function hook_uc_coupon_apply($coupon) {
  // Grant a role to the active user when a particular coupon is applied.
  // This could be used in conjunction with a node access module to expose products
  // only when certain coupon codes are entered.
  global $user;
  if ($coupon->cid == 99 && $user->uid != 0) {
    $roles = $user->roles + array(99 => 'My Special Role');
    user_save($user, array('roles' => $roles));
  }
}

/**
 * hook_uc_coupon_remove().
 * 
 * Invoked whenever a previously valid coupon is removed.
 * 
 * Note that you should not do anything in your hoook implementation which causes the
 * current cart contents to be rebuilt.  This includes calls to uc_cart_add_item() without
 * explicitly setting the $rebuild argument to false.  And, since coupons may be submitted
 * on the checkout page after the cart contents are frozen, updates to the cart contents
 * may fail under some circumstances anyway.
 * 
 * @param $coupon
 * 		The coupon which was removed.
 */
function hook_uc_coupon_remove($coupon) {
  // Revoke a role for the active user when a particular coupon is removed.
  global $user;
  if ($coupon->cid == 99 && $user->uid != 0) {
    $roles = $user->roles;
    $key = array_search('My Special Role', $roles);
    if ($key !== FALSE) {
      unset($roles[$key]);
      user_save($user, array('roles' => $roles));
    }
  }
}


 