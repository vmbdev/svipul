<?php

class UserController extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

        $this->router->addRoute('login', 'login', 'login');
        $this->router->addRoute('register', 'register', 'register');
    }

    public function login() {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new User();
            try {
                $user->findByParams([
                    'email' => $email,
                    'password' => Config::generatePassword($password)
                ]);
                ResManager::getSession()->login($user);
                $this->content['loggedIn'] = true;
            } catch (Exception $e) {
                $this->addError(0, 'Authentication failed');
            }
        }
    }

    public function register() {
        if (!empty($_POST)) {
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if ($password === $password2) {
                $user = new User();
                $user->setProp('email', $_POST['email']);
                $user->setProp('password', Config::generatePassword($_POST['password']));
                $user->setProp('name', $_POST['name']);
                $user->setProp('surname', $_POST['surname']);
                $user->setProp('type', $_POST['type']);

                try {
                    $user->insert();
                    $this->content['registered'] = true;
                } catch (Exception $e) {
                    $this->addError(1, 'Registration failed');
                }
            }
        }
    }
}
