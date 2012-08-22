<?php

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

function limmud_preprocess_node(&$variables) {
  $node = $variables['node'];

  if ($node->type == 'konferencia_program' && $variables['view_mode'] == 'full') {
    // field_presentation_location
    // field_presentation_tags
    // field_presentation_types
    $node_year = substr($variables['field_presentation_time'][0]['value'], 0, 4);
    
    foreach ($variables['content']['field_presentation_location'] as $key => $value) {
      if (is_numeric($key)) {
        $variables['content']['field_presentation_location'][$key] = str_replace('taxonomy/term', 'konfesztival/'.$node_year, $variables['content']['field_presentation_location'][$key]);
      }
    }
    foreach ($variables['content']['field_presentation_tags'] as $key => $value) {
      if (is_numeric($key)) {
        $variables['content']['field_presentation_tags'][$key] = str_replace('taxonomy/term', 'konfesztival/'.$node_year, $variables['content']['field_presentation_tags'][$key]);
      }
    }
    foreach ($variables['content']['field_presentation_types'] as $key => $value) {
      if (is_numeric($key)) {
        $variables['content']['field_presentation_types'][$key] = str_replace('taxonomy/term', 'konfesztival/'.$node_year, $variables['content']['field_presentation_types'][$key]);
      }
    }
    
    // kpr($variables);
  }
  
}