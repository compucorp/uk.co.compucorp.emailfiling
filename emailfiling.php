<?php

require_once 'emailfiling.civix.php';
// phpcs:disable
use CRM_Emailfiling_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function emailfiling_civicrm_config(&$config) {
  _emailfiling_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function emailfiling_civicrm_xmlMenu(&$files) {
  _emailfiling_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function emailfiling_civicrm_install() {
  _emailfiling_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function emailfiling_civicrm_postInstall() {
  _emailfiling_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function emailfiling_civicrm_uninstall() {
  _emailfiling_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function emailfiling_civicrm_enable() {
  _emailfiling_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function emailfiling_civicrm_disable() {
  _emailfiling_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function emailfiling_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _emailfiling_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function emailfiling_civicrm_managed(&$entities) {
  _emailfiling_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function emailfiling_civicrm_caseTypes(&$caseTypes) {
  _emailfiling_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function emailfiling_civicrm_angularModules(&$angularModules) {
  _emailfiling_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function emailfiling_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _emailfiling_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function emailfiling_civicrm_entityTypes(&$entityTypes) {
  _emailfiling_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function emailfiling_civicrm_themes(&$themes) {
  _emailfiling_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_alterMailParams().
 */
function emailfiling_civicrm_alterMailParams(&$params, $context) {
  $hooks = [
    new CRM_Emailfiling_Hook_alterMailParams_OutboundProcessor(),
  ];

  foreach ($hooks as &$hook) {
    $hook->run($params, $context);
  }
}

function emailfiling_civicrm_emailProcessor($type, &$params, $mail, &$result, $action) {
  $hooks = [
    new CRM_Emailfiling_Hook_emailProcessor_InboundProcessor(),
  ];

  foreach ($hooks as &$hook) {
    $hook->run($type, $params, $mail, $result, $action);
  }
}


/**
 * Implements hook_civicrm_buildForm().
 */
function emailfiling_civicrm_buildForm($formName, &$form) {
  $hooks = [
    new CRM_Emailfiling_Hook_BuildForm_AddSettingOutbound(),
    new CRM_Emailfiling_Hook_BuildForm_FieldIsOriginalEmlAttached(),
    new CRM_Emailfiling_Hook_BuildForm_SortActivityAttachments(),
  ];

  foreach ($hooks as $hook) {
    $hook->run($formName, $form);
  }
}

function emailfiling_civicrm_postProcess($formName, $form) {
  $hooks = [
    new CRM_Emailfiling_Hook_PostProcess_SaveSettingOutbound(),
  ];

  foreach ($hooks as $hook) {
    $hook->run($formName, $form);
  }
}
