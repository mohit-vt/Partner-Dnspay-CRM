<h4 class="country-title"><?php echo _l('viewer_country'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('viewer_by_country_note')?>"></i></h4>
<ul class="country-list">
    <?php foreach ($countries as $name => $value) { ?>
        <li>
            <span class="country-name"><?php echo e($name); ?></span>
            <span class="country-count"><?php echo number_format($value ?? 0); ?></span>
        </li>
    <?php } ?>
</ul>