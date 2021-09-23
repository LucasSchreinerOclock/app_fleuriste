<?php
require_once 'database.php';

if (!empty($_POST['email']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST["tel"]) && !empty($_POST["grade"]) && !empty($_POST["password"]) && !empty($_POST['confirm_password'])) {
    $nom = htmlentities($_POST['nom']);
    $prenom = htmlentities($_POST['prenom']);
    $email = htmlentities($_POST['email']);
    $mobile = htmlentities($_POST['tel']);
    $grade = htmlentities($_POST['grade']);
    $password = htmlentities($_POST['password']);
    $confirm_password = htmlentities($_POST['confirm_password']);


    if ($grade === "admin") {
        $grade = 3;
    } else {
        $grade = 1;
    }

    $check = $bdd->prepare('SELECT username, email, password FROM user WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();

    if ($row == 0) {
        if (strlen($nom) <= 100) {
            if (strlen($prenom) <= 100) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if (strlen($mobile) <= 14 && is_numeric($mobile)) {
                        if (strlen($password) > 6 && strlen($password) < 32) {
                            if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password) == 1) {
                                if ($password === $confirm_password) {
                                    $cost = ['cost' => 12];
                                    $password = password_hash($password, PASSWORD_BCRYPT, $cost);
                                    $insert = $bdd->prepare('INSERT INTO users(firstname, surname, telephone, email, grade, password) VALUES(:prenom, :nom , :mobile,  :email, :grade,:password)');
                                    $insert->execute(array(
                                        'nom' => $nom,
                                        'prenom' => $prenom,
                                        'mobile' => $mobile,
                                        'grade' => $grade,
                                        'email' => $email,
                                        'password' => $password,
                                    ));
                                    header('Location:liste_employer.php?loggin=success');/* bug ne passe pas loggin_err*/
                                } else
                                    header('Location:add_employer.php?reg_err=confirm_password');
                            } else {
                                header('Location:add_employer.php?reg_err=password_char');
                            }
                        } else
                            header('Location:add_employer.php?reg_err=password_lenght');

                    } else
                        header('Location:add_employer.php?reg_err=mobile_err');
                } else
                    header('Location:add_employer.php?reg_err=email_err');
            } else
                header('Location:add_employer.php?add_err=firstname_length');

        } else
            header('Location :add_employer.php?reg_err=lastname_length');

    } else
        header('Location:add_employer.php?reg_err=already');
} else
    header('Location:add_employer.php?reg_err=emptyfield');
/* ici */