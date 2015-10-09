<?php

$archive_html = '<ul>';
foreach(Get::articles('DESC') as $article) {
    $post = Get::articleAnchor($article);
    $archive_html .= '<li><a href="' . $post->url . '">' . $post->title . '</a></li>';
}
$archive_html .= '</ul>';