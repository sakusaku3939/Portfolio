<?php
$post_data = [];
foreach (getFilename('./posts/') as $folder) {
    if ($folder !== 'style.css') {
        $parameter = _parameter($folder);
        $path = './posts/' . $folder . '/' . $parameter . '.html';
        $content = file_get_contents($path);
        $title = _title($content);
        $overview = _overview($content);
        $position = _position($content);
        $image_path = _image($content, $folder);

        $post_data[] = [
            'title' => $title,
            'overview' => $overview,
            'position' => $position,
            'image' => $image_path,
            'parameter' => $parameter,
            'date' => $folder
        ];
    }
}

$json = json_encode($post_data, JSON_UNESCAPED_UNICODE);


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

function _position($content)
{
    if ($content and preg_match('!<div id="position">(.*?)\s(.*?)</div>!s', $content, $overview)) {
        return $overview[1] . ' ' . $overview[2];
    }
    return 'center center';
}

function _image($content, $folder)
{
    if ($content and preg_match('!<img src="(.*?)"!s', $content, $image_path)) {
        return 'posts/' . $folder . '/' . $image_path[1];
    }
    return 'image/NoImage.jpg';
}

function _parameter($folder)
{
    foreach (getFilename('posts/' . $folder) as $name) {
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex">

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@_aokiyuki"/>
    <meta property="og:title" content="Aokiti's Portfolio"/>
    <meta property="og:description" content="Aokitiのポートフォリオです"/>
    <meta property="og:image" content="https://portfolio.sakusaku3939.com/image/ogp.jpg"/>

    <title>Aokiti's Portfolio</title>

    <link rel="shortcut icon" href="image/favicon.ico">
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <link rel="stylesheet" href="css/loading.css" type="text/css">
</head>
<body id="index">
<div id="posts-wrapper" class="posts-wrapper-animation">
    <div id="posts" class="posts-animation">
        <div id="navigation-bar">
            <div id="back" onclick="back()"><i class="fas fa-angle-left"></i>Back</div>
            <div id="share" onclick="share_on()"><i class="fas fa-share-alt"></i>
                <ul id="share_menu">
                    <li><a onclick="copy_clipboard()"><span class="menu_icon">
                <i class="fas fa-link"></i></span>リンクを取得</a></li>
                    <li><a id="twitter" href="#" target="_blank"><span class="menu_icon">
                <i class="fab fa-twitter"></i></span>Twitter</a></li>
                    <li><a id="facebook" href="#" target="_blank"><span class="menu_icon">
                <i class="fab fa-facebook-f"></i></span>Facebook</a></li>
                    <li><a id="pocket" href="#" target="_blank"><span class="menu_icon">
                <i class="fab fa-get-pocket"></i></span>Pocket</a></li>
                </ul>
            </div>
        </div>
        <iframe id="iframe-posts" src="" scrolling="no"></iframe>
    </div>
</div>
<div id="loader">
    <div id="main">
        <div class="side-bar">
            <div class="title">Portfolio</div>
            <div class="sns_box">
                <div style="display: inline-block">
                    <div class="sns_button twitter">
                        <a href="https://twitter.com/_aokiyuki" title="Twitter"><i class="fa fa-twitter"></i></a>
                    </div>
                    <div class="sns_button github">
                        <a href="https://github.com/sakusaku3939" title="GitHub"><i class="fa fa-github"></i></a>
                    </div>
                    <div class="sns_button mail">
                        <a onclick="form_on()" title="Mail"><i class="fas fa-envelope"></i></a>
                    </div>
                    <div class="sns_button qiita">
                        <a href="https://qiita.com/sakusaku3939" title="Qiita"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div id="form">
                    <iframe id="iframe-form" src="form.html"></iframe>
                </div>
            </div>
        </div>
        <div id="posts-grid">
            <?php
            echo '<div class="card-contents">';
            foreach ($post_data as $post) {
                echo '<div class="card card-skin" onclick="click_posts(\'' . $post["date"] . '\', \'' . $post["parameter"] . '\')">' .
                    '<div class="card_date">' . date('Y.m.d', strtotime($post['date'])) . '</div>' .
                    '<div class="card_imgframe" style="background-image: url(' . $post['image'] . '); background-position: ' . $post['position'] . ';"></div>' .
                    '<div class="card_textbox">' .
                    '<div class="card_titletext">' . $post['title'] . '</div>' .
                    '<div class="card_overviewtext">' . $post['overview'] . '</div>' .
                    '</div></div>';
            }
            echo '</div>';
            ?>
        </div>
    </div>
</div>
<!-- フォント -->
<link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
<!-- アイコン -->
<script src="https://kit.fontawesome.com/57176a007a.js" crossorigin="anonymous"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- PACE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

<script src="js/index.js" type="text/javascript"></script>
<script> setPost_data(<?php echo $json?>) </script>
<script>
    $(function () {
        let style = '<link rel="stylesheet" href="css/animation.css">';
        $('head link:last').after(style);
    });
</script>
</body>
</html>