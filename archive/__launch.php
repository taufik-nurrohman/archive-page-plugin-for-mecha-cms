<?php

function kill_that_archive_html_plugin_cache() {
    File::open(CACHE . DS . 'plugin.archive.cache')->delete();
}

// Delete archive HTML cache on article, page and comment update
Weapon::add('on_article_update', 'kill_that_archive_html_plugin_cache');
Weapon::add('on_page_update', 'kill_that_archive_html_plugin_cache');
Weapon::add('on_comment_update', 'kill_that_archive_html_plugin_cache');

// Delete archive HTML cache on plugin destruct
Weapon::add('on_plugin_' . md5(basename(__DIR__)) . '_destruct', 'kill_that_archive_html_plugin_cache');


/**
 * Plugin Updater
 * --------------
 */

Route::accept($config->manager->slug . '/plugin/' . basename(__DIR__) . '/update', function() use($config, $speak) {
    if( ! Guardian::happy()) {
        Shield::abort();
    }
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        File::write($request['slug'])->saveTo(PLUGIN . DS . basename(__DIR__) . DS . 'states' . DS . 'slug.txt');
        Notify::success(Config::speak('notify_success_updated', $speak->plugin));
        Guardian::kick(dirname($config->url_current));
    }
});