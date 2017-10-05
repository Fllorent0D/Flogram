<?php 
session_start();
include '../include/functions.php'; 
$currentDomain = 'http://'.$_SERVER['SERVER_NAME'].'/';

?>
<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Flogram</title>

	<link href="<?php echo $currentDomain; ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $currentDomain; ?>assets/css/theme.css" rel="stylesheet">
	<link href="<?php echo $currentDomain; ?>assets/font_awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo $currentDomain; ?>assets/animate/animate.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>	

<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<div class="container">
<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
   </button> 
      <a class="navbar-brand" href="<?php echo $currentDomain; ?>">Flogram</a>
    </div> 
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo $currentDomain; ?>decouvrir.php">Rechercher</a></li>
        <li><a href="<?php echo $currentDomain; ?>membres.php">Membres</a></li>
      <?php 
      if (!isConnect()) : 
      ?>      
        <li><a href="<?php echo $currentDomain; ?>inscription.php">S'inscrire</a></li>
        <li class="visible-sm visible-md"><a href="connexion.php">Se connecter</a></li>
      </ul>
      <form class="navbar-form navbar-right hidden-sm hidden-md" role="form" action="<?php echo $currentDomain; ?>connexion.php" method="POST">
        <div class="form-group">
          <input type="text" placeholder="Nom d'utilisateur" name="nomutili" class="form-control">
        </div>
        <div class="form-group">
          <input type="password" placeholder="Mot de passe" name="mdp" class="form-control">
        </div>
        <div class="checkbox-nav checkbox">
          <label>
            <input type="checkbox" name="souvenir"> Se souvenir de moi
          </label>
        </div>
        <button type="submit" name = "valider" class="btn btn-info">Se connecter</button>
      </form>
      <?php 
      else :
      ?>
        <li><a href="<?php echo $currentDomain; ?>galerie.php?u=<?php echo $_SESSION["nomutili"]; ?>">Ma galerie</a></li>
        <li><a href="<?php echo $currentDomain; ?>upload.php">Ajouter une photo</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#"><?php echo $_SESSION['nomutili']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $currentDomain; ?>profilepic.php">Changer ma photo de profil</a></li>
            <li><a href="<?php echo $currentDomain; ?>include/deco.php">Déconnexion</a></li>
          </ul>
        </li>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</nav>    
	<section class="container animated fadeIn">
    	<div class="well">
        <section class="row">
          <div class="col-md-12 text-center">
            <header>
             <h1>404 - La page n'a pas été trouvée...</h1>
             <img src="<?php echo $currentDomain; ?>assets/img/404.jpeg" class="ri">
             <h2>Je pense donc que vous êtes perdu</h2>
            </header>
            <a href="<?php echo $currentDomain; ?>" class="btn btn-primary">Retourner à la page d'accueil</a>
            
          </div>
        </section>
      </div>
    </section>
</body>

</html>
