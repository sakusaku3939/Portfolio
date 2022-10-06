let posts = false
let postData = {}

$('#loader').fadeIn(300)
custom_vh()

//読み込み完了時
$(function () {
    const hasParameter = location.search !== ''
    if (hasParameter) openPosts()

    const ref = "css/animation.css"
    const style = '<link rel="stylesheet" href=' + ref + '>';
    $('head link:last').after(style);
})

//戻る、進むのクリックリスナー
window.addEventListener('popstate', () => {
    posts = !posts
    posts ? openPosts() : closePosts()
}, false)

//ウィンドウの横幅変更リスナー
let lastInnerWidth = window.innerWidth;
window.addEventListener('resize', function () {
    custom_vh()
    if (lastInnerWidth !== window.innerWidth) {
        lastInnerWidth = window.innerWidth;
        updateFormPos()
    }
});

//記事がクリックされた場合
function clickPosts(posts, parameter) {
    sessionStorage.setItem("posts", posts)
    history.pushState(null, null, "?posts=" + parameter)
    openPosts()
}

//iOS対策をしたカスタムプロパティをセット
function custom_vh() {
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

//ドキュメント全体のクリックリスナー
$(document).on('click touchend', function (e) {
    if (!e.target.closest('#form')) closeForm()
    closeShareMenu()
});

function openPosts() {
    posts = true

    //PHPから取得した記事データを表示
    const content = $("#posts-content")
    content.empty()
    content.append(decodeURIComponent(atob(sessionStorage.getItem('posts'))))

    //Speaker Deckのスクリプト読み込み
    $('#speaker-deck-script').remove()
    const script = document.createElement('script')
    script.id = "speaker-deck-script"
    script.src = "//speakerdeck.com/assets/embed.js"
    document.body.appendChild(script)

    //Speaker Deckのスライドサイズを設定
    const speakerDeck = $('.speaker-deck')
    speakerDeck.ready(function () {
        const radio = 0.5625
        const iframe = $('.speakerdeck-iframe')
        iframe.width(speakerDeck.width())
        iframe.height(speakerDeck.width() * radio)
    })

    const postsWrapper = $("#posts-wrapper")
    postsWrapper.css("backdrop-filter", "blur(16px)")
    postsWrapper.css("-webkit-backdrop-filter", "blur(16px)")
    postsWrapper.css("display", "inline")

    $("#index").css("overflow-y", "hidden")
    document.getElementById("posts-wrapper").scrollTo(0, 0)

    // if (navigator.userAgent.match(/(iPhone|iPad|iPod)/i)) {
    //     const iframe_posts = document.getElementById("iframe-posts")
    //     iframe_posts.style.marginBottom = '96px'
    // }

    setShareLink()
}

function closePosts() {
    posts = false

    const postsWrapper = $("#posts-wrapper")
    postsWrapper.css("backdrop-filter", "none")
    postsWrapper.css("-webkit-backdrop-filter", "none")
    postsWrapper.css("display", "none")

    $("#index").css("overflow-y", "scroll")
}

//外部リンクから記事ページに来た場合、phpから直接記事データを格納する
function setPostData(data) {
    postData = data
    let _getIndex = function (value, arr, prop) {
        for (let i = 0; i < arr.length; i++) {
            if (arr[i][prop] === value) {
                return i;
            }
        }
        return -1;
    }

    let index = _getIndex(location.search.substr(7), postData, 'parameter')
    if (index !== -1) {
        sessionStorage.setItem("src", "posts/" + postData[index]['date'] + "/" + postData[index]['parameter'] + ".html")
    }
}

//フォームのON/OFF
let form = false
let formOpenAnimation = false
let formCloseAnimation = false

function openForm() {
    if (!form && !formCloseAnimation) {
        $('#form').addClass('.form_show').fadeIn(200);
        updateFormPos()

        formOpenAnimation = true
        setTimeout(function () {
            form = true
            formOpenAnimation = false
        }, 100);
    }
}

function closeForm() {
    if (form && !formOpenAnimation) {
        $('#form').fadeOut(200);
        updateFormPos()

        formCloseAnimation = true
        setTimeout(function () {
            form = false
            formCloseAnimation = false
        }, 100);
    }
}

//メールフォームの座標をセット
function updateFormPos() {
    const pos = $('.mail').offset()
    $('#form').css('left', (pos.left - 118) + 'px')
}

//シェアメニューのON/OFF
let shareMenu = false
let shareOpenAnimation = false
let shareCloseAnimation = false

function openShareMenu() {
    if (!shareMenu && !shareCloseAnimation) {
        $('#share_menu').addClass('.menu_show').fadeIn();

        shareOpenAnimation = true
        setTimeout(function () {
            shareMenu = true
            shareOpenAnimation = false
        }, 200);
    }
}

function closeShareMenu() {
    if (shareMenu && !shareOpenAnimation) {
        $('#share_menu').fadeOut();

        shareCloseAnimation = true
        setTimeout(function () {
            shareMenu = false
            shareCloseAnimation = false
        }, 100);
    }
}

//記事のシェア用リンク設定
function setShareLink() {
    const twitter = document.getElementById("twitter")
    twitter.href = "https://twitter.com/share?url=" + location.href

    const facebook = document.getElementById("facebook")
    facebook.href = "https://www.facebook.com/sharer/sharer.php?u=" + location.href

    const pocket = document.getElementById("pocket")
    pocket.href = "https://getpocket.com/edit?url=" + location.href
}

//クリップボードにコピー
function copyClipboard() {
    const tmp = document.createElement("div");
    const pre = document.createElement('pre');

    pre.style["webkitUserSelect"] = 'auto';
    pre.style.userSelect = 'auto';
    tmp.appendChild(pre).textContent = location.href;

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