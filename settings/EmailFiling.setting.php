<?php

/**
 * @file
 * Email Filing Setting file.
 */

$settings = [
  'emailfilingIsOutboundProcessingEnabled' => [
    'group_name' => 'Mailing Preferences',
    'group' => 'mailing',
    'name' => 'emailfilingIsOutboundProcessingEnabled',
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'add' => '5.24',
    'title' => ts('Store a copy of sent emails on activity'),
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ts('If enabled, civicrm will attach a copy of sent email as .eml file to activity.'),
    'help_text' => NULL,
  ],
];

return $settings;
