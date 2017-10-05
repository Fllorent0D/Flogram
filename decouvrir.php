<?php
  session_start(); 
  include 'include/functions.php';

  $photos = getPhotosFrom('*');	//Retrouve les toutes les photos
  $photos = removePrivate($photos); //Supprime les privées
  $photos = sortArray($photos);	//Fonction un peu spécial pour trier les photos dans l'ordre chronologique pour ne pas perdre les chemins d'accès
  $def = getDefinitions();	//retourne les différentes definitions de photos
  
  /* Par défaut */
  $colonnes = 3;
  $definition = 'all';
  $format = 'all';
  $tris = 'recent';
  $titre = '';
  
  if(!empty($_POST))
  {
  	  if($_POST['colonne'] != 3)
	  	$colonnes = $_POST['colonne'];
	  	
	  $format = $_POST['format'];
	  if ($format != 'all')
	  	$photos = keepExt($photos, $format);
	  
	  $definition = $_POST['def'];
	  if($definition != 'all')
	  	$photos = keepSize($photos, $definition);
      
	  $titre = $_POST['titre'];
	  $reg = '#'.str_replace(' ', '|', $titre).'#i'; // Crée une regex
	  $photos = searchTitle($reg, $photos);
	  
	  $tris = $_POST['tris'];
	  switch($tris)
	  {
		  case "aleatoire":
		  	shuffle($photos);
		  	break;
		  case "vieux":
		  	$photos = array_reverse($photos);
		  	break;
	  }
	    
  }
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
  <link href="assets/css/label.min.css" rel="stylesheet">
  <link href="assets/font_awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/animate/animate.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head> 

<body>
    <?php include 'include/nav.php'; ?>
    <section class="container animated fadeIn">
      <section class="row well">
            <header>
              <h1>Rechercher</h1>
            </header>
				<form class="form-inline" method="POST" action="decouvrir.php">

            <div class="row left">
	            <div class="form-group">
	              <div class="input-group">
	                <label>Titre</label>
	                <input type="text" name="titre" class="form-control" value="<?php echo $titre; ?>" placeholder="Chercher un titre">
	              </div>
	            </div>
            </div>
            <br>
             <div class="row left">
            	 <div class="form-group">
	              <div class="input-group">
	                <label>Tri</label>
	                <select class="form-control" name="tris">
	                  <option value="recent" <?php if($tris == 'recent') echo "selected" ?>>Les plus récentes en premier</option>
	                  <option value="vieux" <?php if($tris == 'vieux') echo "selected" ?>>Les plus anciennes en premier</option>
	                  <option value="aleatoire" <?php if($tris == 'aleatoire') echo "selected" ?>>Aléatoire</option>
	                </select>
	              </div>
	            </div>
	            <div class="form-group">
	              <div class="input-group">
	                <label>Définition</label>
	                <select class="form-control" name="def">
	                  <option value="all" selected>Toutes</option>
	                  <?php
	                  foreach ($def as $taille) 
	                  { 
	                    $select = "";
	                    if($definition == $taille)
	                      $select = "selected";
	                    echo '<option value="'.$taille.'" '.$select.'>'.$taille.'</option>';
	                  }
	                  ?>
	                </select>
	              </div>
	            </div>
	            <div class="form-group">
	              <div class="input-group">
	                <label>Format</label>
	                <select class="form-control" name="format">
	                  <option value="all" <?php if($format == 'all') echo "selected" ?>>Tous</option>
	                  <option value="jpg" <?php if($format == 'jpg') echo "selected" ?>>jpg</option>
	                  <option value="png" <?php if($format == 'png') echo "selected" ?>>png</option>
	                  <option value="gif" <?php if($format == 'gif') echo "selected" ?>>gif</option>
	                </select>
	              </div>
	            </div>
	            <div class="form-group hidden-sm hidden-xs">
	              <div class="input-group">
	                <label>Taille des miniatures</label>
	                <select class="form-control" name="colonne">
	                  <option value="6" <?php if($colonnes == 6) echo "selected" ?>>Minuscule</option>
	                  <option value="4" <?php if($colonnes == 4) echo "selected" ?>>Petite</option>
	                  <option value="3" <?php if($colonnes == 3) echo "selected" ?>>Moyenne</option>
	                  <option value="2" <?php if($colonnes == 2) echo "selected" ?>>Grande</option>
	                </select>
	              </div>
	            </div>

            </div>
			<br>

            <div class="form-group">
              <div class="form-input">
               <input type="submit" class="btn btn-primary" name="valider" value="Rechercher les photos">
               <a href="decouvrir.php" class="btn btn-warning ">Réinitialiser</a>
              </div>
            </div>
			</form>
			</div>
      </section>
	  <section class="row">
      <?php  
      if($photos)
      {
        echo '<div class="container-fluid">';
        for($i = 0, $total_photos = count($photos); $i < $colonnes; $i++)
        {
          echo '<div class="nopadding col-md-'.(12/$colonnes).'">';
          
          for ($j = $i; $j < $total_photos; $j+=$colonnes)
          {
            $infoPhoto = getInfoPhoto($photos[$j]);
            $detail = getLinkForDetail($photos[$j]);
            
            echo '
            <div class="spacing"> 
            <a href="detail.php?f='.$detail.'">
              <figure class="label bottom inside float" data-label="'.$infoPhoto['titre'].'">
                 <img src="thumbnail.php?f='.$photos[$j].'&amp;max=600" class="img-responsive" alt="'.$infoPhoto['titre'].'">
              </figure>
            </a>
            </div>';  
          }
          echo '</div>';

        }
        echo '</div>';
     }
     else
      echo '<h1>Pas de photos trouvées</h1>';
      ?>
      

    </section>
  </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
