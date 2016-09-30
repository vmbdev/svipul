<?php if ($controller->getError(0)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error:</strong> No se han encontrado datos.
    </div>

<?php elseif ($controller->session->isLogged() && ($controller->session->getProp('user')->getProp('type') == 1)): ?>

    <form class="form-horizontal" action="/company/edit" method="POST">
        <fieldset>
            <legend>Edita tu perfil</legend>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="cif">CIF</label>
                <div class="col-md-4">
                    <input id="cif" name="cif" type="text" class="form-control-md form-input-md" value="<?= $model->getProp('cif') ?>">
                </div>
            </div>

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="address">Dirección</label>
                <div class="col-md-4">
                    <input id="address" name="address" type="text" class="form-control-md form-input-md" value="<?= $model->getProp('address') ?>">
                </div>
            </div>
            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="submit"></label>
                <div class="col-md-4">
                    <button id="submit" name="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </fieldset>
    </form>

<?php else: ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error:</strong> El usuario no está registrado o no es un solicitante.
    </div>
<?php endif; ?>
