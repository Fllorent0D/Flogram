<?php 
  session_start();
  include "include/functions.php";
  if(!isConnect()) 
    header("Location: index.php");
      
  include "include/ajout_photo.php";
?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <section class="well col-md-8 col-md-offset-2">
      <header class="heading">
        <h1>Ajouter une photo</h1>
      </header>
      <form method="POST" action="upload.php" enctype="multipart/form-data">
        <div class="form-group <?php if (!empty($error['photo'])) echo "has-error"; ?>">
          <?php if (!empty($error['photo'])) echo '<label class="control-label" for="photo">'.$error['photo'].'</label>'; ?>
          <input type="file" name="photo" value="Choisir un fichier"/>
        </div>
        <div class="form-group <?php if (!empty($error['titre'])) echo "has-error"; ?>">
          <?php if (!empty($error['titre'])) echo '<label class="control-label" for="titre">'.$error['titre'].'</label>'; ?>
          <input type="text" class="form-control" name="titre" placeholder="Titre" value="<?php if (isset($_POST['titre'])) echo $_POST['titre']; ?>" autofocus/>
        </div>

        <div class="form-group <?php if (!empty($error['petite_descript'])) echo "has-error"; ?>">
          <?php if (!empty($error['petite_descript'])) echo '<label class="control-label" for="petite_descript">'.$error['petite_descript'].'</label>'; ?>
            <input type="text" class="form-control" name="petite_descript" placeholder="Description courte" value="<?php if (isset($_POST['petite_descript'])) echo $_POST['petite_descript']; ?>"/>
        </div>

        <div class="form-group <?php if (!empty($error['longue_descript'])) echo "has-error"; ?>">
          <?php if (!empty($error['longue_descript'])) echo '<label class="control-label" for="longue_descript">'.$error['longue_descript'].'</label>'; ?>
          <textarea class="form-control" rows="5" name="longue_descript" placeholder="Description longue"><?php if (isset($_POST['longue_descript'])) echo $_POST['longue_descript']; ?></textarea>
        </div>

        <div class="form-group">
          <div class="radio">
            <label>
              <input type="radio" name="statut" value="public" checked>
              <i class="fa fa-eye"></i> Publique
            </label>
          </div>
          <div class="radio">
            <label>
              <input type="radio" name="statut" value="prive">
              <i class="fa fa-eye-slash"></i> Privée
            </label>
          </div>         
        </div>


        <?php  if(isset($error['creation'])) echo '<div class="alert alert-danger" role="alert">'.$error['creation'].'</div>' ?>
        <div class="form-group right">
          <input type="submit" class="btn btn-primary" name="envoyer" value="Ajouter à ma galerie" />
        </div>

      </form>
    </section>
  </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
