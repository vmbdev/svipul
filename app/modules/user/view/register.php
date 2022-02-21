<?php if (!empty($registered)): ?>
	<div class="alert alert-success" role="alert">
		Registrado correctamente. Por favor, identifícate con tus nuevas credenciales.
	</div>
<?php elseif ($controller->getError(1)): ?>
	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> Información incorrecta.
	</div>
<?php endif; ?>

<form class="form-horizontal" action="/user/register" method="POST">
	<fieldset>
		<legend>Registro</legend>

		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="email">Email</label>
			<div class="col-md-4">
				<input id="email" name="email" type="text" class="form-control-md form-input-md" required="">
			</div>
		</div>

		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="password">Password</label>
			<div class="col-md-4">
				<input id="password" name="password" type="password" class="form-control-md form-input-md" required="">
			</div>
		</div>

		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="password2">Repite password</label>
			<div class="col-md-4">
				<input id="password2" name="password2" type="password" class="form-control-md form-input-md" required="">
			</div>
		</div>

		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="name">Nombre</label>
			<div class="col-md-4">
				<input id="name" name="name" type="text" class="form-control-md form-input-md" required="">
			</div>
		</div>

		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="surname">Apellidos</label>
			<div class="col-md-4">
				<input id="surname" name="surname" type="text" class="form-control-md form-input-md" required="">
			</div>
		</div>

		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="type">Tipo</label>
			<div class="col-md-4">
				<div class="radio">
					<label for="type-0">
					<input type="radio" name="type" id="type-0" value="0" checked="checked" required>
					Solicitante
					</label>
				</div>
				<div class="radio">
					<label for="type-1">
					<input type="radio" name="type" id="type-1" value="1">
					Empresa
					</label>
				</div>
			</div>
		</div>


		<div class="form-control-lg row">
			<label class="col-md-4 form-control-label" for="submit"></label>
			<div class="col-md-4">
				<button id="submit" name="submit" class="btn btn-primary">Registrarse</button>
			</div>
		</div>
	</fieldset>
</form>
