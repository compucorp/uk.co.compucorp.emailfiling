<?php

/**
 * Class CRM_Emailfiling_Hook_BuildForm_SortActivityAttachments.
 *
 * Moves .eml (original email) attachments to a separate row (field) on
 * view activity card.
 */
class CRM_Emailfiling_Hook_BuildForm_SortActivityAttachments {

  /**
   * Adds template to a page.
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

    $this->addTemplate();
  }

  /**
   * Checks if this hook should run.
   *
   * @param string $formName
   *   Form name.
   *
   * @return bool
   *   True if hook should run, false otherwise.
   */
  private function shouldRun($formName) {
    $allowForms = [
      'CRM_Activity_Form_Activity',
      'CRM_Activity_Form_ActivityView',
    ];

    if (in_array($formName, $allowForms)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Adds the template with js code to sort attachments.
   */
  private function addTemplate() {
    $templatePath = CRM_Emailfiling_ExtensionUtil::path() . '/templates';
    CRM_Core_Region::instance('page-body')->add([
      'template' => "{$templatePath}/CRM/Emailfiling/Form/Activity/SortActivityAttachments.tpl",
    ]);
  }

}
