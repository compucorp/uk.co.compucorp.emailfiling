<?php

use CRM_Emailfiling_Helper_EmailfilingConst as EmailfilingConst;
use CRM_Emailfiling_Helper_MailAccountSettings as MailAccountSettings;

/**
 * Class CRM_Emailfiling_Hook_BuildForm_AddIsOriginalEmlAttachedField.
 *
 * Adds new field to the Mail Account Settings form.
 */
class CRM_Emailfiling_Hook_BuildForm_AddIsOriginalEmlAttachedField {

  /**
   * Form field data.
   *
   * @var array
   */
  private $fieldData;

  /**
   * CRM_Emailfiling_Hook_BuildForm_FieldIsOriginalEmlAttached constructor.
   */
  public function __construct() {
    $this->fieldData = EmailfilingConst::settingInbound();
  }

  /**
   * Appends new field to the Mail Account Settings form.
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

    $this->addField($form);
    $this->addTemplate();
  }

  /**
   * Checks if this hook should run.
   *
   * @param string $formName
   *   Form name.
   * @param CRM_Core_Form $form
   *   Form Class object.
   *
   * @return bool
   *   True if hook should run, false otherwise.
   */
  private function shouldRun($formName, CRM_Core_Form $form) {
    if ($formName === 'CRM_Admin_Form_MailSettings' && $form->getVar('_id') && $form->getAction() !== CRM_Core_Action::DELETE) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Adds the Case Category Instance Form field.
   *
   * @param CRM_Core_Form $form
   *   Form Class object.
   */
  private function addField(CRM_Core_Form $form) {
    $field = $form->add(
      $this->fieldData['type'],
      $this->fieldData['name'],
      $this->fieldData['title']
    );

    // Set value to a field.
    $setting = new MailAccountSettings(NULL, $form->getVar('_id'));
    $element = &$form->_elements[$form->_elementIndex[$this->fieldData['name']]];
    $element->setValue($setting->isEnabled());
  }

  /**
   * Adds the template for new setting field.
   */
  private function addTemplate() {
    $templatePath = CRM_Emailfiling_ExtensionUtil::path() . '/templates';
    CRM_Core_Region::instance('page-body')->add([
      'template' => "{$templatePath}/CRM/Admin/Form/FieldIsOriginalEmlAttached.tpl",
    ]);
  }

}
