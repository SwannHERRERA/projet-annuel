<div class="col-md-9 col-lg-10 align-self">
    <?php if (isset($_SESSION['update_param'])): ?>
        <div class="mt-30 alert alert-danger">
            <ul class='mb-0'>
                <?php foreach ($_SESSION['update_param'] as $error): ?>
                    <?php if (is_string($error)) echo '<li>' . $error . '</li>';?>
                <?php endforeach; ?>
                <?php unset($_SESSION['update_param']); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class='mt-50 mb-65'>
        <h1>Paramètres</h1>

        <p><label>Drawing tool: <select id="dtool">
                    <option value="line">Line</option>
                    <option value="rect">Rectangle</option>
                    <option value="pencil">Pencil</option>
                </select></label></p>

        <div id="container">
            <canvas id="imageView" width="400" height="300">
                <p>Unfortunately, your browser is currently unsupported by our web
                    application.  We are sorry for the inconvenience. Please use one of the
                    supported browsers listed below, or draw the image you want using an
                    offline tool.</p>
                <p>Supported browsers: <a href="http://www.opera.com">Opera</a>, <a
                            href="http://www.mozilla.com">Firefox</a>, <a
                            href="http://www.apple.com/safari">Safari</a>, and <a
                            href="http://www.konqueror.org">Konqueror</a>.</p>
            </canvas>
        </div>

        <div class="form-row mt-5">
            <div class="form-group col-md-6">
                <label for="email">E-mail</label>
                <input type="text" class="form-control" name="email" id="email" value="<?= $current_param['email'] ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="pseudo">Pseudonyme</label>
                <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $current_param['pseudo']?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="genre">Genre</label>
                <select name="genre" class="form-control" id="genre">
                    <option <?php echo ($current_param['gender'] == 0) ? 'selected' : ''?> value="0"></option>
                    <option <?php echo ($current_param['gender'] == 1) ? 'selected' : ''?> value="1">Homme</option>
                    <option <?php echo ($current_param['gender'] == 2) ? 'selected' : ''?> value="2">Femme</option>
                    <option <?php echo ($current_param['gender'] == 3) ? 'selected' : ''?> value="3">Autre</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="form-group row no-gutters">
                    <label for="dateNaissance" class="col-12">Date de naissance
                    </label>
                    <div class="col-10">
                        <input type="date" name="dateNaissance" id="dateNaissance" class="form-control" value="<?= $current_param['birth_date'] ?>">
                    </div>
                    <label for="dateNaissance" class="col-2 col-form-label text-center">
                        <i class="fas fa-calendar-alt"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="country">Pays</label>
                <input type="text" name="country" id="country" class="form-control" value="<?= $current_param['country'] ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="city">Ville</label>
                <input type="text" class="form-control" name="city" id="city" value="<?= $current_param['city']?>">
            </div>
        </div>

        <p class="text-center">
            <button type='submit' class="btn btn-yellow">Enregistrer</button>
        </p>
    </form>
</div>

