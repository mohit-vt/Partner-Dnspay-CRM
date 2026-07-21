
<div class="col-md-6">
    <h4 class="country-title"><?php echo _l('top_countries'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('instagram_top_countries_note')?>"></i></h4>
    <ul class="country-list">
        <?php foreach ($countries as $name => $value) { ?>
            <li>
                <span class="country-name"><?php echo e($name); ?></span>
                <span class="country-count"><?php echo number_format($value ?? 0); ?></span>
            </li>
        <?php } ?>
    </ul>
</div>

<div class="col-md-6">
    <h4 class="country-title"><?php echo _l('top_languages'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('instagram_top_languages_note')?>"></i></h4>
    <ul class="country-list">
        <?php foreach ($languages as $name => $value) { ?>
            <li>
                <span class="country-name"><?php echo e($name); ?></span>
                <span class="country-count"><?php echo number_format($value ?? 0); ?></span>
            </li>
        <?php } ?>
    </ul>

</div>
