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
$pdo = new PDO('mysql:host=localhost;dbname=discussion', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
/*on prépare une requête pour récupérer les messages*/
 $message = $pdo->query("SELECT * FROM messages JOIN utilisateurs ON id_utilisateur=utilisateurs.id ORDER BY date DESC")->fetchAll();
 

//on vérifie que le formulaire a été envoyé
if(isset($_POST['submit']))
{
    
    if(isset($_POST['message']) AND !empty($_POST['message']))
    {
        $id_utilisateur=$_SESSION['id'];//puisqu'on est déjà connecté -
        $messageSend=$_POST['message'];//on recupère le message du formulaire
        $date=date("Y.m.d");

        //on insère le message dans la base discussion, table messages
        $sql = "INSERT INTO messages (message, id_utilisateur, date) VALUES (?,?,?)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$messageSend, $id_utilisateur, $date]);

        header('Location:discussion.php');
            
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
                        echo '<li class="nav-item active ">
                        <span class="nav-link">Vous êtes connecté(e)</span>    
                        </li>';
                        echo '<li class="nav-item ">
                        <form action="connexion.php" method="post">                                            
                            <button type="submit" class="btn btn-danger" name="session_fin">Déconnexion</button><br/>                        
                        </form>
                        </li>
                </ul>
                        <form method="post" action="profil.php" class="form-inline my-2 my-lg-0">
                            <button class="btn btn-warning my-2 my-sm-0" type="Modifier">Modifier votre profil</button>
                      </form>';
                    }
                    ?>
                
            </div>
        </nav>
    </header> 
    <main>    
        <div class="jumbotron2 back_img2">
            <article class="container">
            <?php foreach($message as $buble){               
                echo '<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">';
                echo'<div id="circle" style="background:'.$buble['color'].' "></div>';
                       echo '<strong class="mr-auto">'.$buble['login'].'</strong>';
                        echo '<small>'.$date = date('d/m/Y', strtotime($buble['date'])).'</small>';
                        echo '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">';
                        echo'<span aria-hidden="true">&times;</span>';
                        echo'</button>';
                echo '</div>';
                echo '<div class="toast-body">';
                   echo $buble['message'];
                echo '</div>';
                echo '</div>';
              } ?>
                            <div class="modal-body">
                            <form action="discussion.php" method="post">
                                <textarea class="form-control" name="message"  maxlength="140" 
                                required  placeholder="Ecrire votre message ici (Max 140 caract.)"></textarea> 
                            </div>
                            <div class="modal-footer">
                                
                                <button type="submit" class="btn btn-secondary" name="submit">Envoyer</button>
                                </form>
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