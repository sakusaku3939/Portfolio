<?php
require_once dirname(__FILE__) . '/php/session.php';

$pick_post_parameter = [
    "presc",
    "tkg-beacon",
    "android-deepl",
];
$post_data = [];
$pick_post_data = [];

foreach (getFilename('./posts/') as $folder) {
    if ($folder !== 'style.css') {
        $parameter = _parameter($folder);
        $path = './posts/' . $folder . '/' . $parameter . '.html';
        $content = file_get_contents($path);
        $title = _title($content);
        $overview = _overview($content);
        $position = _position($content);
        $tag_list = _tag($content);
        $image_path = _image($content, $folder);

        if (in_array($parameter, $pick_post_parameter)) {
            $pick_post_data[] = [
                'title' => $title,
                'overview' => $overview,
                'tag' => $tag_list,
                'position' => $position,
                'image' => $image_path,
                'parameter' => $parameter,
                'date' => $folder,
                'pick' => true,
            ];
        } else {
            $post_data[] = [
                'title' => $title,
                'overview' => $overview,
                'tag' => $tag_list,
                'position' => $position,
                'image' => $image_path,
                'parameter' => $parameter,
                'date' => $folder,
                'pick' => false,
            ];
        }
    }
}

$json = json_encode(array_merge($pick_post_data, $post_data), JSON_UNESCAPED_UNICODE);

function getFilename($directory)
{
    return array_diff(scandir($directory, 1), array('.', '..'));
}

function _title($content): string
{
    if ($content and preg_match('!<h1>(.*?)</h1>!s', $content, $title)) {
        return $title[1];
    }
    return 'no title';
}

function _overview($content): string
{
    if ($content and preg_match('!<div id="overview">(.*?)</div>!s', $content, $overview)) {
        return $overview[1];
    }
    return 'no overview';
}

function _position($content): string
{
    if ($content and preg_match('!<div id="position">(.*?)\s(.*?)</div>!s', $content, $overview)) {
        return $overview[1] . ' ' . $overview[2];
    }
    return 'center center';
}

function _image($content, $folder): string
{
    if ($content and preg_match('!<img src="(.*?)"!s', $content, $image_path)) {
        return 'posts/' . $folder . '/' . $image_path[1];
    }
    return 'image/NoImage.jpg';
}

function _tag($content): string
{
    if ($content and preg_match('!<ul id="tag">(.*?)</ul>!s', $content, $overview)) {
        return $overview[1];
    }
    return '';
}

function _parameter($folder): string
{
    foreach (getFilename('posts/' . $folder) as $name) {
        if (strpos($name, '.html')) {
            return preg_replace("/(.+)(\.[^.]+$)/", "$1", $name);
        }
    }
    return 'no-parameter';
}

function draw_card_content($post_data)
{
    echo '<div class="card-contents">';
    foreach ($post_data as $post) {
        $pick_mark = $post['pick'] ? '<div class="card-pick"><i class="fas fa-thumbtack"></i></div>' : '';
        echo '<div class="card card-skin" onclick="clickPosts(\'' . fetch_posts($post["date"], $post["parameter"]) . '\', \'' . $post["parameter"] . '\')">' .
            '<div class="card-date">' . date('Y.m.d', strtotime($post['date'])) . '</div>' . $pick_mark .
            '<div class="card-img" style="background-image: url(' . $post['image'] . '); background-position: ' . $post['position'] . ';"></div>' .
            '<div class="card-text-box">' .
            '<div class="card-title">' . $post['title'] . '</div>' .
            '<ul class="card-tag">' . $post['tag'] . '</ul>' .
            '<div class="card-overview">' . $post['overview'] . '</div>' .
            '</div></div>';
    }
    echo '</div>';
}

