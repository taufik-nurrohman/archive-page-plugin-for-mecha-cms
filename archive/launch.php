<?php

// Load the configuration data
$archive_config = File::open(__DIR__ . DS . 'states' . DS . 'config.txt')->unserialize();

if(Route::is($archive_config['slug'])) {
    // Use archive HTML cache if available
    if($cache = File::exist(CACHE . DS . 'plugin.archive.cache')) {
        $archive_html = File::open($cache)->read();
    } else {
        // If not, create one!
        $archive_html = "";
        include __DIR__ . DS . 'workers' . DS . $archive_config['kind'] . '.php';
        // Create new cache file for your archive page
        File::write($archive_html)->saveTo(CACHE . DS . 'plugin.archive.cache');
    }
    // Replace string `{{toc_archive}}` in the
    // selected page with the HTML markup of archive
    Filter::add('page:content', function($content) use($archive_html) {
        if( ! Text::check($content)->has('{{toc_archive}}')) {
            return $content . $archive_html;
        }
        return str_replace('{{toc_archive}}', $archive_html, $content);
    });
}