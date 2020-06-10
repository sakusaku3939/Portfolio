<?php

$post_data = [];
foreach (getFilename('../posts/') as $folder) {
    if ($folder !== 'style.css') {
        $parameter = _parameter($folder);
        $path = '../posts/' . $folder . '/' . $parameter . '.html';
        $content = file_get_contents($path);
        $title = _title($content);
        $overview = _overview($content);
        $image_path = _image($content, $folder);

        $post_data[] = [
            'title' => $title,
            'overview' => $overview,
            'image' => $image_path,
            'parameter' => $parameter,
            'date' => $folder
        ];
    }
}

foreach ($post_data as $post) {
    echo '<div class="card card-skin" onclick="click_posts(\'' . $post["date"] . '\', \'' . $post["parameter"] . '\')">' .
        '<div class="card_imgframe" style="background-image: url(' . $post['image'] . ')"></div>' .
        '<div class="card_textbox">' .
        '<div class="card_titletext">' . $post['title'] . '</div>' .
        '<div class="card_overviewtext">' . $post['overview'] . '</div>' .
        '</div></div>';
}

function getFilename($directory)
{
    return array_diff(scandir($directory, 1), array('.', '..'));
}

function _title($content)
{
    if ($content and preg_match('!<h1>(.*?)</h1>!s', $content, $title)) {
        return $title[1];
    }
    return 'no title';
}

function _overview($content)
{
    if ($content and preg_match('!<div id="overview">(.*?)</div>!s', $content, $overview)) {
        return $overview[1];
    }
    return 'no overview';
}

function _image($content, $folder)
{
    if ($content and preg_match('!<img src="(.*?)" alt="">!s', $content, $image_path)) {
        return '../posts/' . $folder . '/' . $image_path[1];
    }
    return '../image/NoImage.jpg';
}

function _parameter($folder)
{
    foreach (getFilename('../posts/' . $folder) as $name) {
        if (strpos($name, '.html')) {
            return preg_replace("/(.+)(\.[^.]+$)/", "$1", $name);
        }
    }
    return 'no-parameter';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/posts_list.css" type="text/css">
    <!-- フォント -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <title></title>
</head>
<body>
<script>
    function click_posts(date, parameter) {
        window.parent.sessionStorage.setItem("src", "posts/" + date + "/" + parameter + ".html")
        window.parent.history.pushState(null, null, "?posts=" + parameter)
        window.parent.toggle()
    }
</script>
</body>
</html>