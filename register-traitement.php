<?php
require_once 'database.php';

if (!empty($_POST['email']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST["tel"]) && !empty($_POST["password"]) && !empty($_POST['confirm_password'])) {
    $nom = htmlentities($_POST['nom']);
    $prenom = htmlentities($_POST['prenom']);
    $email = htmlentities($_POST['email']);
    $mobile = htmlentities($_POST['tel']);
    $password = htmlentities($_POST['password']);
    $confirm_password = htmlentities($_POST['confirm_password']);


    $check = $bdd->prepare('SELECT username, email, password FROM user WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();
    $email = strtolower($email);

    if ($row == 0) {
        if (strlen($nom) <= 100) {
            if (strlen($prenom) <= 100) {
                if (strlen($email) <= 100) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if ($password > strlen("4")) {
                            if ($password === $confirm_password) {
                                $cost = ['cost' => 12];
                                $password = password_hash($password, PASSWORD_BCRYPT, $cost);
                                $insert = $bdd->prepare('INSERT INTO users(firstname, surname, telephone, email, password) VALUES(:prenom, :nom , :mobile, :email, :password)');
                                $insert->execute(array(
                                    'nom' => $nom,
                                    'prenom' => $prenom,
                                    'mobile' => $mobile,
                                    'email' => $email,
                                    'password' => $password,
                                ));
                                header('Location:index.php?loggin_err=success');/* bug ne passe pas loggin_err*/
                            } else {
                                header('Location: register.php?reg_err=password');
                            }
                        } else {
                            header('Location: register.php?reg_err=password_lenght');
                        }
                    } else {
                        header('Location: register.php?reg_err=valid');
                    }
                } else {
                    header('Location: register.php?reg_err=email_length');
                }
            } else {
                header('Location: register.php?reg_err=nom_length');
            }
        } else {
            header('Location : register.php?reg_err=nom_length');
        }
    } else {
        header('Location: register.php?reg_err=already');
    }
} else {
    header('Location: register.php?reg_err=emptyfield');
}