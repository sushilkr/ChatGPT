jQuery(document).ready(function() {
    jQuery("#edit-message").autocorrect();

    jQuery("#textbox2").autocorrect({
        corrections: {
            arent: "aren't",
            aboutit: "about it"
        }
    });

    jQuery("#textarea").autocorrect({ corrections: { aboutit: "about it" } });
    
    var clipboard = new ClipboardJS('.btn');

      clipboard.on('success', function (e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
      });

      clipboard.on('error', function (e) {
        console.log(e);
      });
    
    // Copy the text to clipboard
    jQuery('#copy_button').on('click', function () {
      jQuery('.info').remove();
      jQuery('#replace-textfield-container').append('<div class="info"></div>');
      var field_selector = 'textarea[name="replace_textfield"]';
      var translated_content = jQuery(field_selector).val();
      if (translated_content) {
        jQuery(field_selector).select();
        navigator.clipboard.writeText(translated_content);
        jQuery('.info').html('The content is copied to clipboard.');
      } else {
        jQuery('.info').html('Please translate your content to copy.');
      }
    });
});