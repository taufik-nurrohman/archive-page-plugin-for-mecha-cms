<?php

$archive_header = "";
$archive_header_cache = "";
$posts = Get::articles('DESC');
for($i = 0, $count = count($posts); $i < $count; ++$i) {
    $post = Get::articleAnchor($posts[$i]);
    $time = Date::extract($post->time);
    $all_comments = Get::comments($post->time);
    $number = $all_comments !== false ? count($all_comments) : 0;
    $total_comments_text = $number . ' ' . ($number === 1 ? $speak->comment : $speak->comments);
    $archive_header = $config->widget_year_first ? ($time['year'] . ' ' . $time['month_name']) : ($time['month_name'] . ' ' . $time['year']);
    if($archive_header_cache != $archive_header) {
        $archive_html .= ($i > 0 ? '</ul>' : "") . '<h5><a href="' . $config->url . '/' . $config->archive->slug . '/' . substr($post->time, 0, 7) . '">' . $archive_header . '</a></h5>';
        $archive_html .= '<ul>';
        $archive_header_cache = $archive_header;
    }
    $archive_html .= '<li><time datetime="' . $time['W3C'] . '">' . $time['year'] . '/' . $time['month'] . '/' . $time['day'] . '</time> &ndash; <a title="' . $total_comments_text . '" href="' . $post->url . '">' . $post->title . '</a></li>';
}
$archive_html .= '</ul>';