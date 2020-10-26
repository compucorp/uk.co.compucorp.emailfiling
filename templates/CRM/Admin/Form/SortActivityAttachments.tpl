<table class="hidden">
  <tr class="original-email-container">
    <td class="label">Original Email</td>
    <td class="view-value"></td>
  </tr>
</table>

{literal}
<script type="text/javascript">
(function($) {
  var $attachmentsContainer = $('td.label:contains("Attachment")').siblings('.view-value');
  var $originalEmailContainer = $('.original-email-container .view-value');
  var emlCount = 0;

  function moveEmlFiles() {
    var filename = $('a', this).text().trim();
    var match = filename.match(/\d{8}_\d{4}_?\w*\.eml/);

    // If file name matches original email file naming format.
    if (match && match[0]) {
      emlCount++;
      // Move file to a separate row.
      $originalEmailContainer.append(this);
    }
  }

  (function init() {
    // Go through all attachments.
    $('[id*="attachFileRecord_"]', $attachmentsContainer).each(moveEmlFiles);

    // Append original email file row to the activity card.
    if (emlCount) {
      $originalEmailContainer.closest('tr').insertAfter($attachmentsContainer.closest('tr'));
    }
  })();
})(CRM.$);
</script>
{/literal}
