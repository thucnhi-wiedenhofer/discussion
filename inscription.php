<?php
session_start();


/*routine de validation des données*/
if(isset($_SESSION) && !empty($_SESSION)){
    header('location:connexion.php');
}

 elseif (isset($_POST['submit'])) {
    function valid_data($data){
                $data = trim($data);/*enlève les espaces en début et fin de chaîne*/
                $data = stripslashes($data);/*enlève les slashs dans les textes*/
                $data = htmlspecialchars($data);/*enlève les balises html comme ""<>...*/
                return $data;
            }
    /*on récupère les valeurs login ,password, prenom, nom du formulaire et on y applique ,
     les filtres de la fonction valid_data*/
    $login = valid_data($_POST["login"]);
    $password = $_POST["password"];
   

    $password = password_hash($password, PASSWORD_DEFAULT);/*Crypte le mot de passe*/

    $pdo = new PDO('mysql:host=localhost;dbname=discussion', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    /*on prépare une requête pour récupérer les données de l'utilisateur qui a rempli
     le formulaire, afin de vérifier que le login n'existe pas déja dans la table*/
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login=?');
    $stmt->execute($login);
    $userExist = $stmt->fetch();
     
   
            if ($userExist)
            {
                $error="Ce login existe déja !";
            }
            elseif ($_POST['password'] != $_POST['conf-password'])
            {
                $error="Les mots de passe ne sont pas identiques!";
            }
            elseif(empty($_POST['password']))
            {
                $error="tous les champs doivent être remplis!";
            }
            else
            {
                 //attribue un code color random à l'utilisateur
                 $input=array('#F0201A','#E61AF0','#F0ED1A','#65F01A','#1AF0D9','#8F1AF0','#F06E1A','#1A9CF0','#1A4BF0','#F01A44');
                 $rand_keys = array_rand($input, 2);
                 $rand_color= $input[$rand_keys[0]] ;
                /*si le login est nouveau, on insert les données dans la base discussion,table utilisateurs*/
                $sql = "INSERT INTO utilisateurs (login, password, color) VALUES (?,?,?)";
                $stmt= $pdo->prepare($sql);
                $stmt->execute([$login, $password, $rand_color]);

                header('Location:connexion.php');
            }
    mysqli_close($db);

}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/4/minty/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Inscription</title>
</head>
<body>
    
    <header>               
     
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                        
                    <?php 
                    if(isset($_SESSION['login'])) //message de connexion dans la navbar et bouton de déconnexion
                    {
                        echo '<li class="nav-item active align-right">
                        <span class="nav-link">Vous êtes connecté(e)</span>    
                        </li>';
                        echo '<li class="nav-item align-right">
                        <form action="connexion.php" method="post">                                            
                            <button type="submit" class="btn btn-info" name="session_fin">Déconnexion</button><br/>                        
                        </form>
                        </li>';
                    }
                    else
                    {
                        echo '<li class="nav-item active">                        
                            <a class="nav-link" href="inscription.php">S\'inscrire</a>
                            <span class="sr-only">(current)</span>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="connexion.php">Se connecter</a>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>
    <main >
        <div class="jumbotron2 back_img3">
            <article class="container">
                <h1>Inscription</h1>
                <p class="lead">Veuillez vous inscrire pour entrer dans la discussion.</p>
                <div class="row">
                    <section class="col-lg-3"></section>
                    <section class="col-lg-6 col-sm-12">
                        <form action="inscription.php" method="post">
                            <fieldset >
                        <!-- envoyer un message d'erreur si login existe déjà ou si password invalide-->
                        <?php if(!empty($error)){echo '<p class="h4 text-warning">'.$error.'</p>'; } ?> 
                        
                            <div class="form-group">
                            <label for="login">Identifiant</label>
                            <input type="txt" class="form-control" id="login" name="login" 
                            placeholder="login" required>
                            </div>   

                            <div class="form-group">
                            <label for="password">Mot de passse</label>
                            <input type="password" class="form-control" id="password" 
                            name="password" placeholder="Password" required>
                            </div>                       
                            
                            <div class="form-group">
                            <label for="conf-password">Confirmer votre mot de passe</label>
                            <input type="password" class="form-control" id="conf-password" 
                            name="conf-password" placeholder="Password" required>
                            </div>                                            
                                                        
                            <button type="submit" class="btn btn-secondary" name="submit">Envoyer</button>
                            </fieldset>
                        </form>
                    </section>
                </div>
            </article>
        </div>
    </main>
        <footer id="footer">
            <article class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled">
                        <li class="float-lg-right"><a href="#top">Back to top</a></li>
                        
                        <li><a href="https://github.com/thucnhi-wiedenhofer">GitHub</a></li>
                        
                        </ul>
                        <p>Bootstrap style made by <a href="https://thomaspark.co/">Thomas Park</a>.</p>
                        <p>Code released under the <a href="https://github.com/thomaspark/bootswatch/blob/master/LICENSE">MIT License</a>.</p>
                        
                    </div>
                </div>
            </article>
        </footer>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>