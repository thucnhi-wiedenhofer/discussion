<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=discussion', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

//déconnexion
if(isset($_POST['session_fin']))
{
    //enlève les variables de la session et utilisateurs de la table connected
    $sql = "DELETE FROM connected WHERE id_connected =  :id_connected";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_connected', $_SESSION['id'], PDO::PARAM_INT);   
    $stmt->execute();

    session_unset();
    //détruit la session
    session_destroy();
}
    
/*routine de validation des données - */
    
//connexion en tant que membre:
if (isset($_POST['submit'])) {
    function valid_data($data){
        $data = trim($data);/*enlève les espaces en début et fin de chaîne*/
        $data = stripslashes($data);/*enlève les slashs dans les textes*/
        $data = htmlspecialchars($data);/*enlève les balises html comme ""<>...*/
        return $data;
    }
        /*on récupère les valeurs login ,password du formulaire et on y applique
         les filtres de la fonction valid_data*/
        $login = valid_data($_POST["login"]);
        $password = $_POST["password"];
            
       
    /*on prépare une requête pour récupérer les données de l'utilisateur qui a rempli
     le formulaire, afin de vérifier que le login n'existe pas déja dans la table*/
     $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
     $stmt->execute([$login]);
     $user = $stmt->fetch();
     
         
       
            if (empty($user))//champs vide
            {
                $error="Ce login n'existe pas!";
            }
            elseif (password_verify($password, $user['password']))//vérification de password
            { 
                //attribue un code color random à l'utilisateur qui se connecte
                $input=array('#F0201A','#E61AF0','#F0ED1A','#65F01A','#1AF0D9','#8F1AF0','#F06E1A','#1A9CF0','#1A4BF0','#F01A44');
                $rand_keys = array_rand($input, 2);
                $rand_color = $input[$rand_keys[0]] ;
                $_SESSION['color'] = $rand_color;
                $_SESSION['login'] = $user['login'];
                $_SESSION['id'] = $user['id'];
                $id_connected = $user['id'];

                /*interface connected
                on va enregistrer l'utilisateur connecté dans une table connected s'il ne l'est pas déja(oubli de fin de session)
                */
                
                $stmt = $pdo->prepare("SELECT * FROM connected WHERE id_connected = ?");
                $stmt->execute([$_SESSION['id']]);
                $user = $stmt->fetch();

                if(empty($user)){
                    $sql = "INSERT INTO connected (login_connected, color, id_connected) VALUES (?,?,?)";
                    $stmt= $pdo->prepare($sql);
                    $stmt->execute([$login, $rand_color, $id_connected]);
                }
                    header('location:discussion.php');     
                                              
            } 
            else //si password différent
            {
                $error='Le mot de passe est invalide.';
               
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
    <title>Connexion</title>
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
                    if(!isset($_SESSION['login'])) //message de connexion dans la navbar et bouton de déconnexion
                    {
                        echo '<li class="nav-item">                        
                            <a class="nav-link" href="inscription.php">S\'inscrire</a>
                            
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="connexion.php">Se connecter</a>
                        </li>
                        <span class="sr-only">(current)</span>';
                    }
                    
                    else
                    {
                        echo '<li class="nav-item active align-right">
                        <span class="nav-link">Vous êtes connecté(e)</span>    
                        </li>';
                        echo '<li class="nav-item align-right">
                        <form action="connexion.php" method="post">                                            
                            <button type="submit" class="btn btn-danger" name="session_fin">Déconnexion</button><br/>                        
                        </form>
                        </li>
                    </ul>
                    <form method="post" action="profil.php" class="form-inline my-2 my-lg-0">
                            <button type="submit" class="btn btn-warning my-2 my-sm-0" name="modifier">Modifier votre profil</button>
                      </form>
                    ';

                    }
                    ?>
                
            </div>
        </nav>
    </header>
    <main>
        <div class="jumbotron2 back_img3">
            <article class="container">
                <h1>Connexion</h1>
                <p class="lead">Veuillez vous connecter pour entrer dans la discussion.</p>
               
                <div class="row">
                <section class="col-lg-3"></section>
                <section class="col-lg-6 col-sm-12">
                    <form action="connexion.php" method="post">
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
                        
                                             
                        <button type="submit" class="btn btn-secondary" name="submit">Envoyer</button>
                        </fieldset>
                    </form>
                </section>
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