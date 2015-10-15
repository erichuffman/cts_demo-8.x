<?php

/**
 * @file
 * This is the heart of creating custom theme settings. You set all of your form options within
 * the form_system_theme_settings_alter hook. From the Drupal API:
 * "With this hook, themes can alter the theme-specific settings form in any way allowable by
 * Drupal's Form API, such as adding form elements, changing default values and removing form
 * elements. See the Form API documentation on api.drupal.org for detailed information."
 * (https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!theme.api.php/function/
 * hook_form_system_theme_settings_alter/8)
 *
 */

/**
 * Implementation of hook_form_system_theme_settings_alter()
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 *
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function cts_demo_form_system_theme_settings_alter(&$form, &$form_state) {

  // keep options for radio buttons, and drop-downs as variables in a separate file
  // so it's easier to manage when changes are needed.
  require_once(drupal_get_path('theme', 'cts_demo') . '/inc/theme_settings.options.inc');

  // Set up a fieldset for the site's "flavor"
  $form['site_flavor_settings'] = array(
    '#type'         => 'details',
    '#title'        => t('Site Flavor'),
    '#description'  => t('Select a flavor for your site.'),
    '#weight' => -1000,
    '#open' => TRUE,
  );

  // Set up a drop-down for the flavor options
  $form['site_flavor_settings']['site_flavor'] = array(
    '#type' => 'select',
    '#title' => t('Flavor:'),
    '#default_value' => theme_get_setting('site_flavor'),
    '#options' => $site_flavor_options,
    '#description'  => t('Select a flavor.'),
  );

  // Set up a fieldset for Divison/Territory selections
  $form['div_ter_settings'] = array(
    '#type'         => 'details',
    '#title'        => t('Company Division And Territory Details'),
    '#description'  => t('Select the appropriate Division and Territory for your site. This will appear to the right of the company logo.'),
    '#weight' => -999,
    '#open' => TRUE,
  );

  // Set up radio buttons for selecting the company division
  $form['div_ter_settings']['division_id']= array(
    '#type' => 'radios',
    '#title' => t('Divison:'),
    '#default_value' => theme_get_setting('division_id'),
    '#options' => $company_division_options,
  );

  // Set up radio buttons for selecting the territory
  $form['div_ter_settings']['territory_id']= array(
    '#type' => 'radios',
    '#title' => t('Territory:'),
    '#default_value' => theme_get_setting('territory_id'),
    '#options' => $territory_options,
  );

  // Set up a fieldset for Menu location
  $form['menu_location'] = array(
    '#type'         => 'details',
    '#title'        => t('Main Menu Location'),
    '#description'  => t('Select the location of the main menu. A "top" menu will appear below the logo bar, and have a horizontal list of links. A "left" menu will appear to the left of the page content, and have a vertical list of links.'),
    '#weight' => -900,
    '#open' => TRUE,
  );

  $form['menu_location']['main_menu_location']= array(
    '#type' => 'radios',
    '#title' => t('Select A Location:'),
    '#default_value' => theme_get_setting('main_menu_location'),
    '#options' => array(
      0 => t('Top'),
      1 => t('Left')
    ),
  );

  // Set up a fieldset for the Leprechaun Mascot option
  $form['mascot_option'] = array(
    '#type'         => 'details',
    '#title'        => t('Leprechaun Mascot'),
    '#description'  => t('Select whether or not a Leprechaun mascot should be included with your site.'),
    '#weight' => -800,
    '#open' => TRUE,
  );
  // Set up the checkbox to include/not include
  $form['mascot_option']['leprechaun_mascot'] = array(
    '#type'         => 'checkbox',
    '#title'        => t('Include a Leprechaun Mascot with this site.'),
    '#default_value' => theme_get_setting('leprechaun_mascot'),
    '#description'  => t('Check this option if you\'d like to include a Leprechaun Mascot.'),
  );
  // Set up the container to hold the mascot options (it is toggled via the mascot include checkbox)
  $form['mascot_option']['mascot_selection']['#type'] = 'container';
  $form['mascot_option']['mascot_selection']['#states'] = array('visible' => array('input[name="leprechaun_mascot"]' => array('checked' => TRUE)));
  $form['mascot_option']['mascot_selection']['leprechaun_mascot_style'] = array(
    '#type' => 'select',
    '#title' => t('Mascot:'),
    '#default_value' => theme_get_setting('leprechaun_mascot_style'),
    '#options' => $leprechaun_mascot_options,
    '#description'  => t('Select a Leprechaun Mascot.'),
  );

  /**
   * Setting the open/closed state for the "Toggle Display" fieldset options
   * (i.e.,  User pictures in posts, User pictures in comments, User verification status
   * in comments and Shortcut icon)
   */
  $form['theme_settings']['#open'] = FALSE;
  $form['favicon']['#open'] = TRUE;

  /**
   * If you don't want the Drupal defaults at all, uncomment the lines below
   */
  //$form['theme_settings']['#access'] = FALSE;
  //$form['favicon']['#access'] = FALSE;


  /**
   * TODO: Learn more about the Drupal 8 Form API so I can
   * validate the selections of this from!
   *
   * Below is how we would set a function to run for form validation.
   *
   * $form['#validate'][] = 'cts_demo_settings_validate';
   *
   */
}

/**
 *
 * TODO: Learn more about the Drupal 8 Form API so I can
 * validate the sections of this form!
 *
 * Below is a D7 form validation function.
 *
 * @file
 * Validatation for theme settings.
 *
 * @param $form
 * @param $form_state
 *
 * function cts_demo_settings_validate($form, &$form_state) {
 *
 *  // Check for a top menu selection along with a Surly leprechaun, and warn accordingly.
 *  if (!empty($form_state['values']['leprechaun_mascot']) && $form_state['values']['main_menu_location'] == 0 && $form_state['values']['leprechaun_mascot_style'] == 'tipsy') {
 *    drupal_set_message(t('Notice: Placing a menu above a tipsy leprechaun may cause adverse effects. You might consider keeping the menu to the left, or things might get ugly.'), 'warning');
 *   }
 *
 * }
 *
 */