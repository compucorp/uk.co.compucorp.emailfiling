<?php

use CRM_Emailfiling_Helper_EmailfilingConst as EmailfilingConst;
use CRM_Emailfiling_Service_MailProcessor as MailProcessor;

/**
 * Class EmailFilingOutboundProcessor.
 */
class CRM_Emailfiling_Hook_alterMailParams_OutboundProcessor {

  /**
   * Setting data used for form field.
   *
   * @var array
   */
  private $fieldData;

  /**
   * CRM_Emailfiling_Hook_alterMailParams_OutboundProcessor constructor.
   */
  public function __construct() {
    $this->fieldData = EmailfilingConst::settingOutbound();
  }

  /**
   * Outbound email processor.
   *
   * Attaches original email as .eml file to activity.
   *
   * @param array $params
   *   Email data, comes from hook_civicrm_alterMailParams().
   * @param string $context
   *   Email context, comes from hook_civicrm_alterMailParams().
   *
   * @throws \CiviCRM_API3_Exception
   */
  public function run(array &$params, $context) {
    $activityId = $this->shouldRun($params);
    if (!$activityId) {
      return;
    }

    $processor = new MailProcessor($params);
    $processor->attachEmlToActivity($activityId);
  }

  /**
   * Checks if processing should run and related activity exists.
   *
   * @param array $params
   *   Email data.
   *
   * @return int
   *   Related activity id, or 0 if not found or params are not valid.
   *
   * @throws \CiviCRM_API3_Exception
   *   An exception generated by civicrm_api3().
   */
  private function shouldRun(array $params) {
    // Creator is required for activity, let's check it too.
    if (empty($params['from'])) {
      return 0;
    }
    // Ignore bulk emails.
    if (!empty($params['job_id'])) {
      return 0;
    }
    // Check if respective setting is enabled.
    if (!Civi::settings()->get($this->fieldData['name'])) {
      return 0;
    }

    return $this->getActivityIdByMailParams($params);
  }

  /**
   * Returns id of activity related to email.
   *
   * There is no straight forward way to get activity, because we don't have
   * an id, so instead we would fetch the latest activity with matching type,
   * subject, creator and suitable date.
   *
   * @param array $params
   *   Email data.
   *
   * @return int
   *   Activity id.
   *
   * @throws \CiviCRM_API3_Exception
   *   An exception generated by civicrm_api3().
   */
  private function getActivityIdByMailParams(array $params) {
    // Get the latest activity that has been created just now.
    $date = date('Y-m-d H:i:s', time() - 10);
    $result = civicrm_api3('Activity', 'get', [
      'sequential' => 1,
      'activity_type_id' => 'Email',
      'subject' => $this->removeCaseHash($params['subject']),
      'source_contact_id' => CRM_Core_Session::getLoggedInContactID(),
      'created_date' => ['>=' => $date],
      'options' => [
        'sort' => 'id DESC',
        'limit' => 1,
      ],
    ]);

    return $result['id'] ?? 0;
  }

  /**
   * Removes case hash/id from email subject.
   *
   * @param string $subject
   *   Email subject.
   *
   * @return string
   *   Email subject without case hash/id.
   */
  private function removeCaseHash($subject) {
    return preg_replace('!\s*\[\w+\s+#\w+\]\s*!i', '', $subject);
  }

}
