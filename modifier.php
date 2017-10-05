<?php 
  session_start();
  include 'include/functions.php'; 
  if(isConnect() && (isset($_GET["f"]) || isset($_POST['enregistrer'])))
  {
    if(isset($_GET["f"])) 
    {
      $recherche = $_GET["f"];
      if ($lienImage = getPhoto($recherche)) //Recherche la photo demandée
      { 
        $infoPhoto = getInfoPhoto($lienImage); //Retourne les infos de la photo
        $titre = $infoPhoto['titre'];
        $petite_descript = $infoPhoto['petite_descript'];
        $longue_descript = $infoPhoto['longue_descript'];
        $statut = $infoPhoto["statut"];

        $auteur = basename(dirname($lienImage)); //Auteur est le nom du dossier dans le quel se trouve la photo 
        $infoAuteur = getInfoUser($auteur); 	//On cherche les infos de l'auteur
        
        if($infoAuteur['nomutili'] != $_SESSION['nomutili'])	//si c'est pas lui l'auteur de la photo on le redirige car pas accès
          header("Location: galerie.php?u=".$infoAuteur["nomutili"]);
       
        if(isset($_GET['sup']) && $_GET["sup"] == "oui")	//SI c'est pour supprimer la photo
        {
          unlink($lienImage);
          unlink(getLinkForTXT($lienImage));
          $lienlike = substr_replace($lienImage , '_likes.txt', strrpos($lienImage , '.'));

          unlink($lienlike);
          header("Location: galerie.php?u=".$_SESSION['nomutili']);

        }
      }
    }
    if(isset($_POST["enregistrer"]))
    {
      $titre = $_POST['titre'];
      $petite_descript = $_POST['petite_descript'];
      $longue_descript = $_POST['longue_descript'];
      $statut = $_POST["statut"];

      $error['titre'] = "";
      $error['petite_descript'] ="";
      $error['longue_descript'] = "";
      $regtitre = "/^[a-zA-Z0-9._-]$/";

      if(empty($titre))
        $error["titre"] = "Le titre ne peut pas être vide.";
      else if (strlen($titre) > 70)
      	$error["titre"] = "Le titre ne peut pas être aussi long.";

      if(empty($petite_descript))
        $error["petite_descript"] = "La petite description ne peut pas être vide.";
      else if(strlen($petite_descript) > 400)
      	$error["petite_descript"] = "La description courte ne peut pas être aussi longue. Au maximum 400 caractères.";
      else if(strlen($petite_descript) > strlen($longue_descript))
      	$error["petite_descript"] = "La description courte doit être plus petite que la longue description";  
      	
      if (empty($longue_descript))
        $error["longue_descript"] = "La description longue ne peut pas être vide.";
      else if(strlen($longue_descript) > 800)
      	$error["petite_descript"] = "La description longue ne peut pas être aussi longue. Au maximum 800 caractères.";
      

      $bon = 1;
      foreach ($error as $err)
      {
        if(empty($err))
          $bon *= 1;
        else
          $bon = 0;
      }
      if($bon)
      {
          $lienImage = $_POST["lienImage"];
          unset($_POST['lienImage']);
          unset($_POST['enregistrer']);
          htmlspecial_array($_POST); //XSS

          $infoPhoto = serialize($_POST);
          unlink(getLinkForTXT($lienImage));	//Suprime l'ancien TXT de la photo
          file_put_contents(getLinkForTXT($lienImage),$infoPhoto); 	//On recrée un nouveau
          header("Location: galerie.php?u=".$_SESSION['nomutili']);

      }      
    }
  }
  else
    header("Location: explorer.php");




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
    <div class="container animated fadeIn">
      <?php 
      if (!empty($lienImage)) :
      ?>
      <header class="row">
        <div class="col-md-4">
          <img src="thumbnail.php?f=<?php echo $lienImage; ?>&max=600" class="img-rounded img-responsive" />
        </div>
        <div class="col-md-8">
          <article class="panel panel-default">   
            <form method="POST" action="modifier.php?f=<?php echo $_GET['f']; ?>">
              <div class="panel-body">
                <h1>Modification</h1>
                <div class="form-group <?php if (!empty($error['titre'])) echo "has-error"; ?>">
                  <?php if (!empty($error['titre'])) echo '<label class="control-label" for="titre">'.$error['titre'].'</label>'; ?>
                  <input type="text" class="form-control" name="titre" placeholder="Titre" value="<?php if(isset($titre)) echo $titre;?>" autofocus>
                </div>

                <div class="form-group <?php if (!empty($error['petite_descript'])) echo "has-error"; ?>">
                  <?php if (!empty($error['petite_descript'])) echo '<label class="control-label" for="petite_descript">'.$error['petite_descript'].'</label>'; ?>
                    <textarea class="form-control" rows="3"  name="petite_descript" placeholder="Description courte"><?php if (isset($petite_descript)) echo $petite_descript; ?></textarea>
                </div>

                <div class="form-group <?php if (!empty($error['longue_descript'])) echo "has-error"; ?>">
                  <?php if (!empty($error['longue_descript'])) echo '<label class="control-label" for="longue_descript">'.$error['longue_descript'].'</label>'; ?>
                  <textarea class="form-control" rows="5" name="longue_descript" placeholder="Description longue"><?php if (isset($longue_descript)) echo $longue_descript; ?></textarea>
                </div>

                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="statut" value="public" <?php if ($statut == "public") echo "checked"; ?>>
                      <i class="fa fa-eye"></i> Publique
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="statut" value="prive" <?php if ($statut == "prive") echo "checked"; ?>>
                     <i class="fa fa-eye-slash"></i> Privée 
                    </label>
                  </div>         
                </div>
              </div> 
              <div class="panel-footer">
              <input type="hidden" name="lienImage" value="<?php if (isset($lienImage)) echo $lienImage; ?>">
              <input type="submit" class="btn btn-success" name="enregistrer" value="Enregistrer">
              <a href="galerie.php?u=<?php echo $_SESSION["nomutili"]; ?>" class="btn btn-default">Annuler</a>
              <a href="modifier.php?f=<?php echo $_GET["f"]."&sup=oui"; ?>" class="btn btn-danger pull-right "><i class="fa fa-trash"></i> Supprimer</a>
              </div> 
            </form>      
          </article>
        </div>
      </header>

      <?php 
      else :
      ?>
      <header class="row">
        <div class="col-md-12">
          <h1>La photo recherchée n'existe pas.
          </h1>
        </div>
      </header>
      <?php 
      endif;
      ?>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
