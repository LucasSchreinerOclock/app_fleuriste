<?php
session_start();
require_once 'database.php';
$sessionid= intval($_SESSION['user']);

$rank = $bdd->prepare("SELECT * from users where id = :session");
$rank->execute(array(
    'session'=> $sessionid,
));
$user = $rank->fetch();

if ($user["grade"] >= 3){
    $admin = "admin";
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <link href="style.css" rel="stylesheet">
    <title>flower app</title>
</head>
<body>
<header>
    <div class="logo">
        <h1>La boite à fleurs</h1>
    </div>
    <nav>
        <?php if (isset($admin))
        { ?>
            <a href="liste_employer.php">Employée</a>
        <?php } ?>
        <a href="client.php">Client</a>
        <a href="commande.php">Commande</a>
        <a href="fleurs.php">Fleurs</a>
    </nav>
</header>
<main>
    <b>Bienvenu ! <?= $user[1]?><br></b>
    <b>Vous êtes <?php
        if (isset($admin)) {
            echo $admin;
        }else echo "Employée";
        ?></b>
    <a href="logout.php">logout</a>

    <form action="add_employer_traitement.php" method="post">
        <label for="email">Email</label>
        <input type="email" name="email">
        <label for="prenom">Prenom</label>
        <input type="text" name="prenom">
        <label for="nom">Nom</label>
        <input type="tel" name="nom">
        <label for="tel">Mobile</label>
        <input type="text" name="tel">
        <label for="grade">Grade</label>
        <input type="number" name="grade">
        <label for="password">Mot de passe</label>
        <input type="password" name="password">
        <label for="password">Confirmer mot de passe</label>
        <input type="password" name="confirm_password">
        <input type="submit">
    </form>
</main>