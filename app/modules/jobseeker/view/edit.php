<?php if ($controller->getError(0)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error:</strong> El usuario no está registrado o no es un solicitante.
    </div>

<?php elseif ($controller->session->isLogged() && ($controller->session->getProp('user')->getProp('type') == 0)): ?>

    <form class="form-horizontal" action="/jobseeker/edit" method="POST">
        <fieldset>
            <legend>Edita tu perfil</legend>

            <?php if ($model->getProp('updated')): ?>
                <div class="alert alert-success" role="alert">
                    Tu perfil está disponible para mostrarse.
                </div>
            <?php endif; ?>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="birthday">Fecha de nacimiento</label>
                <div class="col-md-4">
                    <input id="birthday" name="birthday" type="text" class="form-control-md form-input-md" value="<?= $model->getProp('birthday') ?>">
                </div>
            </div>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="city">Ciudad</label>
                <div class="col-md-4">
                    <input id="city" name="city" type="text" class="form-control-md form-input-md" value="<?= $model->getProp('city') ?>">
                </div>
            </div>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="studies">Estudios</label>
                <div class="col-md-4">
                    <input id="studies" name="studies" type="text" class="form-control-md form-input-md" value="<?= $model->getProp('studies') ?>">
                </div>
            </div>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="years_experience">Años de experiencia</label>
                <div class="col-md-4">
                    <input id="years_experience" name="years_experience" type="text" class="form-control-md form-input-md" value="<?= $model->getProp('years_experience') ?>">
                </div>
            </div>

            <legend>Idiomas</legend>
            <div class="form-control-lg row">
                <a href="#" data-toggle="modal" data-target="#addlanguage">Añadir nuevo idioma</a>
            </div>

            <?php foreach ($languages as $language): ?>
                <div class="form-control-lg row">
                    <label class="col-md-4 form-control-label" for="language">Idioma</label>
                    <div class="col-md-4">
                        <input id="language" name="language[]" type="text" class="form-control-md form-input-md" value="<?= $language->getProp('language') ?>">
                        <a href="/jobseeker/edit/removelanguage/<?= $language->getId() ?>">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="submit"></label>
                <div class="col-md-4">
                    <button id="submit" name="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="modal fade" id="addlanguage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Añadir idioma</h4>
                </div>
                <div class="modal-body">
                    <div class="form-control-lg row">
                        <label class="col-md-4 form-control-label" for="newlanguage">Nuevo idioma</label>
                        <div class="col-md-4">
                            <input id="newlanguage" name="newlanguage" type="text" class="form-control-md form-input-md">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="baddlanguage" class="btn btn-primary">Añadir</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('#baddlanguage').click(function() {
            window.location.replace("/jobseeker/edit/addlanguage/" + $('#newlanguage').val());
        });
    </script>

<?php else: ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error:</strong> El usuario no está registrado o no es un solicitante.
    </div>
<?php endif; ?>
