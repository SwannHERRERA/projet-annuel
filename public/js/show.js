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

function enableNotification(id) {
    const element = document.getElementById("notificationCheck");
    const request = new XMLHttpRequest();
    request.open('GET', '/show/enableNotification?show=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.className = "fas fa-bell-slash";
            }
        }
    };
    request.send();
}

function disableNotification(id) {
    const element = document.getElementById("notificationCheck");
    const request = new XMLHttpRequest();
    request.open('GET', '/show/disableNotification?show=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.className = "far fa-bell";
            }
        }
    };
    request.send();
}

function checkNotification(id) {
    const element = document.getElementById("notificationCheck");

    if (element.className === "far fa-bell") {
        enableNotification(id);
    } else {
        disableNotification(id);
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
        if (request.readyState === 4) {
            if (request.status === 200) {
                const element = document.getElementById("userRating");
                element.setAttribute("onmouseout", "rating(" + rate + "," + show + ")");
            }
        }
    };
    request.send();
}

function statusShow(status, show) {
    const element = document.getElementById("userStatus");
    let values = ['a voir', 'en cours', 'termine'];
    let valuesName = ['À voir', 'En cours', 'Terminé'];
    let content = '<select onchange="changeStatus(' + show + ')" class="form-control" id="userStatusSelect">';
    for (let i = 0; i < 3; i++) {
        content += '<option value="' + values[i] + '" ';
        if (status === values[i]) {
            content += 'selected';
        }
        content += '>' + valuesName[i] + '</option>';
    }
    content += '</select>';
    element.innerHTML = content;
}

function changeStatus(show) {
    let element = document.getElementById("userStatusSelect");
    const request = new XMLHttpRequest();
    request.open('GET', '/show/updateStatus?show=' + show + '&status=' + element.value);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                if (element.value === 'termine') {
                    watchAll(show);
                } else if (element.value === 'a voir') {
                    unwatchAll(show);
                }
            }
        }
    };
    request.send();
}

function submitComment(idShow, userPhoto, username) {
    const comment = document.getElementById("commentWrite");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                const comments = document.getElementById("userComments");
                let newComment = '<div class="row mb-10"><div class="col-sm-2"><img src="' + userPhoto + '"' +
                    ' class="img-thumbnail" alt="photo profile"></div><div class="col-sm-9"><div class="card"><div class="card-header">' +
                    '<strong>' + username + '</strong> <span class="text-muted">commenté le ' + new Date().getDate() + '</span>' +
                    '</div><div class="card-body">' + comment.value + '</div></div></div></div>';
                comments.innerHTML = newComment + comments.innerHTML;
            }
        }
    };
    request.open('POST', '/show/submitComment');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send(
        'show=' + idShow +
        '&comment=' + comment.value
    );
}

function deleteComment(id) {
    let element = document.getElementById(id);
    const request = new XMLHttpRequest();
    request.open('GET', '/show/deleteComment?comment=' + id);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.remove();
            }
        }
    };
    request.send();
}