function fetch_posts($folder, $parameter): string
{
    $posts = file_get_contents("posts/$folder/$parameter.html");
    $fix_style_path = str_replace("../style.css", "posts/style.css", $posts);

    $img_reg = '/src=[\"|\']((?!.*https:).*?(jpg|jpeg|gif|png|mp4))[\"|\']/i';
    $fix_image_path = preg_replace($img_reg, 'src="posts/' . $folder . '/$1"', $fix_style_path);

    return base64_encode(rawurlencode($fix_image_path));
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex">

    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            let f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WKPDZ5L');</script>
    <!-- End Google Tag Manager -->

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@_aokiyuki"/>
    <meta property="og:title" content="Aokiti's Portfolio"/>
    <meta property="og:description" content="Aokitiのポートフォリオです"/>
    <meta property="og:image" content="https://portfolio.sakusaku3939.com/image/ogp.jpg"/>

    <title>Aokiti's Portfolio</title>

    <link rel="shortcut icon" href="image/favicon.ico">
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <link rel="stylesheet" href="css/form.css" type="text/css">
    <link rel="stylesheet" href="css/loading.css" type="text/css">
    <link rel="stylesheet" href="css/animation.css" type="text/css">
</head>
<body id="index">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WKPDZ5L"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="posts-wrapper" class="posts-wrapper-animation">
    <div class="posts-animation">
        <div class="navigation-bar">
            <div class="back" onclick="window.history.back()"><i class="fas fa-angle-left"></i>Back</div>
            <div class="share" onclick="openShareMenu()"><i class="fas fa-share-alt"></i>
                <ul id="share-menu">
                    <li><a onclick="copyClipboard()"><span class="menu-icon">
                <i class="fas fa-link"></i></span>リンクを取得</a></li>
                    <li><a id="twitter" href="#" target="_blank"><span class="menu-icon">
                <i class="fab fa-twitter"></i></span>Twitter</a></li>
                    <li><a id="facebook" href="#" target="_blank"><span class="menu-icon">
                <i class="fab fa-facebook-f"></i></span>Facebook</a></li>
                    <li><a id="pocket" href="#" target="_blank"><span class="menu-icon">
                <i class="fab fa-get-pocket"></i></span>Pocket</a></li>
                </ul>
            </div>
        </div>
        <div class="posts-content-wrapper">
            <div id="posts-content" class="posts"></div>
        </div>
        <div class="posts-bottom-margin"></div>
    </div>
</div>
<div id="loader">
    <div id="main">
        <div class="side-bar">
            <div class="title">Portfolio</div>
            <div class="sns-box">
                <div style="display: inline-block">
                    <div class="sns-button github">
                        <a href="https://github.com/sakusaku3939" title="GitHub" target="_blank">
                            <i class="fa fa-github"></i>
                        </a>
                    </div>
                    <div class="sns-button qiita">
                        <a href="https://qiita.com/sakusaku3939" title="Qiita" target="_blank">
                            <svg id="qiita-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"
                                 preserveAspectRatio="none">
                                <g>
                                    <path d="M222.77,227.36C206.59,244.62,184.6,250.29,158,253c-53.89,5.44-100.57-18.62-106.22-78.75a117.62,117.62,0,0,1,14.75-69.77L59.84,78.2c-1.07-4.56,3.13-8.9,7.58-7.82L90.49,76A90.6,90.6,0,0,1,139.1,55.5a89.09,89.09,0,0,1,44.64,7l21.68-13.12a6.41,6.41,0,0,1,9.64,5.21l1.25,30.93h0c16.68,17.6,28,41.64,30.6,68.95,1.45,15.42-.15,27.22-4.24,38.47-1.91,5.27-2.49,9.72,1,14.13,3.34,4.26,8.64,12.53,14.56,11.15,8.88-2.08,18.39-1.53,24.27,2.14A150.16,150.16,0,1,0,258.12,254a35.42,35.42,0,0,1-14.33-5.68C234.86,242.33,226.93,236.7,222.77,227.36Z"/>
                                    <path d="M208.92,117.38l14-1.71-.2-2.53-14.37,1.76c-.62-1.81-1.31-3.59-2.08-5.32l15.83-5-.5-3.17-16.84,5.83c-11.29-23.68-35.71-38.06-63.36-35.26A65,65,0,0,0,85.24,120l-16.47-2.49-.18,3.24,16.22,1.75a49.84,49.84,0,0,0-1.08,4.91l-14.14,1.13.12,2.53L83.5,130a51.14,51.14,0,0,0-.41,5.7l-12.6,5.15.62,3.13,12-5.62c0,1.68.1,2.57.25,4.26,3.34,35.46,33,42.48,68.35,38.9s63.15-16.4,59.81-51.86c-.16-1.7-.4-2.59-.69-4.24l13,2.82.3-2.86-13.63-2.59A48.79,48.79,0,0,0,208.92,117.38Z"/>
                                </g>
                            </svg>
                        </a>
                    </div>
                    <div class="sns-button speaker-deck">
                        <a href="https://speakerdeck.com/sakusaku3939" title="Speaker Deck" target="_blank">
                            <i class="fab fa-speaker-deck"></i>
                        </a>
                    </div>
                    <div class="sns-button mail">
                        <a onclick="openForm()" title="Mail"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                <div id="form">
                    <form id="form-data">
                        <input type="hidden" id="token" value="<?php echo generate_token() ?>">
                        <div class="group"><input type="text" id="name" name="name" required="required">
                            <label for="name">名前</label>
                        </div>
                        <div class="group"><input type="email" id="email" name="email" required="required">
                            <label for="email">メールアドレス</label>
                        </div>
                        <div class="group"><textarea id="message" name="message" required="required"></textarea>
                            <label for="message">ここに送る内容を入力</label>
                        </div>
                        <button value="submit">送信</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="posts-grid">
            <div class="posts-filter"><i class="fas fa-thumbtack"></i> ピックアップ記事</div>
            <?php draw_card_content($pick_post_data); ?>
            <div class="posts-filter">その他の記事</div>
            <?php draw_card_content($post_data); ?>
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
<script>
    setPostData(<?php echo $json?>)
    <?php
    if (isset($_GET["posts"])) {
        $current_parameter = $_GET["posts"];

        $merge_post_data = array_merge($pick_post_data, $post_data);
        $array_parameter = array_column($merge_post_data, 'parameter');
        $index = array_search($current_parameter, $array_parameter);
        $folder = $merge_post_data[$index]['date'];

        echo 'sessionStorage.setItem("posts", \'' . fetch_posts($folder, $current_parameter) . '\');';
    }
    ?>
</script>
</body>
</html>