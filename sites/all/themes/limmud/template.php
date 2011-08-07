<?php
function limmud_nice_menus_menu_item_link($variables) {
  global $user;
  
  if (empty($variables['element']['#localized_options'])) {
    $variables['element']['#localized_options'] = array();
  }
  
  if ($variables['element']['#href'] == 'http://atrium.limmud.hu' && (!in_array('administrator', $user->roles) && !in_array('önkéntesek', $user->roles))) {
     return "";
  }
  
  return l($variables['element']['#title'], $variables['element']['#href'], $variables['element']['#localized_options']);
}


/**
* Implements hook_form_FORM_ID_alter().
*/
function limmud_form_user_login_block_alter(&$form) {
  // Remove the links provided by Drupal.
  unset($form['links']);

  // Set a weight for form actions so other elements can be placed
  // beneath them.
  $form['actions']['#weight'] = 5;

  // Shorter, inline request new password link.
  $form['request_password'] = array(
    '#markup' => "<p>".l(t('Request new password'), 'user/password', array('attributes' => array('title' => t('Request new password via e-mail.'))))."<br/>",
    '#weight' => 9,
  );  

  // New sign up link, with 'cuter' text.
  if (variable_get('user_register', USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL)) {
    $form['signup'] = array(
      '#markup' => l(t('Create new account'), 'user/register', array('attributes' => array('class' => 'button', 'id' => 'create-new-account', 'title' => t('Create a new user account.'))))."</p>",
      '#weight' => 10, 
    );  
  }
}