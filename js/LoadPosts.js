// document.addEventListener("DOMContentLoaded", onLoad);
window.addEventListener('popstate', toggle, false)

//ウィンドウの横幅変更後リスナー
let resizeTimer;
let lastInnerWidth = window.innerWidth;
window.addEventListener('resize', function (event) {
    if (lastInnerWidth !== window.innerWidth) {
        lastInnerWidth = window.innerWidth;
        if (!resizeTimer) {
            clearTimeout(resizeTimer);
        }
        resizeTimer = setTimeout(function () {
            scroll_toggle()
        }, 300);
    }
});

//記事一覧のクリックリスナー
$('#iframe_content').load(function () {
    const iframe = $('#iframe_content').contents()
    iframe.find(".card").click(function () {
        toggle()
        history.pushState(null, null, "?posts=test")
    });
    iframe_height()
});

//読み込まれた場合
$(window).load(function () {
    toggle(location.search === '?posts=test')
})

//戻るボタンが押された場合
function back() {
    toggle(false)
    window.history.back()
}

//記事表示・非表示の切り替え
function toggle(isToggle = true) {
    const element = document.querySelector("#hide")
    const posts = document.getElementById("posts")

    if (isToggle) element.classList.toggle('is-hide')
    scroll_toggle()
    if (element.classList.contains('is-hide')) {
        const iframe = document.getElementById("iframe-posts")
        const index = document.getElementById("index")
        posts.style.display = "inline"
        iframe.src = "posts/test.html"
        index.style.overflowY = "scroll"
    } else {
        posts.style.display = "none"
    }
}

//記事ページのheightを設定
function posts_height() {
    if (document.getElementById("iframe-posts")) {
        const elm = document.getElementById("iframe-posts");
        elm.style.height = 60 + elm.contentWindow.document.body.scrollHeight + "px";
    }
}

//スクロール表示・非表示の切り替え
function scroll_toggle() {
    const index = document.getElementById("index")
    if (window.matchMedia('(max-width: 1000px)').matches) {
        index.style.overflowY = "scroll"
        const hide = document.querySelector("#hide")
        hide.classList.contains('is-hide') ? posts_height() : iframe_height()
    } else {
        index.style.overflowY = "hidden"
        const iframe = document.getElementById("iframe_content")
        iframe.style.height = "100vh"
    }
}

//記事一覧のheightを設定
function iframe_height() {
    const elm = document.getElementById("iframe_content");
    if (window.matchMedia('(max-width: 1000px)').matches) {
        elm.style.height = 20 + elm.contentWindow.document.body.scrollHeight + "px";
    }
}