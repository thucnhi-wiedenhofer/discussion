<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=discussion;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

session_start();
//déconnexion
if(isset($_POST['session_fin']))
{
    //enlève les variables de la session
    session_unset();
    //détruit la session
    session_destroy();
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
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
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
        <main>
            <div class="jumbotron2">
                <h1>Inscription</h1>
                <p class="lead">Veuillez vous inscrire pour entrer dans la discussion.</p>
               
                
                <section class="col-sm-12">
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
        </main>
        <footer id="footer">
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
        </footer>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>

</body>
</html>