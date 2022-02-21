<?php if ($controller->getError(0)): ?>
	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> No hay solicitantes disponibles
	</div>

<?php elseif ($controller->session->isLogged() && ($controller->session->getProp('user')->getProp('type') == 1)): ?>
	<form class="form-horizontal" action="/company/list" method="POST">
		<fieldset>
			<div class="form-control-lg row">
				<label class="col-md-4 form-control-label" for="years_experience">Años de experiencia</label>
				<div class="col-md-4">
					<input id="years_experience" name="years_experience" type="text" class="form-control-md form-input-md">
				</div>
			</div>

			<div class="form-control-lg row">
				<label class="col-md-4 form-control-label" for="studies">Estudios</label>
				<div class="col-md-4">
					<input id="studies" name="studies" type="text" class="form-control-md form-input-md">
				</div>
			</div>

			<div class="form-control-lg row">
				<label class="col-md-4 form-control-label" for="submit"></label>
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-primary">Filtrar</button>
				</div>
			</div>
		</fieldset>
	</form>

	<ul>
		<?php foreach ($list as $jobseeker): ?>
			<li>
				<a href="/company/show/profile/<?= $jobseeker->getId() ?>">
					<?= $jobseeker->getProp('user')->getProp('surname') ?>, <?= $jobseeker->getProp('user')->getProp('name') ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

<?php else: ?>
	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> El usuario no está registrado o no es una empresa.
	</div>
<?php endif; ?>
