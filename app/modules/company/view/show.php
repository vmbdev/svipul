<?php if ($controller->getError(2)): ?>
	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> <?= $controller->getError(2) ?>
	</div>

<?php elseif ($controller->getError(3)): ?>
	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> Permisos insuficientes: su perfil debe de ser de una compañía.
	</div>

<?php elseif ($jobseeker->getProp('updated') == 1): ?>
	<div id="jobseeker-info">
		<h1><?= $jobseeker->getProp('user')->getProp('surname') ?>, <?= $jobseeker->getProp('user')->getProp('name') ?></h1>
		<h3><?= $jobseeker->getProp('user')->getProp('email') ?></h3>
		<div>
			<p>Nacido en <?= $jobseeker->getProp('city') ?>, el <?= $jobseeker->getProp('birthday') ?></p>
			<p>Estudió <?= $jobseeker->getProp('studies') ?></p>
			<p>Tiene <?= $jobseeker->getProp('years_experience') ?> años de experiencia laboral</p>
		</div>
	</div>
<?php else: ?>
	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> El perfil no puede ser mostrado al no haber sido actualizado.
	</div>
<?php endif; ?>
