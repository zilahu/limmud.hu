<?php


// keep the default separate as they are used by boxes and presets

function _fb_social_recommendations_fb_defaults() {
  return array(
    'site' => '',
    'width' => 300,
    'height' => 300,
    'header' => 1,
    'colorscheme' => 'light',
    'border_color' => '',
    'font' => 'arial',
  );
}

function _fb_social_recommendations_fb_attrs_form() {
  $form = array();

  $form['site'] = array(
    '#type' => 'textfield',
    '#title' => t('Domain'),
    '#description' => t('The domain for which to show recommendations'),
  );

  $form['width'] = array(
    '#type' => 'textfield',
    '#title' => t('Width'),
    '#description' => t('The width of the plugin in pixels'),
  );

  $form['height'] = array(
    '#type' => 'textfield',
    '#title' => t('Height'),
    '#description' => t('The height of the plugin in pixels'),
  );

  $form['header'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show the plugin header'),
    '#description' => t('Show the header in the recommendations plugin'),
  );

  $form['colorscheme'] = array(
    '#type' => 'select',
    '#title' => t('Color'),
    '#description' => t('The color scheme of the plugin'),
    '#options' => array(
      'dark' => t('dark'),
      'light' => t('light'),
    ),
  );

  $form['border_color'] = array(
    '#type' => 'textfield',
    '#title' => t('Border color'),
    '#description' => t('The border color of the plugin'),
  );

  $form['font'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#description' => t('The font of the plugin'),
    '#options' => array(
      'arial' => t('arial'),
      'lucida grande' => t('lucida grande'),
      'segoe ui' => t('segoe ui'),
      'tahoma' => t('tahoma'),
      'trebuchet ms' => t('trebuchet ms'), 'verdana',
    ),
  );

  return $form;
}

function _fb_social_recommendations_settings_form($settings) {

  $form = array();
  return $form;
}


