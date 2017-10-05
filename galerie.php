<?php
  session_start(); 
  include 'include/functions.php';

  if(isset($_GET['u']) && !empty($_GET['u']))
  {
    $edit = false;
    $galerieDe = $_GET["u"];
    if (isUser($galerieDe))
    {
      $infoAuteur = getInfoUser($galerieDe);
      $photos = getPhotosFrom($galerieDe);

      if(isConnect() && strtolower($galerieDe) == strtolower($_SESSION['nomutili']))
      {
          $titre = "Ma galerie";
          $edit = true;
      }
      else
      {
        $titre = "Galerie de ".$infoAuteur["prenom"]." " .$infoAuteur["nom"];
		$edit = false;
      }

      if(count($photos) == 0)
        $titre = $infoAuteur["prenom"]." " .$infoAuteur["nom"]." n'a aucune photo.";

    }
    else
      $titre ="Cette utilisateur n'existe pas.";
    
  }
  else
  {
    if(isConnect())
      header("Location: galerie.php?u=".$_SESSION["nomutili"]);
    else
      header("Location: decouvrir.php");
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
  <link href="assets/font_awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/animate/animate.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>	

<body>
    <?php include 'include/nav.php'; ?>
    <section class="container animated fadeIn well">
        <section class="row">
          <div class="col-md-12">
            <header>
             <h1><?php echo $titre; ?></h1>
            </header>
          </div>
        </section>
        <section class="row">
        <?php  
        if($photos)
        {
          if (!$edit)
          	$photos = removePrivate($photos);

          for($i = 0, $colonnes = 3, $total_photos = count($photos); $i < $colonnes; $i++)
          {
            echo '<div class="col-md-'.(12/$colonnes).'">';
            
            for ($j = $i; $j < $total_photos; $j+=$colonnes)
            {
              $infoPhoto = getInfoPhoto($photos[$j]);
              $detail = getLinkForDetail($photos[$j]);
              $likes = count(getLikes($photos[$j]));
              $editButton = '';
              $statut ='';
              if($infoPhoto['statut'] == "prive")
                $statut ="-slash";
              if ($edit)
                $editButton = '<a href="modifier.php?f='.$detail.'" class="close pull-right"><i class="fa fa-eye'.$statut.'"></i> <i class="fa fa-pencil"></i></a> ';

              echo '  <article class="panel panel-default ">
                        <header class="panel-heading">
                         '.$editButton.' 
                          <a href="detail.php?f='.$detail.'"><h3 class="panel-title">'.$infoPhoto["titre"].'</h3></a>
                        </header>
                        <div class="panel-body">
                          <a href="detail.php?f='.$detail.'"><img src="thumbnail.php?f='.urlencode($photos[$j]).'&amp;max=600" class="img-rounded img-responsive" alt="'.$infoPhoto['titre'].'"></a>
                        </div>
                        <div class="panel-footer">
                        <a class="close pull-right" href="detail.php?f='.$detail.'&amp;like">'.$likes.' <i class="fa fa-heart"></i> </a>
                          '.$infoPhoto["petite_descript"].'
                         
                        </div>    
                      </article>';  
            }
            echo '</div>';
        
          }
        }
        else
        {
	        echo '<header><h4>Il n\'y a pas de photo dans cette galerie.</h4></header>';
        }
        ?>
        

        </section>
    </section>
</body>

</html>
