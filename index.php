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
                    <div class="sns_button github">
                        <a href="https://github.com/sakusaku3939" title="GitHub"><i class="fa fa-github"></i></a>
                    </div>
                    <div class="sns_button qiita">
                        <a href="https://qiita.com/sakusaku3939" title="Qiita">
                            <svg id="qiita-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300" preserveAspectRatio="none">
                                <g>
                                    <path d="M222.77,227.36C206.59,244.62,184.6,250.29,158,253c-53.89,5.44-100.57-18.62-106.22-78.75a117.62,117.62,0,0,1,14.75-69.77L59.84,78.2c-1.07-4.56,3.13-8.9,7.58-7.82L90.49,76A90.6,90.6,0,0,1,139.1,55.5a89.09,89.09,0,0,1,44.64,7l21.68-13.12a6.41,6.41,0,0,1,9.64,5.21l1.25,30.93h0c16.68,17.6,28,41.64,30.6,68.95,1.45,15.42-.15,27.22-4.24,38.47-1.91,5.27-2.49,9.72,1,14.13,3.34,4.26,8.64,12.53,14.56,11.15,8.88-2.08,18.39-1.53,24.27,2.14A150.16,150.16,0,1,0,258.12,254a35.42,35.42,0,0,1-14.33-5.68C234.86,242.33,226.93,236.7,222.77,227.36Z"/>
                                    <path d="M208.92,117.38l14-1.71-.2-2.53-14.37,1.76c-.62-1.81-1.31-3.59-2.08-5.32l15.83-5-.5-3.17-16.84,5.83c-11.29-23.68-35.71-38.06-63.36-35.26A65,65,0,0,0,85.24,120l-16.47-2.49-.18,3.24,16.22,1.75a49.84,49.84,0,0,0-1.08,4.91l-14.14,1.13.12,2.53L83.5,130a51.14,51.14,0,0,0-.41,5.7l-12.6,5.15.62,3.13,12-5.62c0,1.68.1,2.57.25,4.26,3.34,35.46,33,42.48,68.35,38.9s63.15-16.4,59.81-51.86c-.16-1.7-.4-2.59-.69-4.24l13,2.82.3-2.86-13.63-2.59A48.79,48.79,0,0,0,208.92,117.38Z"/>
                                </g>
                            </svg>
                        </a>
                    </div>
                    <div class="sns_button mail">
                        <a onclick="form_on()" title="Mail"><i class="fas fa-envelope"></i></a>
                    </div>
                    <div class="sns_button slideshare">
                        <a href="https://www.slideshare.net/YukiAoki3" title="SlideShare"><i class="fab fa-slideshare"></i></a>
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
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
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