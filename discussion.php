<?php

session_start();

$pdo = new PDO('mysql:host=localhost;dbname=discussion', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
$id_connected=$_SESSION['id'];
//déconnexion
if(isset($_POST['session_fin']))
{   
    /* on enléve les données de l'utilisateur dans la table connected */
    $sql = "DELETE FROM connected WHERE id_connected =  :id_connected";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_connected', $id_connected, PDO::PARAM_INT);   
    $stmt->execute();

    //enlève les variables de la session
    session_unset();
    //détruit la session
    session_destroy();
}

/*on prépare une requête pour récupérer les messages*/
 $message = $pdo->query("SELECT id_utilisateur, message, date, login, login_connected, color FROM messages LEFT JOIN utilisateurs ON id_utilisateur=utilisateurs.id LEFT JOIN connected ON id_utilisateur=id_connected ORDER BY date DESC")->fetchAll();
 

//on vérifie que le formulaire a été envoyé
if(isset($_POST['submit']))
{
    //on vérifie que la variable message contient bien une string
    if(isset($_POST['message']) AND !empty($_POST['message']))
    {
        $id_utilisateur=$_SESSION['id'];//puisqu'on est déjà connecté -
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
                            <button type="submit" class="btn btn-warning my-2 my-sm-0" name="modifier">Modifier votre profil</button>
                      </form>';
                    }
                    ?>
                
            </div>
        </nav>
    </header> 
    <main>    
        <div class="jumbotron2 back_img2">
            <article class="container">
            
                <div class="row"> 
                    <section class="col-lg-2 col-md-2 col-sm-12"></section>
                    <section class="col-lg-8 col-md-8 col-sm-12">
                        <h1>Discussion en cours</h1><br />
                                
                                <!-- On compte combien d'utilisateurs sont actuellement connectés dans la table connected -->       
                                <?php 
                                    $count = $pdo->query("SELECT COUNT(*) FROM connected")->fetchColumn();
                                    $membres = $pdo->query("SELECT  login_connected, color FROM connected ")->fetchAll();
                                    //on récupére la liste des utilisateurs connectés et leurs couleurs respectives
                                    ?>
                                    <!-- on affiche le total de connectés -->
                                <p class="lead">Il y a actuellement: <?php if($count>1){echo '<span class="badge badge-dark">'.$count.'</span> Membres connectés';}
                                    else{echo '<span class="badge badge-dark">'.$count.'</span> membre connecté';} ?>
                                    </p>
                                    <!-- on affiche la liste des connectés avec une boucle -->
                                <?php 
                                    if($membres){
                                        foreach($membres as $connected){
                                        echo'<svg height="10" width="10">
                                        <circle r="2" cx="5" cy="5" stroke="'.$connected['color'].'" stroke-width="3" fill="'.$connected['color'].'" />
                                        </svg> '.$connected['login_connected'].' ';  
                                        } 
                                    }?>
                                    
                    </section>
                
                    <section class="col-lg-2 col-md-2 col-sm-12"></section>
                </div>

                <div class="row">
                        <section class="col-lg-4 col-md-4 col-sm-12">  
                                </section>
                    
                        <section class="col-lg-4 col-md-4 col-sm-12">         
                            
                            <div class="content">
                            <br /> 
                                <?php 
                                    // Affichage des messages avec login des utilisateurs, couleur et date
                                
                                    foreach($message as $bubble){               
                                    echo '<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-header">';
                                    if(isset($bubble['color']) && !empty($bubble['color'])){
                                        echo'<div id="circle" style="background:'.$bubble['color'].' "></div>';      
                                    }
                                        echo '<strong class="mr-auto">'.$bubble['login'].'</strong>';
                                            //mise au format européen de la date et heure
                                            echo '<small>'.$date = date('d/m/Y h:i:s', strtotime($bubble['date'])).'</small>';
                                            
                                    echo '</div>';
                                    echo '<div class="toast-body">';
                                    echo $bubble['message'];
                                    echo '</div>';
                                    echo '</div>';
                                } ?> 
                            </div>
                        </section>
                        <section class="col-lg-4 col-md-4 col-sm-12"></section>    
                </div> 
                <div class="row">    
                    <section class="col-lg-2 col-sm-2"></section>
                    <section class="col-lg-8 col-sm-8">
                        <br />  
                        <!-- formulaire pour poster message -->  
                        <div class="modal-content  p-2">
                            <form action="discussion.php" method="post">
                                <textarea class="form-control" name="message"  maxlength="140" 
                                required  placeholder="Ecrire votre message ici (Max 140 caract.)"></textarea></br>        
                                
                                <button type="submit" class="btn btn-secondary" name="submit">Envoyer</button>
                            </form>
                        </div>
                    </section>
                    <section class="col-lg-2 col-sm-2"></section>
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