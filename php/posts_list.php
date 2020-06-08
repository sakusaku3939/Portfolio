<?php

$post_data = [];
foreach (filename('../posts/') as $folder) {
    if ($folder !== 'style.css') {
        $parameter = _parameter($folder);
        $title = _title('../posts/'.$folder.'/'.$parameter.'.html');
        $post_data[] = ['title' => $title, 'parameter' => $parameter];
    }
}

function filename($directory) {
    $file_list = [];
    foreach (scandir($directory) as $name) {
        if ($name !== '.' and $name !== '..') {
            $file_list[] = $name;
        }
    }
    return $file_list;
}

function _title($path) {
    $content = file_get_contents($path);
    if ($content and preg_match('!<h1>(.*?)</h1>!s', $content, $title)){
        return $title[1];
    }
    return 'no title';
}

function _parameter($folder) {
    foreach (filename('../posts/' . $folder) as $name) {
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
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <link rel="stylesheet" href="../css/posts_list.css" type="text/css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- フォント -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <title></title>
</head>
<body>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            <?php echo $post_data[1]['title']; ?>
        </div>
        <div class="card_overviewtext">
            <?php ?>
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル2
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル3
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル4
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル5
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル6
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル7
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル8
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
<div class="card card-skin">
    <div class="card_imgframe"></div>
    <div class="card_textbox">
        <div class="card_titletext">
            タイトル9
        </div>
        <div class="card_overviewtext">
            ここは概要となります。ここは概要となります。ここは概要となります。
        </div>
    </div>
</div>
</body>
</html>