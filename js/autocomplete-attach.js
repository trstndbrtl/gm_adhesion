(function ($) {
  Drupal.behaviors.autocomplete.attach = function attach(context) {
    
    var $autocomplete = $(context).find('input#edit-donateur-address').once('autocomplete');
    if ($autocomplete.length) {
      console.log('auto')
      // var blacklist = $autocomplete.attr('data-autocomplete-first-character-blacklist');
      // $.extend(Drupal.autocomplete.options, {
      //   firstCharacterBlacklist: blacklist || ''
      // });

      $autocomplete.autocomplete(Drupal.autocomplete.options).each(function () {
        console.log(Drupal.autocomplete.options)
        $(this).data('ui-autocomplete')._renderItem = Drupal.autocomplete.options.renderItem;
      });

      $autocomplete.on('compositionstart.autocomplete', function () {
        
        Drupal.autocomplete.options.isComposing = true;
      });
      $autocomplete.on('compositionend.autocomplete', function () {
        console.log('compositionend')
        console.log(Drupal.autocomplete.options)
        Drupal.autocomplete.options.isComposing = false;
      });
    }
  };
})(jQuery);