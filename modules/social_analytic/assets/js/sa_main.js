(function($) {
  "use strict";

  $('select[name=sa_base_workspace]').on('change', function() {
    requestGetJSON('social_analytic/set_default_workspace/' + $(this).val()).done(function(response) {
      if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
        alert_float('success', response.message);
      }
      
      window.location.reload();
    });

  });
})(jQuery);
  