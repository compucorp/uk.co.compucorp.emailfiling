<?php

/**
 * Class EmailfilingConst.
 *
 * Stores some constants for this extension.
 * Since '.setting.php' file is not working here to add a new setting
 * to civicrm, we would use this class as a storage for some field data.
 */
class CRM_Emailfiling_Helper_EmailfilingConst {

  /**
   * Returns enable/disable outbound email processing setting data.
   *
   * This is a site global setting, which represents a field on civicrm
   * settings page.
   *
   * @param string|null $name
   *   (Optional) Setting item name. If not specified then whole data array
   *   will be returned.
   *
   * @return array|mixed
   *   Field data array, or single array item if $name is specified.
   */
  public static function settingOutbound($name = NULL) {
    $const = [
      'name' => 'emailfilingIsOutboundProcessingEnabled',
      'title' => ts('Store a copy of sent emails on activity'),
      'description' => ts('If enabled, civicrm will attach a copy of sent email as .eml file to activity.'),
      'default' => 0,
      'type' => 'Boolean',
      'quick_form_type' => 'YesNo',
    ];

    return $name ? ($const[$name] ?? NULL) : $const;
  }

  /**
   * Returns enable/disable inbound email processing field data.
   *
   * This not a site global setting, but Mail Account Settings form field,
   * it's value is added to a setting (which is array) on form submit (see:
   * CRM_Emailfiling_Hook_PostProcess_SaveSettingInbound
   * CRM_Emailfiling_Helper_MailAccountSettings).
   *
   * @param string|null $name
   *   (Optional) Field item name. If not specified then whole data array
   *   will be returned.
   *
   * @return array|mixed
   *   Field data array, or single array item if $name is specified.
   */
  public static function settingInbound($name = NULL) {
    $const = [
      'name' => 'is_original_eml_attached',
      'title' => ts('Store original email'),
      'default' => 0,
      'type' => 'checkbox',
    ];

    return $name ? ($const[$name] ?? NULL) : $const;
  }

}
