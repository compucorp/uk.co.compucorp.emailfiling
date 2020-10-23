<?php

namespace Civi\Emailfiling;

/**
 * Class EmailfilingConst
 *
 * Stores some constants for this extension.
 * Since '.setting.php' file is not working here to add a new setting
 * to civicrm, we would use this class as a storage for some constant data.
 */
class EmailfilingConst {

  /**
   * Returns enable/disable outbound email processing setting data.
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

}
