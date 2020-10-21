<?php

/**
 * PostProcess Hook Class for settings page.
 */
class CRM_Emailfiling_Hook_PostProcess_Setting {

  /**
   * Saves value of custom setting value.
   *
   * @param string $formName
   *   Form Name.
   * @param CRM_Core_Form $form
   *   Form Class object.
   */
  public function run($formName, CRM_Core_Form &$form) {
    if (!$this->shouldRun($formName)) {
      return;
    }

    $this->saveValue($form);
  }

  /**
   * Checks if hook should run.
   *
   * @param string $formName
   *  Form Name.
   *
   * @return bool
   *   True if hook should run, false otherwise.
   */
  private function shouldRun($formName) {
    if ($formName === 'CRM_Admin_Form_Setting_Smtp') {
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
    $fieldName = 'emailfilingIsOutboundProcessingEnabled';
    $fieldValue = $values[$fieldName] ?? NULL;

    if (isset($fieldValue)) {
      Civi::settings()->set($fieldName, $fieldValue);
    }
  }

}
