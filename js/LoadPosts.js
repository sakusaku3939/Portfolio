window.addEventListener('popstate', toggle, false)

//読み込まれた場合
$(window).on('load', function () {
    toggle(location.search === '?posts=test')
    form_pos()
})

//戻るボタンが押された場合
function back() {
    window.history.back()
}

//ウィンドウの横幅変更リスナー
let resizeTimer;
let lastInnerWidth = window.innerWidth;
let lastInnerHeight = window.innerHeight;
window.addEventListener('resize', function () {
    //横幅変更
    if (lastInnerWidth !== window.innerWidth) {
        lastInnerWidth = window.innerWidth;
        form_pos()
        if (!resizeTimer) {
            clearTimeout(resizeTimer);
        }
        resizeTimer = setTimeout(function () {
            scroll_toggle()
        }, 300);
    }
    //縦幅変更
    if (lastInnerHeight !== window.innerHeight) {
        lastInnerHeight = window.innerHeight;
        if (!isMedia_min) {
            $('#form').css('height', (window.innerHeight / 2 - 150) + 'px')
        }
    }
});

//記事一覧のクリックリスナー
$('#iframe_content').on('load', function () {
    const iframe = $('#iframe_content').contents()
    iframe.find(".card").click(function () {
        toggle()
        history.pushState(null, null, "?posts=test")
    });
    iframe_height()
});

//シェアメニューのクリックリスナー
let isShare_menu = false
$(document).on('click touchend', function () {
    share_off()
});

//記事表示・非表示の切り替え
function toggle(isToggle = true) {
    const element = document.querySelector("#hide")
    const posts = document.getElementById("posts")

    if (isToggle) element.classList.toggle('is-hide')

    scroll_toggle()
    share()

    if (element.classList.contains('is-hide')) {
        const iframe = document.getElementById("iframe-posts")
        const index = document.getElementById("index")
        $("#iframe-posts").ready(function () {
            posts.style.display = "inline"
            iframe.src = "posts/test.html"
            index.style.overflowY = "scroll"
        })
    } else {
        posts.style.display = "none"
    }
}

//メールフォームの座標をセット
function form_pos() {
    const pos = $('.mail').offset()
    $('#form').css('left', (pos.left - 128) + 'px')
}

//記事のシェア用リンク設定
function share() {
    const twitter = document.getElementById("twitter")
    twitter.href = "https://twitter.com/share?url=" + location.href

    const facebook = document.getElementById("facebook")
    facebook.href = "https://www.facebook.com/sharer/sharer.php?u=" + location.href

    const pocket = document.getElementById("pocket")
    pocket.href = "http://getpocket.com/edit?url=" + location.href
}

//シェアメニューをオン
let on_animation = false

function share_on() {
    if (!isShare_menu && !off_animation) {
        $('#share_menu').addClass('.menu_show').fadeIn();
        on_animation = true
        setTimeout(function () {
            isShare_menu = true
            on_animation = false
        }, 100);
    }
}

//シェアメニューをオフ
let off_animation = false

function share_off() {
    if (isShare_menu && !on_animation) {
        $('#share_menu').fadeOut();
        off_animation = true
        setTimeout(function () {
            isShare_menu = false
            off_animation = false
        }, 100);
    }
}

//クリップボードにコピー
function copy_clipboard() {
    const tmp = document.createElement("div");
    const pre = document.createElement('pre');

    pre.style.webkitUserSelect = 'auto';
    pre.style.userSelect = 'auto';
    tmp.appendChild(pre).textContent = location.href;

    const s = tmp.style;
    s.position = 'fixed';
    s.right = '200%';

    document.body.appendChild(tmp);
    document.getSelection().selectAllChildren(tmp);
    document.execCommand("copy");
    document.body.removeChild(tmp);

    swal({
        text: "リンクがクリップボードにコピーされました",
        icon: "success",
        button: false,
    });
}

//記事ページの設定（読み込み前）
function posts_before_loading() {
    const elm = document.getElementById("iframe-posts");
    elm.style.height = "100vh";
}

//記事ページの設定（読み込み後）
function posts_loading() {
    $("#iframe-posts").contents().on('click touchend', share_off);

    const elm = document.getElementById("iframe-posts");
    elm.style.height = 60 + elm.contentWindow.document.body.scrollHeight + "px";
}

//スクロール表示・非表示の切り替え
let isMedia_min = false
function scroll_toggle() {
    const index = document.getElementById("index")
    const element = document.querySelector("#hide")
    if (window.matchMedia('(max-width: 1000px)').matches || element.classList.contains('is-hide')) {
        isMedia_min = true
        index.style.overflowY = "scroll"
        const hide = document.querySelector("#hide")
        hide.classList.contains('is-hide') ? posts_before_loading() : iframe_height()
        $('#form').css('height', '420px')
    } else {
        isMedia_min = false
        index.style.overflowY = "hidden"
        const iframe = document.getElementById("iframe_content")
        iframe.style.height = "100vh"
        $('#form').css('height', (window.innerHeight / 2 - 150) + 'px')
    }
}

//記事一覧のheightを設定
function iframe_height() {
    const elm = document.getElementById("iframe_content");
    if (window.matchMedia('(max-width: 1000px)').matches) {
        elm.style.height = 20 + elm.contentWindow.document.body.scrollHeight + "px";
    }
}