<?php

/**
 * Class MailAccountSettings.
 *
 * Handles get/set routine for enable/disable inbound email processing setting.
 */
class CRM_Emailfiling_Helper_MailAccountSettings {

  /**
   * Name of the setting.
   *
   * Used to load/save data from civicrm_setting db table.
   * Unlike similar setting for outbound processing which is site global
   * (see emailfilingIsOutboundProcessingEnabled), this setting would be
   * an associative array where each item would represent enabled processing,
   * for example:
   * Array(
   *   12 => 1,
   *   4 => 1,
   * ).
   * It means that for mail accounts with id 12 and 4 the 'Store original email'
   * checkbox is ticked. Accounts with unticked checkboxes are not stored in db
   * as there is no point for that.
   *
   * Keep in mind that 'Store original email' checkbox does not represent this
   * setting directly (form field has different name), but it's value is added
   * to array and saved to db on form submit (see
   * CRM_Emailfiling_Hook_PostProcess_SaveSettingInbound).
   *
   * @var string
   */
  private $settingName = 'emailfilingIsInboundProcessingEnabled';

  /**
   * Mail account data.
   *
   * @var array
   */
  private $account;

  /**
   * MailAccountSettings constructor.
   *
   * @param array $data
   *   Data used to load mail account. May contain:
   *   - id: account id.
   *   - name: account name.
   *   - email: email address from account settings.
   *   - _force_id: set to TRUE to make id usable even if account is not found.
   *
   *   Only one of id/name/email should be set. The _force_id may be useful on
   *   mail account deletion, when it's already removed from db, but we still
   *   need to remove it's setting.
   *
   * @throws \CiviCRM_API3_Exception
   *   An exception generated by civicrm_api3().
   */
  public function __construct(array $data) {
    // Load mail account settings by id if available.
    if (!empty($data['id'])) {
      $this->loadAccountById($data['id']);
      // When mail account is being deleted it may already be removed from DB,
      // so it would not be loaded here, but we still can use it's id
      // to remove setting from db (using $this->toggle(0)).
      if (empty($this->account) && !empty($data['_force_id'])) {
        $this->account = ['id' => $data['id']];
      }
    }
    // Load mail account settings by it's name.
    elseif (!empty($data['name'])) {
      $this->loadAccountByName($data['name']);
    }
    // Load mail account settings by email.
    elseif (!empty($data['email']) && is_string($data['email'])) {
      // Convert email address to object.
      $rfc = new Mail_RFC822();
      $email = $rfc->parseAddressList($data['email']);

      if (empty($email[0])) {
        return;
      }

      // As email may have multiple 'To' addresses we will check all of them
      // until first account that matches given address is found.
      for ($i = 0; $i < count($email) && !$this->getAccountId(); $i++) {
        $this->loadAccountByEmail($email[$i]);
      }
    }
  }

  /**
   * Returns current account id.
   *
   * @return int
   *   Account id, or 0 if not found / not loaded.
   */
  private function getAccountId() {
    return $this->account['id'] ?? 0;
  }

  /**
   * Checks if 'Store original email' checkbox is ticked for current account.
   *
   * @return int
   *   1 if enabled, 0 otherwise.
   */
  public function isEnabled() {
    $setting = \Civi::settings()->get($this->settingName);
    return $setting[$this->getAccountId()] ?? 0;
  }

  /**
   * Enables/disables 'Store original email' field for current account.
   *
   * @param int $value
   *   New value for the field.
   */
  public function toggle($value) {
    // Since setting contains array of field values, we can't save new value
    // to it directly, so let's load array first.
    $setting = \Civi::settings()->get($this->settingName);
    $accountId = $this->getAccountId();

    // If we need to disable the feature - let's just remove respective value
    // from array.
    if (!$value) {
      if (isset($setting[$accountId])) {
        unset($setting[$accountId]);
      }
    }
    // Add new value to array for current mail account.
    else {
      $setting[$accountId] = $value ? 1 : 0;
    }

    // Save updated array.
    \Civi::settings()->set($this->settingName, $setting);
  }

  /**
   * Loads mail account settings by it's id.
   *
   * @param int $accountId
   *   Mail account id.
   *
   * @throws \CiviCRM_API3_Exception
   *   An exception generated by civicrm_api3().
   */
  private function loadAccountById($accountId) {
    if (empty($accountId)) {
      return;
    }

    $result = civicrm_api3('MailSettings', 'get', [
      'sequential' => 1,
      'is_default' => 0,
      'id' => $accountId,
    ]);

    $this->account = $result['values'][0] ?? NULL;
  }

  /**
   * Loads mail account settings by email.
   *
   * This function can load account by id or by email address.
   * It may be useful to get mail account id and ensure it actually exists.
   *
   * @param stdClass $email
   *   Email address that mail account has.
   *
   * @throws \CiviCRM_API3_Exception
   *   An exception generated by civicrm_api3().
   */
  private function loadAccountByEmail($email) {
    if (empty($email->mailbox)) {
      return;
    }

    // Keep in mind that normally username is the part before '@',
    // but sometimes username is the full email address.
    $result = civicrm_api3('MailSettings', 'get', [
      'sequential' => 1,
      'is_default' => 0,
      'domain' => $email->host,
      'username' => [
        'IN' => [
          $email->mailbox,
          $email->mailbox . '@' . $email->host,
        ],
      ],
    ]);

    $this->account = $result['values'][0] ?? NULL;
  }

  /**
   * Loads mail account settings by it's name.
   *
   * @param string $accountName
   *   Mail account name.
   *
   * @throws \CiviCRM_API3_Exception
   *   An exception generated by civicrm_api3().
   */
  private function loadAccountByName($accountName) {
    if (empty($accountName)) {
      return;
    }

    $result = civicrm_api3('MailSettings', 'get', [
      'sequential' => 1,
      'is_default' => 0,
      'name' => $accountName,
      'options' => [
        'limit' => 1,
        'sort' => 'id DESC',
      ],
    ]);

    $this->account = $result['values'][0] ?? NULL;
  }

}
