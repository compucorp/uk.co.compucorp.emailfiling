<table style="display: none">
  <tr class="crm-mail-settings-form-block-is_original_eml_attached"><td class="label">&nbsp;</td><td>{$form.is_original_eml_attached.html}{$form.is_original_eml_attached.label} {help id='is_original_eml_attached'}</td></tr>
</table>

{literal}
<script type="text/javascript">
(function($) {
  var $form = $('form.{/literal}{$form.formClass}{literal}');

  function showActivityFields() {
    var fields = [
      '.crm-mail-settings-form-block-is_original_eml_attached',
    ];
    $(fields.join(', '), $form).toggle($(this).val() === '0');
  }

  // Add new field to the form.
  CRM.$('.crm-mail-settings-form-block-is_original_eml_attached').insertBefore(CRM.$('.crm-mail-settings-form-block-activity_status'));
  // Show/hide field when 'Used For?' selected value is changed.
  $('select[name="is_default"]').each(showActivityFields).change(showActivityFields);
})(CRM.$);
</script>
{/literal}
