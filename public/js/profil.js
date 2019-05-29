function getMemberFollowedShow(pseudo) {
    const element = document.getElementById("followedShows");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.innerHTML = request.responseText;
            }
        }
    };
    request.open('GET', '/profil/getMemberFollowedShow?pseudo=' + pseudo);
    request.send();
}

function getMemberWatchingShow(pseudo) {
    const element = document.getElementById("followedShows");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.innerHTML = request.responseText;
            }
        }
    };
    request.open('GET', '/profil/getMemberWatchingShow?pseudo=' + pseudo);
    request.send();
}

function getMemberCompletedShow(pseudo) {
    const element = document.getElementById("followedShows");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.innerHTML = request.responseText;
            }
        }
    };
    request.open('GET', '/profil/getMemberCompletedShow?pseudo=' + pseudo);
    request.send();
}

function getMemberPlanToWatchShow(pseudo) {
    const element = document.getElementById("followedShows");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.innerHTML = request.responseText;
            }
        }
    };
    request.open('GET', '/profil/getMemberPlanToWatchShow?pseudo=' + pseudo);
    request.send();
}

function filterFollowing(pseudo) {
    const choice = document.getElementById("selectFollowedShows").value;
    console.log(choice);
    switch (choice) {
        case "all":
            getMemberFollowedShow(pseudo);
            break;
        case "watching":
            getMemberWatchingShow(pseudo);
            break;
        case "completed":
            getMemberCompletedShow(pseudo);
            break;
        case "plan":
            getMemberPlanToWatchShow(pseudo);
            break;
        default:
            getMemberFollowedShow(pseudo);
            break;
    }
}

function searchFollowing() {
    const input = document.getElementById("searchFollowing").value.toUpperCase();
    const list = document.getElementById("followedShows").getElementsByClassName("col-6 col-sm-3 col-md-4 col-lg-2 mt-20");
    for (let i = 0; i < list.length; i++) {
        if (list[i].id.toUpperCase().indexOf(input) > -1) {
            list[i].style.display = "";
        } else {
            list[i].style.display = "none";
        }
    }
}