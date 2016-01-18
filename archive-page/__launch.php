<?php

function do_remove_archive_page_cache() {
    File::open(CACHE . DS . 'plugin.archive-page.cache')->delete();
}

$hooks = array(
    // Delete archive HTML cache on article, page and comment update
    'on_article_update',
    'on_page_update',
    'on_comment_update',
    // Delete archive HTML cache on plugin eject and destruct
    'on_plugin_' . md5(File::B(__DIR__)) . '_eject',
    'on_plugin_' . md5(File::B(__DIR__)) . '_destruct'
);

Weapon::add($hooks, 'do_remove_archive_page_cache');


/**
 * Plugin Updater
 * --------------
 */

Route::accept($config->manager->slug . '/plugin/' . File::B(__DIR__) . '/update', function() use($config, $speak) {
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        unset($request['token']);
        File::serialize($request)->saveTo(__DIR__ . DS . 'states' . DS . 'config.txt', 0600);
        do_remove_archive_page_cache();
        Notify::success(Config::speak('notify_success_updated', $speak->plugin) . ($request['slug'] ? ' <a class="pull-right" href="' . Filter::colon('page:url', $config->url . '/' . $request['slug']) . '" target="_blank">' . Jot::icon('eye') . ' ' . $speak->view . '</a>' : ""));
        Guardian::kick(File::D($config->url_current));
    }
});