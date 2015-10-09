<form class="form-plugin" action="<?php echo $config->url_current; ?>/update" method="post">
  <?php echo Form::hidden('token', $token); ?>
  <?php

  $options = array();
  $radios = array();
  $archive_config = File::open(PLUGIN . DS . File::B(__DIR__) . DS . 'states' . DS . 'config.txt')->unserialize();
  if($_pages = Get::pages()) {
      foreach($_pages as $_page) {
          list($_time, $_kind, $_slug) = explode('_', File::N($_page), 3);
          $options[$_slug] = Get::articleAnchor($_page)->title;
      }
      asort($options);
      foreach(glob(PLUGIN . DS . File::B(__DIR__) . DS . 'workers' . DS . '*.php') as $radio) {
          $radio = File::N($radio);
          $radios[$radio] = ucfirst(Text::parse($radio, '->text'));
      }
      echo '<p>' . $speak->plugin_archive_title_select_page . '</p>';
      echo '<p>' . Form::select('slug', $options, $archive_config['slug']) . ' ' . Jot::button('action', $speak->update) . '</p>';
      echo '<p>' . Form::radio('kind', $radios, $archive_config['kind']) . '</p>';
  } else {
      echo '<p>' . Config::speak('notify_empty', strtolower($speak->pages)) . '</p>';
  }

  ?>
</form>