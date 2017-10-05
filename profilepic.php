<?php 
  session_start();
  include 'include/functions.php'; 
  if(isConnect())
  {
	$lienImage = profilePic($_SESSION['nomutili']);

    if(isset($_POST["enregistrer"])) //Enregistrer la photo
    {
    	$photo = $_FILES['photo'];
		$extensions = pathinfo($photo["name"],PATHINFO_EXTENSION);

		if(empty($photo["name"]))
			$error["photo"] = "Vous devez choisir une photo.";
		else if($extensions != "jpg" && $extensions != "png" && $extensions != "jpeg" && $extensions != "gif" && $extensions != "JPG" && $extensions != "PNG" &&$extensions != "JPEG" && $extensions != "GIF" ) 
			$error['photo'] = "Seulement les formats JPG, JPEG, PNG et GIF sont acceptés.";
		else if ($photo["size"] > 8000000) 
			$error['photo'] = "La photo est trop grosse.";
		
		if(empty($error))
		{
			unlink($lienImage);
			move_uploaded_file($photo["tmp_name"], 'utilisateurs/'.$_SESSION['nomutili'].'/profile/profile.'.$extensions);
			$lienImage = profilePic($_SESSION['nomutili']);

		}
    }
  }
  else
    header("Location: index.php");




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
            <form method="POST" action="profilepic.php"  enctype="multipart/form-data">
              <div class="panel-body">
                <h2>Modifier ma photo de profil</h2>
              </div> 
              <div class="form-group <?php if (!empty($error['photo'])) echo "has-error"; ?>"
	             <label class="control-label" for="enregistrer">
                  <?php if (!empty($error['photo'])) echo '<label class="control-label" for="photo">'.$error['photo'].'</label>'; ?>	             	
	              	<input type="file" name="photo">
	             </label>
              </div>
              <div class="panel-footer">
              <input type="submit" class="btn btn-success" name="enregistrer" value="Enregistrer">
              <a href="galerie.php?u=<?php echo $_SESSION["nomutili"]; ?>" class="btn btn-default">Retour</a>
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
