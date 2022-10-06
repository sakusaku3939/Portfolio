let isFirst_load = true  //最初の読み込み時か

let isPost = false  //記事が表示されているか
let isPost_loading_now = false  //記事の読み込み中か
let isPost_click = false  //記事がクリックされたか(重複クリック防止)
setPost_click = (bool) => isPost_click = bool
getPost_click = () => isPost_click

let isMin = false  //横幅が1000px以下か
let post_data = {}  //記事データ(連想配列)を格納する変数

const FORM_HEIGHT = 362

custom_vh()

//読み込み完了時
Pace.on('done', function () {
    if (isFirst_load) {
        isFirst_load = false
        $('#loader').fadeIn(300)
        toggle(location.search !== '')
        if (location.search !== '') posts_loading()
        form_pos()
        setTimeout(function () {
            scroll_toggle()
        }, 300)
    } else {
        posts_loading()
    }
})

//戻る、進むのクリックリスナー
window.addEventListener('popstate', () => {
    toggle()
    if (isPost) {
        posts_loading()
    } else {
        const main = $("#main")
        main.css("backdrop-filter", "none")
        main.css("-webkit-backdrop-filter", "none")
        document.getElementById("main").style.filter = "none"
        document.getElementById("posts-wrapper").style.display = "none"
        document.getElementById("index").style.overflowY = "scroll"
    }
}, false)

//戻るボタンが押された場合
function back() {
    window.history.back()
}

//記事がクリックされた場合
function click_posts(posts, parameter) {
    if (!isPost_click) {
        isPost_click = true
        sessionStorage.setItem("posts", posts)
        history.pushState(null, null, "?posts=" + parameter)
        posts_before_loading()
    }
}

// function click_posts(date, parameter) {
//     if (!isPost_click) {
//         isPost_click = true
//         sessionStorage.setItem("src", "posts/" + date + "/" + parameter + ".html")
//         history.pushState(null, null, "?posts=" + parameter)
//         posts_before_loading()
//     }
// }

//ウィンドウの横幅変更リスナー
let resizeTimer;
let lastInnerWidth = window.innerWidth;
let lastInnerHeight = window.innerHeight;
window.addEventListener('resize', function () {
    custom_vh()
    //横幅変更
    if (lastInnerWidth !== window.innerWidth) {
        lastInnerWidth = window.innerWidth;
        form_pos()
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
            form_height()
        }
    }
});

//スクロールリスナー
document.getElementById('posts-wrapper').addEventListener("scroll", () => {
    if (isPost) {
        // const elm = document.getElementById("iframe-posts")
        // elm.style.height = 72 + elm.contentWindow.document.body.scrollHeight + "px"
    }
});

//ドキュメント全体のクリックリスナー
$(document).on('click touchend', function (e) {
    if (!e.target.closest('#form')) form_off()
    share_off()
});

//記事表示・非表示の切り替え
function toggle(isToggle = true) {
    if (isToggle) isPost = !isPost

    // const iframe = document.getElementById("iframe-posts")
    const main = document.getElementById("main")
    // iframe.contentWindow.location.replace(isPost ? sessionStorage.getItem('src') : "hold.html")
    if (isPost) {
        const content = remove_posts_content(document.getElementById('posts-content'))
        content.insertAdjacentHTML('afterbegin', decodeURIComponent(atob(sessionStorage.getItem('posts'))));

        $('#speaker-deck-script').remove()
        const script = document.createElement('script')
        script.id = "speaker-deck-script"
        script.src = "//speakerdeck.com/assets/embed.js"
        document.body.appendChild(script)
    }
    main.style.pointerEvents = isPost ? "none" : "auto"
    if (!isPost) main.style.position = "relative"

    share()
    scroll_toggle()
}

function remove_posts_content(element) {
    const clone = element.cloneNode(false);
    element.parentNode.replaceChild(clone, element);
    return clone
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

//フォームのON/OFF
let isForm = false
let form_on_animation = false
let form_off_animation = false

function form_on() {
    if (!isForm && !form_off_animation) {
        $('#form').addClass('.form_show').fadeIn(200);
        form_pos()

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

//メールフォームの座標をセット
function form_pos() {
    const pos = $('.mail').offset()
    $('#form').css('left', (pos.left - 118) + 'px')
}

//メールフォームのheightをセット
function form_height() {
    const height = window.innerHeight / 2 - 100
    $('#form').css('height', (height < FORM_HEIGHT ? height : FORM_HEIGHT) + 'px')
}

//記事のシェア用リンク設定
function share() {
    const twitter = document.getElementById("twitter")
    twitter.href = "https://twitter.com/share?url=" + location.href

    const facebook = document.getElementById("facebook")
    facebook.href = "https://www.facebook.com/sharer/sharer.php?u=" + location.href

    const pocket = document.getElementById("pocket")
    pocket.href = "https://getpocket.com/edit?url=" + location.href
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
        }, 200);
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

    pre.style["webkitUserSelect"] = 'auto';
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
    toggle()
    isPost_loading_now = true
    // const elm = document.getElementById("iframe-posts")
    // elm.style.height = "100vh"
}

//記事ページの設定（読み込み後）
function posts_loading() {
    if (isPost) {
        isPost_loading_now = false
        const index = document.getElementById("index")
        index.style.overflowY = "hidden"

        const main = $("#posts-wrapper")
        main.css("backdrop-filter", "blur(16px)")
        main.css("-webkit-backdrop-filter", "blur(16px)")

        const speakerDeck = $('.speaker-deck')
        speakerDeck.ready(function () {
            const radio = 0.5625
            $('.speakerdeck-iframe').width(
                speakerDeck.width()
            ).height(
                speakerDeck.width() * radio
            )
        })

        const posts_wrapper = document.getElementById("posts-wrapper")
        posts_wrapper.style.display = "inline"
        posts_wrapper.scrollTo(0, 0)

        setPost_click(false)

        // $("#iframe-posts").on('load', function () {
        //     $(this).contents().on('click touchend', share_off)
        // })
        // document.getElementById("iframe-posts").style.height = "120vh"

        // if (navigator.userAgent.match(/(iPhone|iPad|iPod)/i)) {
        //     const iframe_posts = document.getElementById("iframe-posts")
        //     iframe_posts.style.marginBottom = '96px'
        // }
    }
}

//スクロール表示・非表示の切り替え
function scroll_toggle() {
    const index = document.getElementById("index")
    if (window.matchMedia('(max-width: 1000px)').matches) {
        isMin = true
        if (!isForm) index.style.overflowY = "scroll"
        $('#form').css('height', FORM_HEIGHT + 'px')
    } else {
        isMin = false
        form_height()
    }
}

//iOS対策をしたカスタムプロパティをセット
function custom_vh() {
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}