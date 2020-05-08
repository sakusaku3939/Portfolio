document.addEventListener("DOMContentLoaded", onLoad);

function onLoad() {
    toggle(location.search !== '')
}

function toggle(isToggle = true) {
    const element = document.querySelector("#hide")
    if (isToggle) {
        element.classList.toggle('is-hide');
    }

    if (element.classList.contains('is-hide')) {
        const iframe = document.createElement("iframe")
        iframe.setAttribute("id", "posts")
        iframe.setAttribute("style", "" +
            "background-color: #FAFAFA;" +
            "    border: none;" +
            "    border-radius: 5px;" +
            "    position: absolute;" +
            "    top: 55%;" +
            "    left: 50%;" +
            "    transform: translate(-50%, -50%);" +
            "    -webkit-transform: translate(-50%, -50%);" +
            "    -ms-transform: translate(-50%, -50%);" +
            "    width: 50vw;" +
            "    height: 100vh;"
        )
        iframe.setAttribute("src", "posts/test.html")
        document.body.appendChild(iframe);
    } else {
        const iframe = document.getElementById("posts");
        if (iframe != null) {
            iframe.remove()
        }
    }
}

function posts() {
    toggle()
    history.pushState(null, null, "?p=test");
}

window.addEventListener('popstate', toggle, false)