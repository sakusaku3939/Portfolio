document.addEventListener("DOMContentLoaded", onLoad);
window.addEventListener('popstate', toggle, false)

//記事カードのクリックリスナー
$('#iframe_content').load(function () {
    const iframe = $('#iframe_content').contents()
    iframe.find(".card").click(function () {
        toggle()
        history.pushState(null, null, "?posts=test")
    });
});

function onLoad() {
    toggle(location.search === '?posts=test')
}

function toggle(isToggle = true) {
    const element = document.querySelector("#hide")
    const posts = document.getElementById("posts")
    const iframe = document.getElementById("iframe-posts")
    const index = document.getElementById("index")

    if (isToggle) element.classList.toggle('is-hide')
    if (element.classList.contains('is-hide')) {
        posts.style.display = "inline"
        iframe.src = "posts/test.html"
        index.style.overflowY = "scroll"
    } else {
        posts.style.display = "none"
        index.style.overflowY = "hidden"
    }
}

function adjust_frame_css() {
    if (document.getElementById("iframe-posts")) {
        const myF = document.getElementById("iframe-posts");
        const myC = myF.contentWindow.document.documentElement;
        let myH;
        if (document) {
            myH = myC.scrollHeight;
        } else {
            myH = myC.offsetHeight;
        }
        myF.style.height = myH + "px";
    }
}

function back() {
    window.history.back()
}