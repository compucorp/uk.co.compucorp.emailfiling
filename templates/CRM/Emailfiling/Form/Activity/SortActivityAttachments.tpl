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

  /**
   * Check if attachments have containers.
   *
   * The View Activity card may have slightly different layout (e.g. on Find
   * Activities page and Manage Cases page) - where each attachment either
   * has it's own container or not (separated by <br>).
   *
   * @returns {boolean}
   *   True - attachments have containers, false otherwise.
   */
  function isFileHavingContainer() {
    return $('[id*="attachFileRecord_"]', $attachmentsContainer).length ? true : false;
  }

  /**
   * Checks if given file is 'original email' eml file.
   *
   * @param {string} filename
   *   File name.
   *
   * @returns {boolean}
   *   True - filename matches 'original email' file name, false otherwise.
   */
  function isEmlFile(filename) {
    var match = filename.match(/\d{8}_\d{4}_?\w*\.eml/);
    return match && match[0] ? true : false;
  }

  /**
   * Move attachment (with container) to a separate row.
   */
  function moveEmlFileRecords() {
    var filename = $('a', this).text().trim();

    if (isEmlFile(filename)) {
      emlCount++;
      $originalEmailContainer.append(this);
    }
  }

  /**
   * Move attachment (without container) to a separate row.
   */
  function moveEmlFiles() {
    var filename = $(this).text().trim();

    // If file name matches original email file naming format.
    if (isEmlFile(filename)) {
      emlCount++;

      // Remove <br/> tag before/after this link to avoid empty space.
      if ($(this).next().is('br')) {
        $(this).next().detach();
      }
      else if ($(this).prev().is('br')) {
        $(this).prev().detach();
      }

      $originalEmailContainer
        .append($('<div></div>').append(this));
    }
  }

  (function init() {
    // Move 'original email' attachments to a separate row.
    if (isFileHavingContainer()) {
      $('[id*="attachFileRecord_"]', $attachmentsContainer).each(moveEmlFileRecords);
    }
    else {
      $('a', $attachmentsContainer).each(moveEmlFiles);
    }

    // Append original email file row to the activity card.
    if (emlCount) {
      $originalEmailContainer.closest('tr').insertAfter($attachmentsContainer.closest('tr'));
    }
  })();
})(CRM.$);
</script>
{/literal}
