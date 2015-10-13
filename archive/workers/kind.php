<?php

foreach(Get::rawTags() as $tag) {
    $archive_html .= '<h5><a href="' . $config->url . '/' . $config->tag->slug . '/' . $tag['slug'] . '" rel="tag">' . $tag['name'] . '</a></h5>';
    $archive_html .= '<ul>';
    $archive_list_cache = array();
    foreach(Get::articles('DESC', 'kind:' . $tag['id']) as $path) {
        $post = Get::articleAnchor($path);
        $all_comments = Get::comments($post->time);
        $number = $all_comments !== false ? count($all_comments) : 0;
        $total_comments_text = $number . ' ' . ($number === 1 ? $speak->comment : $speak->comments);
        $archive_list_cache[$post->title] = '<li><a title="' . $total_comments_text . '" href="' . $post->url . '">' . $post->title . '</a></li>';
    }
    ksort($archive_list_cache);
    $archive_html .= implode("", array_values($archive_list_cache)) . '</ul>';
}