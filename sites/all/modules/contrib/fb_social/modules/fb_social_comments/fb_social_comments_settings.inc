<?php

function _fb_social_comments_fb_defaults() {
  return array(
    'numposts' => 10,
    'width' => 550,
    'colorscheme' => 'light',
    'colorscheme' => 0,
  	'migrated' => 0
  );
}

function _fb_social_comments_fb_attrs_form() {
  $form = array();


  $form['numposts'] = array(
    '#type' => 'textfield',
    '#title' => t('Number of posts'),
    '#description' => t('The maximum number of posts to display. You can set numposts to 0 to not display any comments. (Default value is 10.)'),
  );

  $form['width'] = array(
    '#type' => 'textfield',
    '#title' => t('Width'),
    '#description' => t('The width of the Comments Box in pixels. (Default value is 550px.)'),
  );
  
  $form['colorscheme'] = array(
    '#type' => 'select',
    '#title' => t('Color Scheme'),
    '#description' => t('The color scheme of the plugin'),
    '#options' => array(
      'dark' => t('dark'),
      'light' => t('light'),
    ),
  );
  
  
  $form['migrated'] = array(
    '#type' => 'checkbox',
    '#title' => t('Migrate to the new version of facebook comments'),
    '#description' => t('If you are using the original version of the Facebook Comments box, check and the comments box will automatically switch to the new version'),
  );
  
  return $form;
}

function _fb_social_comments_settings_form($settings) {
  $form = array();

  $form['node_types'] = array(
    '#type' => 'fieldset',
    '#title' => t('Content types'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['node_types']['types'] = array(
    '#type' => 'checkboxes',
    '#description' => t('Select the content types that will use fb_comments. NOTE: Drupal comment settings will be ignored for the selected content types'),
    '#default_value' => isset($settings['node_types']['fb_social_comments_node_types']) ? $settings['node_types']['fb_social_comments_node_types'] : array(),
    '#options' => node_type_get_names(),
  );

  return $form;
}


