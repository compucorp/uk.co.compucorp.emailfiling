<?php

/**
 * Class CRM_Emailfiling_Hook_BuildForm_AddFieldIsOriginalEmlAttached.
 *
 * Adds new field to the Mail Account Settings form.
 */
class CRM_Emailfiling_Hook_BuildForm_FieldIsOriginalEmlAttached {

  /**
   * Appends new field to the Mail Account Settings form.
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

  private function shouldRun($formName) {
    if ($formName === 'CRM_Admin_Form_MailSettings') {
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
      'checkbox',
      'is_original_eml_attached',
      ts('Store original email')
    );

    if ($form->getVar('_id')) {
      $values = $form->getVar('_values');
      $default = $this->getDefaultValue($values['value']);
      $field->setValue($default);
    }
  }

  /**
   * Adds the template for case category instance field template.
   */
  private function addTemplate() {
    $templatePath = CRM_Emailfiling_ExtensionUtil::path() . '/templates';
    CRM_Core_Region::instance('page-body')->add([
      'template' => "{$templatePath}/CRM/Admin/Form/FieldIsOriginalEmlAttached.tpl",
    ]);
  }

  /**
   * Returns the default value for the category instance field.
   *
   * @param int $categoryValue
   *   Category value.
   *
   * @return mixed|null
   *   Default value.
   */
  private function getDefaultValue($categoryValue) {
    return NULL;
  }

}
