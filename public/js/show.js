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

function submitComment(idShow) {
    const comment = document.getElementById("commentWrite");
    if (comment.value.length > 0) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.status === 200) {
                    const comments = document.getElementById("userComments");
                    comments.innerHTML = request.responseText + comments.innerHTML;
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
}

function deleteComment(id) {
    if (confirm("Voulez vous vraiment supprimer ce commentaire ?")) {
        const element = document.getElementById(id);
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
}

function checkLike(idComment) {
    const element = document.getElementById("thumb" + idComment);
    if (element.className === "fas fa-thumbs-up") {
        unlikeComment(idComment);
    } else {
        likeComment(idComment);
    }
}

function likeComment(idComment) {
    const request = new XMLHttpRequest();
    request.open('GET', '/show/likeComment?comment=' + idComment);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                const element = document.getElementById("thumb" + idComment);
                const likes = document.getElementById("nblikes" + idComment);
                likes.innerHTML++;
                element.className = "fas fa-thumbs-up";
            }
        }
    };
    request.send();
}

function unlikeComment(idComment) {
    const request = new XMLHttpRequest();
    request.open('GET', '/show/unlikeComment?comment=' + idComment);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                const element = document.getElementById("thumb" + idComment);
                const likes = document.getElementById("nblikes" + idComment);
                likes.innerHTML--;
                element.className = "far fa-thumbs-up";
            }
        }
    };
    request.send();
}

function addList(idShow) {
    const name = document.getElementById("nameListNew");
    const description = document.getElementById("descriptionListNew");
    const visibility = document.getElementById("visibilityNewList");
    if (name.value.length > 0 && description.value.length > 0) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.status === 200) {
                    const element = document.getElementById("lists");
                    let list = '<tr id="list' + request.responseText + '"><th scope="row">' + name.value + '</th>' +
                        '<td>' + description.value.substr(0, 20) + (description.value.length > 20 ? '...' : '') + '</td>' +
                        '<td>' + visibility.value + '</td><td><button onclick="checkList(' + idShow + ',' + request.responseText + ')" class="btn btn-success">' +
                        '<i id="checkList' + request.responseText + '" class="fas fa-plus"></i></button>' +
                        '<button onclick="removeList(' + request.responseText + ')" class="btn btn-warning"><i class="fas fa-trash"></i></button></td></tr>';
                    element.innerHTML += list;
                }
            }
        };
        request.open('POST', '/show/createList');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        request.send(
            'name=' + name.value +
            '&description=' + description.value +
            '&visibility=' + visibility.value
        );
    }
}

function removeList(idList) {
    const request = new XMLHttpRequest();
    request.open('GET', '/show/deleteList?list=' + idList);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                const list = document.getElementById("list" + idList);
                list.remove();
            }
        }
    };
    request.send();
}

function addShowToList(idShow, idList) {
    const request = new XMLHttpRequest();
    request.open('GET', '/show/addShowList?list=' + idList + '&show=' + idShow);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                let element = document.getElementById("checkList" + idList);
                element.className = "fas fa-check";
            }
        }
    };
    request.send();
}

function removeShowList(idShow, idList) {
    let element = document.getElementById("checkList" + idList);
    element.className = "fas fa-plus";
    const request = new XMLHttpRequest();
    request.open('GET', '/show/removeShowList?list=' + idList + '&show=' + idShow);
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                let element = document.getElementById("checkList" + idList);
                element.className = "fas fa-plus";
            }
        }
    };
    request.send();
}

function checkList(idShow, idList) {
    let element = document.getElementById("checkList" + idList);
    if (element.className === "fas fa-check") {
        removeShowList(idShow, idList);
    } else {
        addShowToList(idShow, idList)
    }
}


