<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Page_Views
 * @subpackage Page_Views/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$tomorrow = strtotime("tomorrow");
$todaysCodes = get_option("pv_results_$tomorrow");
?>

<h3>Todays Codes</h3>
<hr>

<div class="pv_filter">
    <label for="pv_filter">Search code</label>
    <input type="text" placeholder="code" id="pv_filter">
</div>
<div id="pv_codes">
    <?php
    if(is_array($todaysCodes)){
        foreach($todaysCodes as $code){
            echo '<div class="pv_code">'.strtolower($code).'</div>';
        }
    }
    ?>
</div>