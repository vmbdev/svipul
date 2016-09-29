<?php if ($controller->getError(0)): ?>
    <?= $controller->getError(0) ?>
<?php else: ?>

    <form class="form-horizontal" action="/jobseeker/edit" method="POST">
        <fieldset>
            <legend>Edita tu perfil</legend>

            <?php if ($model->getProp('updated')): ?>
                <div>Tu perfil está disponible para mostrarse.</div>
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

            <div class="form-control-lg row">
                <label class="col-md-4 form-control-label" for="submit"></label>
                <div class="col-md-4">
                    <button id="submit" name="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </fieldset>
    </form>

<?php endif; ?>
