function watchEpisode(id) {
    const element = document.getElementById(id);
    const request = new XMLHttpRequest();
    request.open('GET', '/show/watchEpisode?ep=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.className = "fas fa-eye-slash";
            }
        }
    };
    request.send();
}

function unwatchEp(id) {
    const element = document.getElementById(id);
    const request = new XMLHttpRequest();
    request.open('GET', '/show/unwatchEpisode?ep=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.className = "fas fa-eye";
            }
        }
    };
    request.send();
}

function checkEp(id) {
    const element = document.getElementById(id);
    if (element.className === "fas fa-eye") {
        watchEpisode(id);
    } else {
        unwatchEp(id);
    }
}

function watchAll(id) {
    const elements = document.getElementsByClassName("accordion")[0].getElementsByTagName("i");
    const request = new XMLHttpRequest();
    request.open('GET', '/show/watchAll?show=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                for (let i = 0; i < elements.length; i++) {
                    elements[i].className = "fas fa-eye-slash";
                }
            }
        }
    };
    request.send();
}

function unwatchAll(id) {
    const elements = document.getElementsByClassName("accordion")[0].getElementsByTagName("i");
    const request = new XMLHttpRequest();
    request.open('GET', '/show/unwatchAll?show=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                for (let i = 0; i < elements.length; i++) {
                    elements[i].className = "fas fa-eye";
                }
            }
        }
    };
    request.send();
}

function rating(rate, show) {
    const element = document.getElementById("userRating");
    let i = 1;
    element.innerHTML = "";
    for (; i < rate; i++) {
        element.innerHTML += '<span onmouseover="rating(' + i + ',' + show + ')" id="star' + i + '" class="fa fa-star" style="color: orange"></span>';
    }
    if (i === rate) {
        element.innerHTML += '<span onclick="rateShow(' + i + ',' + show + ')" id="star' + i + '" class="fa fa-star" style="color: orange"></span>';
        i++;
    }
    for (; i <= 10; i++) {
        element.innerHTML += '<span onmouseover="rating(' + i + ',' + show + ')" id="star' + i + '" class="fa fa-star "></span>';
    }
}

function rateShow(rate, show) {
    const request = new XMLHttpRequest();
    request.open('GET', '/show/updateRating?show=' + show + '&rate=' + rate);
    request.onreadystatechange = function () {
        console.log(request.readyState);
        console.log(request.status);
        if (request.readyState === 4) {
            if (request.status === 200) {
                console.log("OK");
                const element = document.getElementById("userRating");
                element.setAttribute("onmouseout", "rating(" + rate + "," + show + ")");
            }
        }
    };
    request.send();
}