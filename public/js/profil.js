function getMemberFollowedShow() {
    const element = document.getElementById("followedShows");
    const cards = element.getElementsByClassName("col-6 col-sm-3 col-md-4 col-lg-2 mt-20");
    for (let i = 0; i < cards.length; i++) {
        cards[i].style.display = "";
    }
}

function getMemberWatchingShow() {
    const element = document.getElementById("followedShows");
    const cards = element.getElementsByClassName("col-6 col-sm-3 col-md-4 col-lg-2 mt-20");
    for (let i = 0; i < cards.length; i++) {
        if (cards[i].getElementsByTagName("h6")[0].innerHTML.indexOf("en cours") > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}

function getMemberCompletedShow() {
    const element = document.getElementById("followedShows");
    const cards = element.getElementsByClassName("col-6 col-sm-3 col-md-4 col-lg-2 mt-20");
    for (let i = 0; i < cards.length; i++) {
        if (cards[i].getElementsByTagName("h6")[0].innerHTML.indexOf("terminée") > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}

function getMemberPlanToWatchShow() {
    const element = document.getElementById("followedShows");
    const cards = element.getElementsByClassName("col-6 col-sm-3 col-md-4 col-lg-2 mt-20");
    for (let i = 0; i < cards.length; i++) {
        if (cards[i].getElementsByTagName("h6")[0].innerHTML.indexOf("à voir") > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}

function filterFollowing() {
    const choice = document.getElementById("selectFollowedShows").value;
    switch (choice) {
        case "all":
            getMemberFollowedShow();
            break;
        case "watching":
            getMemberWatchingShow();
            break;
        case "completed":
            getMemberCompletedShow();
            break;
        case "plan":
            getMemberPlanToWatchShow();
            break;
        default:
            getMemberFollowedShow();
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