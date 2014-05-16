<?php

if( ! $language = File::exist(PLUGIN . '/archive/languages/' . $config->language . '/speak.txt')) {
    $language = PLUGIN . '/archive/languages/en_US/speak.txt';
}

// Merge the plugin language parts into `Config::speak()`
Config::merge('speak', Text::toArray(File::open($language)->read()));

// Delete archive HTML cache on page update
Weapon::add('on_page_update', function() {
    File::open(CACHE . '/plugin.archive.cache.txt')->delete();
});

// Delete archive HTML cache on comment update
Weapon::add('on_comment_update', function() {
    File::open(CACHE . '/plugin.archive.cache.txt')->delete();
});

// Delete archive HTML cache on plugin destruct
Weapon::add('on_plugin_' . md5('archive') . '_destruct', function() {
    File::open(CACHE . '/plugin.archive.cache.txt')->delete();
});

/**
 * Main Route
 */
$slug = File::open(PLUGIN . '/archive/states/slug.txt')->read();
Route::accept($slug, function() use($slug) {

    $config = Config::get();
    $speak = Config::speak();

    // Use archive HTML cache if available
    if($cache = File::exist(CACHE . '/plugin.archive.cache.txt')) {
        $archive_html = File::open($cache)->read();
    } else {
        // If not, create one!
        $archive_html = "";
        $archive_header = "";
        $archive_header_cache = "";
        $posts = Get::articles('DESC');
        for($i = 0, $count = count($posts); $i < $count; ++$i) {
            $post = Get::articleAnchor($posts[$i]);
            $time = Date::extract($post->time);
            $total_comments = Get::comments($post->time);
            $total_comments_text = ($total_comments !== false ? count($total_comments) : 0) . ' ' . (count($total_comments) > 1 ? $speak->comments : $speak->comment);
            $archive_header = $config->widget_year_first ? ($time['year'] . ' ' . $time['month']) : ($time['month'] . ' ' . $time['year']);
            if ($archive_header_cache != $archive_header) {
                $archive_html .= ($i > 0 ? '</ul>' : "") . '<h5><a href="' . $config->url . '/' . $config->archive->slug . '/' . $time['year'] . '-' . $time['month_number'] . '">' . $archive_header . '</a></h5>';
                $archive_html .= '<ul>';
                $archive_header_cache = $archive_header;
            }
            $archive_html .= '<li><time datetime="' . $time['W3C'] . '">' . $time['year'] . '/' . $time['month_number'] . '/' . $time['day_number'] . '</time> &ndash; <a title="' . $total_comments_text . '" href="' . $post->url . '">' . $post->title . '</a></li>';
        }
        $archive_html .= '</ul>';
        // Create new cache file for your archive page
        File::write($archive_html)->saveTo(CACHE . '/plugin.archive.cache.txt');
    }

    $page = Get::page($slug);

    // Replace string `{{toc_archive}}` in the
    // selected page with the HTML markup of archive
    $page->content = str_replace('{{toc_archive}}', $archive_html, $page->content);

    Config::set(array(
        'page_type' => 'page',
        'page_title' => $page->title . $config->title_separator . $config->title,
        'page' => $page
    ));

    Shield::attach('page-' . $slug);

});

/**
 * Plugin Updater
 */
Route::accept($config->manager->slug . '/plugin/archive/update', function() use($config, $speak) {
    if( ! Guardian::happy()) {
        Shield::abort();
    }
    if(Request::post()) {
        Guardian::checkToken(Request::post('token'));
        File::write(Request::post('slug'))->saveTo(PLUGIN . '/archive/states/slug.txt');
        Notify::success(Config::speak('notify_success_updated', array($speak->plugin)));
        Guardian::kick(dirname($config->url_current));
    }
});