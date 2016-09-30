<?php if (ResManager::getSession()->isLogged()): ?>
    <?php $controller->goto('/'); ?>
<?php elseif ($controller->getError(0)): ?>
    Incorrect login info
<?php endif; ?>

<form class="form-horizontal" action="/user/login" method="POST">
    <fieldset>
        <legend>Login</legend>

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
            <label class="col-md-4 form-control-label" for="submit"></label>
            <div class="col-md-4">
                <button id="submit" name="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </fieldset>
</form>
