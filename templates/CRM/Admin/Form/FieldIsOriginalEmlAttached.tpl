<table class="hidden">
  <tr class="crm-mail-settings-form-block-is_original_eml_attached">
    <td class="label">&nbsp;</td>
    <td>{$form.is_original_eml_attached.html}{$form.is_original_eml_attached.label} {help id='is_original_eml_attached'}</td>
  </tr>
</table>

{literal}
<script type="text/javascript">
(function($) {
  var $form = $('form.{/literal}{$form.formClass}{literal}');

  // Show/hide field depending on the parent field value.
  function showActivityFields() {
    var fields = [
      '.crm-mail-settings-form-block-is_original_eml_attached',
    ];
    $(fields.join(', '), $form).toggle($(this).val() === '0');
  }

  (function init() {
    // Add new field to the form.
    $('.crm-mail-settings-form-block-is_original_eml_attached')
      .insertBefore('.crm-mail-settings-form-block-activity_status'));

    // Show/hide field when 'Used For?' selected value is changed.
    $('select[name="is_default"]')
      .each(showActivityFields)
      .change(showActivityFields);
  })();
})(CRM.$);
</script>
{/literal}
