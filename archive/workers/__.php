<?php

if($articles = Get::articles('DESC')) {
    $archive_html .= '<ul>';
    foreach( as $article) {
        $post = Get::articleAnchor($article);
        $all_comments = Get::comments($post->time);
        $number = $all_comments !== false ? count($all_comments) : 0;
        $total_comments_text = $number . ' ' . ($number === 1 ? $speak->comment : $speak->comments);
        $archive_html .= '<li><time datetime="' . Date::format($post->time, 'c') . '">' . str_replace('-', '/', $post->time) . '</time> &ndash; <a title="' . $total_comments_text . '" href="' . $post->url . '">' . $post->title . '</a></li>';
    }
    $archive_html .= '</ul>';
}