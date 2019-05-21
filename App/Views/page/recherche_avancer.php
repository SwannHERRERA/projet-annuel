<div class="col-md-9 col-lg-10 align-self">
    <div class="row">
        <div class="col-12">
            <form>
                <div class="form-group">
                    <input type="text" name="search" class="form-control" value="">
                </div>
                <div class="row mb-20">
                    <div class="col-4">
                        <div id="network" class="dropdown-check-list" tabindex="100">
                            <span class="anchor">Network</span>
                            <ul class="items">
                                <li><input type="checkbox" />&nbsp;Apple </li>
                                <li><input type="checkbox" />&nbsp;Orange</li>
                                <li><input type="checkbox" />&nbsp;Grapes </li>
                                <li><input type="checkbox" />&nbsp;Berry </li>
                                <li><input type="checkbox" />&nbsp;Mango </li>
                                <li><input type="checkbox" />&nbsp;Banana </li>
                                <li><input type="checkbox" />&nbsp;Tomato</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <div id="list1" class="dropdown-check-list" tabindex="100">
                            <span class="anchor">Genre</span>
                            <ul class="items">
                                <li><input type="checkbox"/>&nbsp;Apple </li>
                                <li><input type="checkbox"/>&nbsp;Orange</li>
                                <li><input type="checkbox"/>&nbsp;Grapes </li>
                                <li><input type="checkbox"/>&nbsp;Berry </li>
                                <li><input type="checkbox"/>&nbsp;Mango </li>
                                <li><input type="checkbox"/>&nbsp;Banana </li>
                                <li><input type="checkbox"/>&nbsp;Tomato</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <div id="list1" class="dropdown-check-list" tabindex="100">
                            <span class="anchor">actor</span>
                            <ul class="items">
                                <li><input type="checkbox" />&nbsp;Apple </li>
                                <li><input type="checkbox" />&nbsp;Orange</li>
                                <li><input type="checkbox" />&nbsp;Grapes </li>
                                <li><input type="checkbox" />&nbsp;Berry </li>
                                <li><input type="checkbox" />&nbsp;Mango </li>
                                <li><input type="checkbox" />&nbsp;Banana </li>
                                <li><input type="checkbox" />&nbsp;Tomato</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                    </div>
                    <div class="col-4">
                        <input type="text" id="search_actor" class="form-control" value="">
                        <div class="valid_result"></div>
                        <div id="ajax_result"></div>
                        <!--
                         Je veux un truc en AJAX qui fais une requête dans les acteurs qui m'affiche les 5 premiers resultats
                        Lorsque l'on click sur le nom d'un acteur on l'ajoute a un tableau(graphique composer de input styliser)
                        -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
