(function($) {
  "use strict";

    $(document).ready(function() {

  $('select[name=sa_base_workspace]').on('change', function() {
    $.get(site_url+'social_analytic/social_analytic_client/set_contact_default_workspace/' + $(this).val()).done(function(response) {
      if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
        alert_float('success', response.message);
      }
      
      window.location.reload();
    });

  });
    });
})(jQuery);
  