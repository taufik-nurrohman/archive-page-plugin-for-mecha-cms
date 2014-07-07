<form class="form-plugin" action="<?php echo $config->url_current; ?>/update" method="post">
  <input name="token" type="hidden" value="<?php echo Guardian::makeToken(); ?>">
  <p><?php echo $speak->plugin_archive_title_select_page; ?></p>
  <p><select name="slug">
    <?php

    $options = array();
    $selected = File::open(PLUGIN . DS . 'archive' . DS . 'states' . DS . 'slug.txt')->read();
    if($_pages = Get::pages()) {
        foreach($_pages as $_page) {
            list($_time, $_kind, $_slug) = explode('_', basename($_page, '.txt'));
            $options[] = $_slug;
        }
        sort($options);
        foreach($options as $option) {
            echo '<option value="' . $option . '"' . ($option == $selected ? ' selected' : "") . '>' . $config->url . '/' . $option . '</option>';
        }
    } else {
        echo '<option disabled>' . Config::speak('notify_empty', array(strtolower($speak->pages))) . '</option>';
    }

    ?>
    </select> <button class="btn btn-action" type="submit"><i class="fa fa-check-circle"></i> <?php echo $speak->update; ?></button></p>
</form>