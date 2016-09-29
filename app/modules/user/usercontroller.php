<?php

class UserController extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

        $this->router->addRoute('login', 'login', 'login');
        $this->router->addRoute('logout', 'logout', null);
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
                $type = $_POST['type'];

                if ($type == 0) // jobseeker
                    $newuser = new Jobseeker();
                else if ($type == 1)
                    $newuser = new Company();
                else
                    throw new Exception('Invalid type', 400);

                $user = new User();

                try {
                    $user->setProp('email', $_POST['email']);
                    $user->setProp('password', Config::generatePassword($_POST['password']));
                    $user->setProp('name', $_POST['name']);
                    $user->setProp('surname', $_POST['surname']);
                    $user->setProp('type', $_POST['type']);

                    $newuser->setProp('user', $user);
                    $newuser->insert();
                    $this->content['registered'] = true;
                } catch (Exception $e) {
                    $this->addError(1, 'Registration failed');
                }
            }
        }
    }

    public function logout() {
        ResManager::getSession()->close();
        header('Location: /user/login');
    }
}
