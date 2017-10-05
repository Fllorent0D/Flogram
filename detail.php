<?php 
  session_start();
  include 'include/functions.php'; 

  if (isset($_GET["f"]))
  {
    $recherche = $_GET["f"];
    if ($lienImage = getPhoto($recherche))
    {	
    	$infoPhoto = getInfoPhoto($lienImage);
    	$auteur = basename(dirname($lienImage));  
    	$infoAuteur = getInfoUser($auteur);
  	}
  	else
  		header('Location: decouvrir.php');
  	if(isset($_GET["like"]))
  	{
  		if(isConnect())
  		{
  			addRemoveLike($_SESSION['nomutili'], $lienImage);
  			header('Location: detail.php?f='.$recherche);
  		}	
  		else
  			header('Location: connexion.php');
	}

  }
  else
    header("Location: decouvrir.php");
?>
<!DOCTYPE html>
<html>
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
</head>	

<body class="smallpadding">
    <?php include 'include/nav.php'; ?>
    <section class="container-fluid nopadding animated fadeIn">
      <div class="row">
        <div class="image-viewer">
          <div class="img">
           <img class="ri" src="<?php echo $lienImage; ?>" alt="<?php echo $infoPhoto['titre']; ?>" >
          </div>
        </div>
      </div>
    </section>
    <section class="row">
      <div class="container">
        <header>  
          <h1>
              <?php echo $infoPhoto["titre"]; ?>
             <span class="btn-group pull-right">

              <div class="btn-group" role="group">
				  <div class="btn-group dropup">
				    <a href="detail.php?f=<?php echo $_GET["f"]?>&amp;like" class="btn btn-success btn-outline like"><?php echo count(getLikes($lienImage));?> <i class="fa fa-heart"></i></a>
				    <ul class="dropdown-menu">
				      <?php
		              	$likes = getLikes($lienImage);
		              	if($likes)
		              	{
			              	foreach($likes as $like)
			              	{
			              		$info = getInfoUser($like);
			              		
				              	echo '<li><a href="galerie?u='.$like.'">'.$info['prenom'].' '.$info['nom'].'</a></li>';
			              	}
						}
						else
						{
							echo "<li><a>Personne n'a encore aimé cette photo.</a></li>";
						}
		              	?>
				    </ul>
				  </div>
				  <a class="btn btn-default" href="galerie.php?u=<?php echo $infoAuteur["nomutili"]; ?>"><i class="fa fa-navicon"></i> Galerie de <?php echo $infoAuteur["prenom"]. " " .$infoAuteur["nom"]; ?></a>				

				</div>
              	
              	
              </span>
          </h1>
        </header>
      </div>
      
      <div class="row">
        <div class="container">
          <div class="col-md-3">
            <article class="panel panel-primary">
              <header class="panel-heading">
                <h2 class="panel-title">Auteur</h2>
              </header>
              <div class="panel-body">
               <p><?php echo $infoAuteur["prenom"]. " ".$infoAuteur["nom"];?></p>
              </div>        
            </article>
            <article class="panel panel-primary">
              <header class="panel-heading">
                <h2 class="panel-title">Ajouté le</h2>
              </header>            
              <div class="panel-body">
               <p><?php echo date("Y/m/d  H:i:s", ($_GET['f']/100))?><p>
              </div>        
            </article>
          </div>
          <div class="col-md-6">
            <article class="panel panel-primary">
              <header class="panel-heading">
                <h2 class="panel-title">Description courte</h2>
              </header>
              <div class="panel-body">
                <p><?php echo $infoPhoto["petite_descript"]; ?></p>
              </div>        
            </article>
            <article class="panel panel-primary">
              <header class="panel-heading">
                <h2 class="panel-title">Description longue</h2>
              </header>
              <div class="panel-body">
                <p><?php echo $infoPhoto["longue_descript"]; ?></p>
              </div>        
            </article>
            <article class="panel panel-primary">
              <header class="panel-heading">
                <h2 class="panel-title">Commentaires</h2>
              </header>
              <div class="panel-body">
              <?php
		      $einfo = $exif['COMMENT'];
				  
			  if ($einfo != 0)
			  {
				  foreach($einfo as $tag)
				  {
					  echo "<li>".$tag."</li>";
				  }
			  	  
			  }
			  else
			  {
			  		
				  echo "<p>Aucun commentaire n'a été trouvé.";
			  }

              ?>
              </div>        
            </article> 
          </div>
          <div class="col-md-3">
            <article class="panel panel-primary">
              <header class="panel-heading">
                <h2 class="panel-title">Propriétés</h2>
              </header>
              <div class="panel-body">
              <?php
			 
              $exif = getExifDetails($lienImage);
			  if ($exif)
			  {
			  	  $einfo['Marque'] = $exif['Make'];
	              $einfo["Modèle"] = $exif['Model'];
	              $einfo["Temps d'exposition"] = $exif['ExposureTime'];           
	              $einfo["Ouverture"] = $exif['FNumber']; 
	              $einfo["Sensibilité ISO"] = $exif['ISOSpeedRatings']; 
	              $einfo["Vitesse d'obturation"]=$exif['ShutterSpeedValue'];
	              $einfo['Date originale'] = $exif["DateTimeOriginal"]; 
	              
	              if(!array_filter($einfo))
	              {
		              echo '<p>Aucun détail n\'a été trouvé</p>';
	              }   
	              else
	              {   
	              	  echo '<ul>';  
		              foreach ($einfo as $clef => $valeur) 
		              {
		                echo '<li><small>'.$clef.' : '.$valeur.'</small></li>';
		              }
		              echo '</ul>';
				  }
			  }
			  else
			  {
			  		
				  echo "<p>Aucun détail n'a été trouvé.";
			  }

              ?>
              </div>        
            </article>

          </div>
        </div>
      </div>
    </section>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
