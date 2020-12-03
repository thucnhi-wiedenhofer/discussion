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
        $id_utilisateur=$_POST['id'];//puisqu'on est déjà connecté -
        $messageSend=$_POST['message'];//on recupère le message du formulaire
        

        //on insère le message dans la base discussion, table messages
        $sql = "INSERT INTO messages (message, id_utilisateur, date) VALUES (?,?,NOW())";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$messageSend, $id_utilisateur]);

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
    <link rel="stylesheet" href="css/bootstrap.css">
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
            <h1>Discussion en cours</h1><br />
                <div class="row">    
                    <div class="col-lg-4 col-md-3"></div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="content">
                                <?php foreach($message as $bubble){               
                                    echo '<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-header">';
                                    if($id_utilisateur==$_SESSION['id']){
                                        echo'<div id="circle" style="background:'.$_SESSION['color'].' "></div>';      
                                    }
                                        echo '<strong class="mr-auto">'.$bubble['login'].'</strong>';
                                            echo '<small>'.$date = date('d/m/Y h:i:s', strtotime($bubble['date'])).'</small>';
                                            echo '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">';
                                            
                                            echo'</button>';
                                    echo '</div>';
                                    echo '<div class="toast-body">';
                                    echo $bubble['message'];
                                    echo '</div>';
                                    echo '</div>';
                                } ?> 
                            </div>
                        </div>
                    <div class="col-lg-4 col-md-3"></div>    
                </div> 
                <div class="row">    
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8 col-sm-12">
                    <br />    
                        <div class="modal-content p-2">
                            <form action="discussion.php" method="post">
                                <textarea class="form-control" name="message"  maxlength="140" 
                                required  placeholder="Ecrire votre message ici (Max 140 caract.)"></textarea></br> 
                                <input type="hidden" name="id" value="<?php echo $_SESSION['id'];// conserve la valeur id dans un champs caché du formulaire
                        ?>">              
                                
                                <button type="submit" class="btn btn-secondary" name="submit">Envoyer</button>
                            </form>
                        </div>
                    </div>
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
    <script src="js/scroll.js"></script>
    
</body>
</html>