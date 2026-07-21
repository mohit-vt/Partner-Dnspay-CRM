<script type="text/javascript">
    var fnServerParams = {
        "from_date": '[name="from_date"]',
        "to_date": '[name="to_date"]',
        'account': '[name=account]',
        'social': '[name=social]',
        'lock': '[name=lock]'
    };

    var social;

    (function($) {
    	"use strict";
        appValidateForm($('#workspace-form'), {
          value: 'required',
          time: 'required',
          type: 'required',
        }, workspace_form_handler);

        $('input[name="from_date"]').on('change', function() {
            init_workspace_table();
        });

        $('input[name="to_date"]').on('change', function() {
            init_workspace_table();
        });

        $('select[name="type"]').on('change', function() {
            $('.fan-sub-type').addClass('hide');
            $('.post-sub-type').addClass('hide');
            $('.reaction-sub-type').addClass('hide');
            $('.follower-sub-type').addClass('hide');
            $('.stories-performance-sub-type').addClass('hide');
            $('.engagement-sub-type').addClass('hide');
            $('.view-sub-type').addClass('hide');
            $('.subscriber-sub-type').addClass('hide');
            
            if ($(this).val() == 'fan') {
                $('.fan-sub-type').removeClass('hide');
            }else if ($(this).val() == 'reaction') {
                $('.reaction-sub-type').removeClass('hide');
            }else if ($(this).val() == 'post') {
                $('.post-sub-type').removeClass('hide');
            }else if ($(this).val() == 'follower') {
                $('.follower-sub-type').removeClass('hide');
            }else if ($(this).val() == 'stories_performance') {
                $('.stories-performance-sub-type').removeClass('hide');
            }else if ($(this).val() == 'engagement') {
                $('.engagement-sub-type').removeClass('hide');
            }else if ($(this).val() == 'view') {
                $('.view-sub-type').removeClass('hide');
            }else if ($(this).val() == 'subscriber') {
                $('.subscriber-sub-type').removeClass('hide');
            }
        });


        init_workspace_table();
    })(jQuery);

    function init_workspace_table() {
      "use strict";

      if ($.fn.DataTable.isDataTable('.table-workspaces')) {
        $('.table-workspaces').DataTable().destroy();
      }

      social = $('input[name=social]').val();

      var notsortable = [];
      if(social == 'facebook'){
        notsortable = [1,2,3,4,5,6,7,8,9];
      }else if(social == 'instagram'){
        notsortable = [1,2,3,4,5,6,7,8,9];
      }else if(social == 'tiktok'){
        notsortable = [1,2,3,4,5];
      }else if(social == 'twitter'){
        notsortable = [1,2,3,4,5];
      }else if(social == 'youtube'){
        notsortable = [1,2,3,4,5,6,7,8];
      }

      initDataTable('.table-workspaces', admin_url + 'social_analytic/raw_data_detail_table', [], notsortable, fnServerParams, [0, "desc"]);
    }

    function edit_data(invoker) {
      "use strict";
        $('#workspace-modal').find('button[type="submit"]').prop('disabled', false);
        var type = $(invoker).data('type');
        var date = $(invoker).data('date');
        var value = $(invoker).data('value');

        $('select[name="type"]').val(type).change();
        $('input[name="time"]').val(date);
        $('input[name="value"]').val(value);

        $('#workspace-modal').modal('show');
    }

    function workspace_form_handler(form) {
        "use strict";
        $('#workspace-modal').find('button[type="submit"]').prop('disabled', true);

        var formURL = form.action;
        var formData = new FormData($(form)[0]);

        $.ajax({
            type: $(form).attr('method'),
            data: formData,
            mimeType: $(form).attr('enctype'),
            contentType: false,
            cache: false,
            processData: false,
            url: formURL
        }).done(function(response) {
            response = JSON.parse(response);
            if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
              alert_float('success', response.message);
              init_workspace_table();
            }
            $('#workspace-modal').modal('hide');
        }).fail(function(error) {
            alert_float('danger', JSON.parse(error.mesage));
        });

        return false;
    }

    function unlock() {
        "use strict";
        $('.lock-btn').removeClass('hide');
        $('.unlock-btn').addClass('hide');
        $('input[name=lock]').val(0);

        init_workspace_table();
    }

    function lock() {
        "use strict";
        $('.lock-btn').addClass('hide');
        $('.unlock-btn').removeClass('hide');
        $('input[name=lock]').val(1);
        
        init_workspace_table();
    }

    function add_data() {
      "use strict";
        $('#workspace-modal').find('button[type="submit"]').prop('disabled', false);

        $('select[name="type"]').val('fan').change();
        $('input[name="time"]').val('');
        $('input[name="value"]').val('');

        $('#workspace-modal').modal('show');
    }
</script>