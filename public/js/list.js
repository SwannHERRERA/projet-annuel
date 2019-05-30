function removeShowList(idShow, idList) {
    const element = document.getElementById(idShow);
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                element.remove();
            }
        }
    };
    request.open('GET', '/show/removeShowList?list=' + idList + "&show=" + idShow);
    request.send();
}