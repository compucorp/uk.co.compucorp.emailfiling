<?php

use CRM_Emailfiling_Helper_EmailfilingConst as EmailfilingConst;
use CRM_Emailfiling_Helper_MailAccountSettings as MailAccountSettings;

/**
 * PostProcess Hook Class for settings page.
 */
class CRM_Emailfiling_Hook_PostProcess_SaveSettingInbound {

  /**
   * Setting data used for form field.
   *
   * @var array
   */
  private $fieldData;

  /**
   * CRM_Emailfiling_Hook_PostProcess_SaveSettingInbound constructor.
   */
  public function __construct() {
    $this->fieldData = EmailfilingConst::settingInbound();
  }

  /**
   * Saves value of custom setting value.
   *
   * @param string $formName
   *   Form Name.
   * @param CRM_Core_Form $form
   *   Form Class object.
   */
  public function run($formName, CRM_Core_Form &$form) {
    if (!$this->shouldRun($formName, $form)) {
      return;
    }

    $this->saveValue($form);
  }

  /**
   * Checks if hook should run.
   *
   * @param string $formName
   *  Form Name.
   * @param CRM_Core_Form $form
   *   Form Class object.
   *
   * @return bool
   *   True if hook should run, false otherwise.
   */
  private function shouldRun($formName, CRM_Core_Form $form) {
    if ($formName === 'CRM_Admin_Form_MailSettings' && $form->getVar('_id')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Saves value of new 'Store a copy of sent emails on activity' field.
   *
   * @param CRM_Core_Form $form
   *   Form Class object.
   */
  private function saveValue(CRM_Core_Form $form) {
    $values = $form->getVar('_submitValues');
    $fieldName = $this->fieldData['name'];
    $fieldValue = $values[$fieldName] ?? 0;

    $setting = new MailAccountSettings(NULL, $form->getVar('_id'));
    $setting->toggle($fieldValue);
  }

}
