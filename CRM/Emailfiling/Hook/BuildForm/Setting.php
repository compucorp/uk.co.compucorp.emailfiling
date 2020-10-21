<?php

/**
 * BuildForm Hook Class for settings page.
 */
class CRM_Emailfiling_Hook_BuildForm_Setting {

  /**
   * Adds enable/disable processing setting to the settings form.
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

    $this->addField($form);
    $this->addTemplate();
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
   * Adds new 'Store a copy of sent emails on activity' field to the form.
   *
   * @param CRM_Core_Form $form
   *   Form Class object.
   */
  private function addField(CRM_Core_Form &$form) {
    $fieldName = 'emailfilingIsOutboundProcessingEnabled';
    $fieldLabel = ts('Store a copy of sent emails on activity');
    $form->addYesNo($fieldName, $fieldLabel);

    // Set value to a field.
    $element = &$form->_elements[$form->_elementIndex[$fieldName]];
    $element->setValue(Civi::settings()->get($fieldName) ?? $this->getDefaultValue());
  }

  /**
   * Adds the template for new field to a form.
   */
  private function addTemplate() {
    $templatePath = CRM_Emailfiling_ExtensionUtil::path() . '/templates';
    CRM_Core_Region::instance('smtp-mailer-config')->add([
      'template' => "{$templatePath}/CRM/Emailfiling/Admin/Form/Setting.tpl",
      'weight' => -1,
    ]);
  }

  /**
   * Returns the default value new field.
   *
   * @return int
   *   Default value.
   */
  private function getDefaultValue() {
    return 0;
  }

}
