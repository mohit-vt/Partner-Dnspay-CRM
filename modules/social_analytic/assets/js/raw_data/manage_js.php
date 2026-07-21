<script type="text/javascript">
    var fnServerParams = {
        'type': '[name=type]'
    };
    (function($) {
    	"use strict";

        init_facebook_table();
    })(jQuery);

    function init_facebook_table() {
      "use strict";

      if ($.fn.DataTable.isDataTable('.table-workspaces')) {
        $('.table-workspaces').DataTable().destroy();
      }
      initDataTable('.table-workspaces', admin_url + 'social_analytic/raw_data_table', false, false, fnServerParams);
    }

</script>