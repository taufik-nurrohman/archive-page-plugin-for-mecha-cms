<form class="form-plugin" action="<?php echo $config->url_current; ?>/update" method="post">
  <input name="token" type="hidden" value="<?php echo Guardian::makeToken(); ?>">
  <p><?php echo $speak->plugin_archive_title_select_page; ?></p>
  <p>
    <select name="slug" class="input-block">
    <?php

    $options = array();
    $selected = File::open(PLUGIN . '/archive/states/slug.txt')->read();
    if($s_pages = Get::pages('ASC')) {
        foreach($s_pages as $s_page) {
            list($s_time, $s_kind, $s_slug) = explode('_', basename($s_page, '.txt'));
            $options[] = $s_slug; // take the page slug
        }
        foreach($options as $option) {
            echo '<option value="' . $option . '"' . ($option == $selected ? ' selected' : "") . '>' . $config->url . '/' . $option . '</option>';
        }
    } else {
        echo '<option disabled>' . Config::speak('notify_empty', array(strtolower($speak->pages))) . '</option>';
    }

    ?>
    </select>
  </p>
  <p><button class="btn btn-primary btn-update" type="submit"><i class="fa fa-check-circle"></i> <?php echo $speak->update; ?></button></p>
</form>