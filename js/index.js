window.addEventListener('popstate', () => {
    toggle(true)
}, false)

let isPost = false  //記事が表示されているか
let isPost_loading_now = false  //記事の読み込み中か
let isPost_click = false  //記事がクリックされたか(重複クリック防止)
setPost_click = (bool) => isPost_click = bool
getPost_click = () => isPost_click

let isMin = false  //横幅が1000px以下か
let post_data = {}  //記事データ(連想配列)を格納する変数

//準備完了時
$(document).ready(function () {
    sideBar_height()
})

//読み込み完了時
Pace.on('done', function () {
    $('#loader').fadeIn(300)
    toggle(true, location.search !== '')
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
        sideBar_height()
        if (!resizeTimer) {
            clearTimeout(resizeTimer);
        }
        resizeTimer = setTimeout(function () {
            scroll_toggle()
        }, 500);
    }
    //縦幅変更
    if (lastInnerHeight !== window.innerHeight) {
        lastInnerHeight = window.innerHeight;
        if (!isMin) {
            $('#form').css('height', (window.innerHeight / 2 - 170) + 'px')
            sideBar_height()
        }
    }
});

//記事一覧の読み込み時
$('#iframe-list').on('load', function () {
    const iframe = $('#iframe-list').contents()
    iframe.on('click touchend', form_off);
});

//ドキュメント全体のクリックリスナー
$(document).on('click touchend', function () {
    isPost ? share_off() : form_off()
});

//記事表示・非表示の切り替え
function toggle(isPath = false, isToggle = true) {
    const iframe = document.getElementById("iframe-posts")
    let addPath = isPath ? "./" : "../"

    if (isToggle) isPost = !isPost
    if (isPost) {
        scrollTo(0, 0)
        iframe.contentWindow.location.replace(addPath + sessionStorage.getItem('src'))
    } else {
        const main = document.getElementById("main")
        const posts = document.getElementById("posts")
        main.style.display = "inline"
        posts.style.display = "none"
        iframe.contentWindow.location.replace(addPath + "hold.html")
    }
    share()
    scroll_toggle()
}

//外部リンクから記事ページに来た場合、phpから直接記事データを格納する
function setPost_data(data) {
    post_data = data
    let _getIndex = function (value, arr, prop) {
        for (let i = 0; i < arr.length; i++) {
            if (arr[i][prop] === value) {
                return i;
            }
        }
        return -1;
    }

    let index = _getIndex(location.search.substr(7), post_data, 'parameter')
    if (index !== -1) {
        sessionStorage.setItem("src", "posts/" + post_data[index]['date'] + "/" + post_data[index]['parameter'] + ".html")
    }
}

//メールフォームの座標をセット
function form_pos() {
    const pos = $('.mail').offset()
    if (isMin) {
        $('#form').css('left', (pos.left - 118) + 'px')
    } else {
        $('#form').css('left', (pos.left - 125) + 'px')
    }
}

//フォームのON/OFF
let isForm = false
let form_on_animation = false
let form_off_animation = false

function form_on() {
    if (!isForm && !form_off_animation) {
        $('#form').addClass('.form_show').fadeIn(200);
        document.getElementById("index").style.overflowY = "hidden"
        form_pos()
        document.documentElement.scrollTop = 0

        form_on_animation = true
        setTimeout(function () {
            isForm = true
            form_on_animation = false
        }, 100);
    }
}

function form_off() {
    if (isForm && !form_on_animation) {
        $('#form').fadeOut(200);
        form_pos()

        form_off_animation = true
        setTimeout(function () {
            isForm = false
            form_off_animation = false
            scroll_toggle()
        }, 100);
    }
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

//シェアメニューのON/OFF
let isShare_menu = false
let share_on_animation = false
let share_off_animation = false

function share_on() {
    if (!isShare_menu && !share_off_animation) {
        $('#share_menu').addClass('.menu_show').fadeIn();

        share_on_animation = true
        setTimeout(function () {
            isShare_menu = true
            share_on_animation = false
        }, 100);
    }
}

function share_off() {
    if (isShare_menu && !share_on_animation) {
        $('#share_menu').fadeOut();

        share_off_animation = true
        setTimeout(function () {
            isShare_menu = false
            share_off_animation = false
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
    isPost_loading_now = true
    const elm = document.getElementById("iframe-posts")
    elm.style.height = "100vh"
}

//記事ページの設定（読み込み後）
function posts_loading() {
    if (isPost) {
        isPost_loading_now = false
        const main = document.getElementById("main")
        const posts = document.getElementById("posts")
        main.style.display = "none"
        posts.style.display = "inline"
        setPost_click(false)

        $("#iframe-posts").contents().on('click touchend', share_off)

        const elm = document.getElementById("iframe-posts")
        elm.style.height = 60 + elm.contentWindow.document.body.scrollHeight + "px"

        const index = document.getElementById("index")
        index.style.overflowY = "scroll"
    }
}

//スクロール表示・非表示の切り替え
function scroll_toggle() {
    const index = document.getElementById("index")
    if (window.matchMedia('(max-width: 1000px)').matches) {
        isMin = true
        if (!isForm) index.style.overflowY = "scroll"
        $('#form').css('height', '425px')
        if (!isPost) iframe_height()
    } else {
        isMin = false
        const iframe = document.getElementById("iframe-list")
        iframe.style.height = "100vh"
        $('#form').css('height', (window.innerHeight / 2 - 160) + 'px')
        if (!isPost) index.style.overflowY = "hidden"
    }
}

//記事一覧のiframeのheightを設定
function iframe_height() {
    const elm = document.getElementById("iframe-list");
    if (window.matchMedia('(max-width: 1000px)').matches) {
        elm.style.height = 10 + elm.contentWindow.document.body.scrollHeight + "px";
    }
}

// ブラウザサイズを自動で取得してiOS対策
function sideBar_height() {
    const isIOS = /[ \(]iP/.test(navigator.userAgent)
    if (isIOS) {
        if (!window.matchMedia('(max-width: 1000px)').matches) {
            const hSize = $(window).height()
            $(".side-bar").height(hSize)
        } else if (!window.matchMedia('(max-width: 500px)').matches) {
            $(".side-bar").height('40vh')
        } else {
            $(".side-bar").height('30vh')
        }
    }
}