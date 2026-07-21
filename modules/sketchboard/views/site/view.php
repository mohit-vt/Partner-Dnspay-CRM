<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?= $board->name ?></title>
      <script src="<?php echo module_dir_url('sketchboard','assets/js/react.development.js') ?>"></script>
      <link rel="stylesheet" type="text/css" id="tailwind-css" href="<?php echo site_url('assets/builds/tailwind.css?v=1725981629')?>">
      <link rel="stylesheet" type="text/css" id="style-css" href="<?php echo site_url('assets/css/style.min.css?v=1725981629')?>">
      <link rel="stylesheet" type="text/css" id="fontawesome-css" href="<?php echo site_url('assets/plugins/font-awesome/css/fontawesome.min.css?v=1725981629')?>">
      <link rel="stylesheet" type="text/css" id="fontawesome-css" href="<?php echo site_url('assets/plugins/font-awesome/css/solid.min.css?v=1725981629')?>">
      <link rel="stylesheet" type="text/css" id="public_sketchboard-css" href="<?php echo module_dir_url('sketchboard','assets/css/public_sketchboard.css')?>">
   </head>
   <body>
      <div class="public_view">
         <div class="content p-0" id="full_screen">
            <div class="row topbar tw-mb-2">
               <div class="col-md-6">
                  <h4 class="tw-mt-0 tw-mb-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                     <span><?= $board->name ?></span>
                  </h4>
                  <div class="clearfix"></div>
               </div>
               <div class="col-md-6">
                  <a href="#" onclick="goFullscreen(); return false;" data-placement="left" data-toggle="tooltip" data-title="Toggle full screen view" class="btn_full_screen btn pull-right mright5 hover:tw-text-neutral-800 active:tw-text-neutral-800 hover:tw-bg-neutral-300  tw-bg-neutral-200 tw-text-neutral-500 tw-items-center tw-justify-center tw-inline-flex" data-original-title="" title="">
                  <i class="fa fa-expand"></i></a>
                  <?php if(has_permission('sketchboard', '', 'edit')){ ?>
                  <button type="button" onclick="$('form').submit(); return false;" class="btn btn-success pull-right mright5"><?= _l('save')?></button>
                  <?php } ?>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12" id="small-table">
                  <div class="panel_s">
                     <div class="panel-body">
                        <div id="public-excalidraw-wrapper"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php echo form_open($this->uri->uri_string()); ?>
      <input type="hidden" value="" id="sketch_data" name="sketch_data">
      <?php echo form_close(); ?>
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="<?php echo module_dir_url('sketchboard','assets/js/react.development.js') ?>"></script>
      <script src="<?php echo module_dir_url('sketchboard','assets/js/react-dom.development.js') ?>"></script>
      <script src="<?php echo module_dir_url('sketchboard','assets/js/excalidraw.development.js') ?>"></script>
      <script type="text/javascript">
         var is_edit =  <?=$is_editable?>;
         function onExcalidrawChange(elements, appState) {
             $("#sketch_data").val(JSON.stringify(elements));
         }
         const Excalidraw = window.ExcalidrawLib.Excalidraw;
         const ExcalidrawWithRef = React.forwardRef((props, ref) => {
             return React.createElement(Excalidraw, {
                 ...props,
                 forwardRef: ref
             });
         });
         const excalidrawWrapper = document.getElementById("public-excalidraw-wrapper");
         const root = ReactDOM.createRoot(excalidrawWrapper);
         var sketch_data = <?= !empty($board->sketch_data)?$board->sketch_data:'[{}]'; ?>;
         const excalidrawProps = {
             onChange: onExcalidrawChange,
             UIOptions: {
                 tools: {
                     image: false,
                 },
                 
             },
             viewModeEnabled:is_edit == 1?false:true,
         };
         excalidrawProps.initialData = {
             elements:  sketch_data
         };
         root.render(React.createElement(ExcalidrawWithRef, excalidrawProps));
         
         function goFullscreen() {
             var elem = document.getElementById("full_screen");
         
             // Check if the document is already in fullscreen mode
             if (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
                 // Exit fullscreen
                 if (document.exitFullscreen) {
                     document.exitFullscreen();
                 } else if (document.mozCancelFullScreen) { // Firefox
                     document.mozCancelFullScreen();
                 } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
                     document.webkitExitFullscreen();
                 } else if (document.msExitFullscreen) { // IE/Edge
                     document.msExitFullscreen();
                 }
             } else {
                 // Enter fullscreen
                 if (elem.requestFullscreen) {
                     elem.requestFullscreen();
                 } else if (elem.mozRequestFullScreen) { // Firefox
                     elem.mozRequestFullScreen();
                 } else if (elem.webkitRequestFullscreen) { // Chrome, Safari and Opera
                     elem.webkitRequestFullscreen();
                 } else if (elem.msRequestFullscreen) { // IE/Edge
                     elem.msRequestFullscreen();
                 }
             }
         }
         
      </script>
   </body>
</html>