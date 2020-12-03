<?php

session_start();

function valid_data($data){  //fonction pour éviter l'injection de code malveillant
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_POST['modifier']) && isset($_SESSION['id']))  //un adhérent qui s'est connecté veut modifier ses données
{    
    $id=$_SESSION['id'];//on fait la requête sur la seul donnée qui ne change pas c'est à dire id.
    $pdo = new PDO('mysql:host=localhost;dbname=discussion', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    /*on prépare une requête pour récupérer les données de l'utilisateur qui veut modifier son profil
     */
     $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
     $stmt->execute([$id]);
     $user = $stmt->fetch();
     
         
            if (empty($user)) //la requête n'a pas aboutie
            {
                $error="Il y a une erreur de lecture de vos données!";               
            }
            else //succés on conserve dans des variables les infos de l'adhérent pour remplir le formulaire
            {
            $login = $user['login'];
            $password = $user['password'];
            $_POST = array(); //initialisation de POST à 0
            }                         
}

elseif (isset($_POST['update']) && $_SESSION['id']==$_POST['id'] ) { //l'adhérent a modifié ses données, on conserve en variables ces nouvelles données
    
    $id= $_SESSION['id'];
    $login =valid_data($_POST['login']);
    $new_Password = $_POST['password'];
    $new_Password = password_hash($new_Password, PASSWORD_DEFAULT);

    if ($_POST['password'] != $_POST['conf-password'])
    {
        $error="Les mots de passe ne sont pas identiques!"; //erreur dans le formulaire
    }        

    else
    {   $pdo = new PDO('mysql:host=localhost;dbname=discussion', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        
        $req = $pdo->prepare('UPDATE utilisateurs  SET login = :login, password = :new_Password WHERE id = :id');
        $req->execute(array(
            'login' => $login,
            'new_Password' => $new_Password,
            'id' => $id
            ));

         /* on attribue les nouvelles valeurs au tableau session si la requéte a fonctionné*/
            if($req && isset($_POST['update']))
            {
                $_SESSION['login']=$login;
                $_SESSION['update']="Ok";
                header('Location:connexion.php');
            }
                
    }  
}
else
{
   $error="tous les champs doivent être remplis";
   
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/4/minty/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>profil</title>
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
                        echo '<li class="nav-item ">                        
                            <a class="nav-link" href="inscription.php">S\'inscrire</a>
                            
                        </li>
                    
                        <li class="nav-item active">
                            <a class="nav-link" href="connexion.php">Se connecter</a>
                        </li>
                        <span class="sr-only">(current)</span>';
                    }
                    ?>
                </ul>
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
                    <form action="profil.php" method="post">
                        <fieldset >
                       <!-- envoyer un message d'erreur si login existe déjà ou si password invalide-->
                       <?php if(!empty($error)){echo '<p class="h4 text-warning">'.$error.'</p>'; } ?> 
                    
                        <div class="form-group">
                        <label for="login">Identifiant</label>
                        <input type="txt" class="form-control" id="login" name="login" 
                        value="<?php echo $login; ?>" required>
                        </div>   

                        <div class="form-group">
                        <label for="password">Mot de passse</label>
                        <input type="password" class="form-control" id="password" 
                        name="password" placeholder="Entrer un nouveau mot de passe" required>
                        </div>                       
                        <div class="form-group">
                        <label for="conf-password">Confirmer votre nouveau mot de passe</label>
                        <input type="password" class="form-control" id="conf-password" 
                        name="conf-password" placeholder="Mot de passe identique" required>
                        </div>                                            
                        <input type="hidden" name="id" value="<?php echo (int)$id;// conserve la valeur id dans un champs caché du formulaire
                        ?>">                           
                                                                    
                        <button type="submit" class="btn btn-secondary" name="submit">Valider</button>
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