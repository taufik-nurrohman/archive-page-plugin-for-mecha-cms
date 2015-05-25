<form class="form-plugin" action="<?php echo $config->url_current; ?>/update" method="post">
  <?php echo Form::hidden('token', $token); ?>
  <?php

  $options = array();
  $selected = File::open(PLUGIN . DS . basename(__DIR__) . DS . 'states' . DS . 'slug.txt')->read();
  if($_pages = Get::pages()) {
      foreach($_pages as $_page) {
          list($_time, $_kind, $_slug) = explode('_', basename($_page, '.txt'));
          $options[$_slug] = Get::articleAnchor($_page)->title;
      }
      ksort($options);
      echo '<p>' . $speak->plugin_archive_title_select_page . '</p>';
      echo '<p>' . Form::select('slug', $options, $selected) . ' ' . Jot::button('action', $speak->update) . '</p>';
  } else {
      echo '<p>' . Config::speak('notify_empty', strtolower($speak->pages)) . '</p>';
  }

  ?>
</form>