<?php

session_start();
//déconnexion
if(isset($_POST['session_fin']))
{
    //enlève les variables de la session
    session_unset();
    //détruit la session
    session_destroy();
}
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=discussion;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

//on vérifie que le formulaire a été envoyé
if(isset($_POST['submit']))
{
    
    if(isset($_POST['message']) AND !empty($_POST['message']))
    {
        $id_utilisateur=$_SESSION['id'];//puisqu'on est déjà connecté
        $message=$_POST['message'];//on recupère le message du formulaire

        //on insère le message dans la base discussion, table messages
            $mysqli->query("INSERT INTO messages ( message,id_utilisateur,date) 
            VALUES ('$_POST[message]','$_POST[id_utilisateur]', NOW())") OR DIE ($mysqli->error);
            echo '<div class="lert alert-dismissible alert-success">Votre message a bien été enregistré.</div>';
    }    
    else
    {
        echo '<div class="alert alert-dismissible alert-warning">Veuillez remplir tous les champs du formulaire.</div>';
    }
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/4/minty/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Discussion</title>
</head>
<body>
    
    <header>               
     
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
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
                            <button type="submit" class="btn secondary disabled" name="session_fin">Déconnexion</button><br/>                        
                        </form>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header> 
    <main>    
        <div class="jumbotron2 back_img2">
            <article class="container">
                <h1 class="display-3">Discussion</h1>
                <p class="lead">Consulter les derniers messages</p><br/>
                <div class="modal">
                    <form action="discussion.php" method="post">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Posté le</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" name="message" id="message" maxlength="1000" rows="3" 
                                required  placeholder="Ecrire votre message ici"></textarea> 
                            </div>
                            <div class="modal-footer">
                                
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Envoyer</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </article> 
        </div>                   
    </main>  
    
    <section class="container">
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
    </section>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>