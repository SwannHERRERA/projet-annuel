let canvas, ctx, flag = false,
    prevX = 0,
    currX = 0,
    prevY = 0,
    currY = 0,
    dot_flag = false;
let canvasOffset;

var x = "white",
    y = 2;

$('#canvas').on('shown.bs.modal', function (e) {
    init();
})

function init() {
    canvas = document.getElementById('can');
    updateCanvasOffset();
    ctx = canvas.getContext("2d");
    w = canvas.width;
    h = canvas.height;
    window.onresize = updateCanvasOffset;

    canvas.addEventListener("mousemove", function (e) {
        findxy('move', e)
    }, false);
    canvas.addEventListener("mousedown", function (e) {
        findxy('down', e)
    }, false);
    canvas.addEventListener("mouseup", function (e) {
        findxy('up', e)
    }, false);
    canvas.addEventListener("mouseout", function (e) {
        findxy('out', e)
    }, false);
}

function updateCanvasOffset() {
    canvasOffset = canvas.getBoundingClientRect();
}

function color(obj) {
    switch (obj.id) {
        case "green":
            x = "green";
            break;
        case "blue":
            x = "blue";
            break;
        case "red":
            x = "red";
            break;
        case "yellow":
            x = "yellow";
            break;
        case "orange":
            x = "orange";
            break;
        case "black":
            x = "black";
            break;
        case "white":
            x = "white";
            break;
    }
    if (x == "black") y = 14;
    else y = 2;

}

function draw() {
    ctx.beginPath();
    ctx.moveTo(prevX, prevY);
    ctx.lineTo(currX, currY);
    ctx.strokeStyle = x;
    ctx.lineWidth = y;
    ctx.stroke();
    ctx.closePath();
}

function erase() {
    var m = confirm("Want to clear");
    if (m) {
        ctx.clearRect(0, 0, w, h);
    }
}

function save() {
    document.getElementById("canvasimg").style.border = "2px solid";
    var dataURL = canvas.toDataURL('image/jpeg');
    document.getElementById("canvasimg").src = dataURL;
    document.getElementById("canvasimg").style.display = "block";
    download(dataURL , "flixadvisor.jpeg", "image/jpeg");
    window.location.reload();
}

function findxy(res, e) {
    if (res == 'down') {
        prevX = currX;
        prevY = currY;
        currX = e.clientX - canvasOffset.left;
        currY = e.clientY - canvasOffset.top;

        flag = true;
        dot_flag = true;
        if (dot_flag) {
            ctx.beginPath();
            ctx.fillStyle = x;
            ctx.fillRect(currX, currY, 2, 2);
            ctx.closePath();
            dot_flag = false;
        }
    }
    if (res == 'up' || res == "out") {
        flag = false;
    }
    if (res == 'move') {
        if (flag) {
            prevX = currX;
            prevY = currY;
            currX = e.clientX - canvasOffset.left;
            currY = e.clientY - canvasOffset.top;
            draw();
        }
    }
}