<script>
    /* © 2009 ROBO Design
 * http://www.robodesign.ro
 */

    // Keep everything in anonymous function, called on window load.
    if(window.addEventListener) {
        window.addEventListener('load', function () {
            var canvas, context, canvaso, contexto;

            // The active tool instance.
            var tool;
            var tool_default = 'line';

            function init () {
                // Find the canvas element.
                canvaso = document.getElementById('imageView');
                if (!canvaso) {
                    alert('Error: I cannot find the canvas element!');
                    return;
                }

                if (!canvaso.getContext) {
                    alert('Error: no canvas.getContext!');
                    return;
                }

                // Get the 2D canvas context.
                contexto = canvaso.getContext('2d');
                if (!contexto) {
                    alert('Error: failed to getContext!');
                    return;
                }

                // Add the temporary canvas.
                var container = canvaso.parentNode;
                canvas = document.createElement('canvas');
                if (!canvas) {
                    alert('Error: I cannot create a new canvas element!');
                    return;
                }

                canvas.id     = 'imageTemp';
                canvas.width  = canvaso.width;
                canvas.height = canvaso.height;
                container.appendChild(canvas);

                context = canvas.getContext('2d');

                // Get the tool select input.
                var tool_select = document.getElementById('dtool');
                if (!tool_select) {
                    alert('Error: failed to get the dtool element!');
                    return;
                }
                tool_select.addEventListener('change', ev_tool_change, false);

                // Activate the default tool.
                if (tools[tool_default]) {
                    tool = new tools[tool_default]();
                    tool_select.value = tool_default;
                }

                // Attach the mousedown, mousemove and mouseup event listeners.
                canvas.addEventListener('mousedown', ev_canvas, false);
                canvas.addEventListener('mousemove', ev_canvas, false);
                canvas.addEventListener('mouseup',   ev_canvas, false);
            }

            // The general-purpose event handler. This function just determines the mouse
            // position relative to the canvas element.
            function ev_canvas (ev) {
                if (ev.layerX || ev.layerX == 0) { // Firefox
                    ev._x = ev.layerX;
                    ev._y = ev.layerY;
                } else if (ev.offsetX || ev.offsetX == 0) { // Opera
                    ev._x = ev.offsetX;
                    ev._y = ev.offsetY;
                }

                // Call the event handler of the tool.
                var func = tool[ev.type];
                if (func) {
                    func(ev);
                }
            }

            // The event handler for any changes made to the tool selector.
            function ev_tool_change (ev) {
                if (tools[this.value]) {
                    tool = new tools[this.value]();
                }
            }

            // This function draws the #imageTemp canvas on top of #imageView, after which
            // #imageTemp is cleared. This function is called each time when the user
            // completes a drawing operation.
            function img_update () {
                contexto.drawImage(canvas, 0, 0);
                context.clearRect(0, 0, canvas.width, canvas.height);
            }

            // This object holds the implementation of each drawing tool.
            var tools = {};

            // The drawing pencil.
            tools.pencil = function () {
                var tool = this;
                this.started = false;

                // This is called when you start holding down the mouse button.
                // This starts the pencil drawing.
                this.mousedown = function (ev) {
                    context.beginPath();
                    context.moveTo(ev._x, ev._y);
                    tool.started = true;
                };

                // This function is called every time you move the mouse. Obviously, it only
                // draws if the tool.started state is set to true (when you are holding down
                // the mouse button).
                this.mousemove = function (ev) {
                    if (tool.started) {
                        context.lineTo(ev._x, ev._y);
                        context.stroke();
                    }
                };

                // This is called when you release the mouse button.
                this.mouseup = function (ev) {
                    if (tool.started) {
                        tool.mousemove(ev);
                        tool.started = false;
                        img_update();
                    }
                };
            };

            // The rectangle tool.
            tools.rect = function () {
                var tool = this;
                this.started = false;

                this.mousedown = function (ev) {
                    tool.started = true;
                    tool.x0 = ev._x;
                    tool.y0 = ev._y;
                };

                this.mousemove = function (ev) {
                    if (!tool.started) {
                        return;
                    }

                    var x = Math.min(ev._x,  tool.x0),
                        y = Math.min(ev._y,  tool.y0),
                        w = Math.abs(ev._x - tool.x0),
                        h = Math.abs(ev._y - tool.y0);

                    context.clearRect(0, 0, canvas.width, canvas.height);

                    if (!w || !h) {
                        return;
                    }

                    context.strokeRect(x, y, w, h);
                };

                this.mouseup = function (ev) {
                    if (tool.started) {
                        tool.mousemove(ev);
                        tool.started = false;
                        img_update();
                    }
                };
            };

            // The line tool.
            tools.line = function () {
                var tool = this;
                this.started = false;

                this.mousedown = function (ev) {
                    tool.started = true;
                    tool.x0 = ev._x;
                    tool.y0 = ev._y;
                };

                this.mousemove = function (ev) {
                    if (!tool.started) {
                        return;
                    }

                    context.clearRect(0, 0, canvas.width, canvas.height);

                    context.beginPath();
                    context.moveTo(tool.x0, tool.y0);
                    context.lineTo(ev._x,   ev._y);
                    context.stroke();
                    context.closePath();
                };

                this.mouseup = function (ev) {
                    if (tool.started) {
                        tool.mousemove(ev);
                        tool.started = false;
                        img_update();
                    }
                };
            };

            init();

        }, false); }

    // vim:set spell spl=en fo=wan1croql tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:


</script>