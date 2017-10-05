<?php
  session_start(); 
  include 'include/functions.php';

  $photos = getPhotosFrom('*'); 							//toutes les photos
  $photos = removePrivate($photos);							//supprime les privées
  $photos = sortArray($photos);								//trie les photos chronologiquement sans perdre les chemins d'accès

  $infoPhoto = getInfoPhoto($photos[0]);					//info de la première photo du tableau
  $infoAuteur = getInfoUser(basename(dirname($photos[0])));	//l'auteur est le nom du dossier dans le quel est la photo
  $detail = getLinkForDetail($photos[0]);					//avoir le lien pour le bouton en savoir plus
?>
<!DOCTYPE html>
<html lang="en" class="index">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Flogram</title>

  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/theme.css" rel="stylesheet">
  <link href="assets/font_awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/animate/animate.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php echo '<style>.index{background:url(thumbnail.php?f='.$photos[0].'&max=2000)no-repeat center center fixed;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;position:fixed;}</style>'; //Modifie avec du css le fond de la page Désolé?>
</head> 

  
  <body class="index bod">
    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container ">
        <?php include 'include/nav.php'; ?>

          <div class="inner cover animated fadeIn">
            <div class="lead">
              <?php if (isConnect()) : ?>

              <h1 class="cover-heading">Bonjour <?php echo getInfoUser($_SESSION['nomutili'])['prenom']; ?> </h1>
              <p>Quelle photo allez-vous ajouter aujourd'hui ?</p>
              <a href="upload.php" class="btn btn-lg btn-primary btn-outline">Ajouter une photo</a>
              <?php else : ?>
              <h1 class="cover-heading">Bienvenue sur Flogram</h1>
              <a href="inscription.php" class="btn btn-lg btn-success btn-outline">S'inscrire</a>
              <a href="connexion.php" class="btn btn-lg btn-primary btn-outline">Se connecter</a>
              <?php endif; ?>
            </div>
            <div class="lead">
            </div>
          </div>

          <div class="mastfoot">
          	<div class="day">
              La photo du jour est '<?php echo $infoPhoto['titre']."' de ".$infoAuteur["prenom"]. " ".$infoAuteur["nom"]; ?>. <a href="detail.php?f=<?php echo $detail; ?>">En savoir plus</a>
          	</div>
            <div class="">
              Flogram est basé sur <a href="http://getbootstrap.com">Bootstrap</a>, par <a href="https://twitter.com/fllorent0d">Florent Cardoen</a>
            </div>
          </div>
        </div>
      </div>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
