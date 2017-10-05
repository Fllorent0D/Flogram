<?php 
  session_start();

  include 'include/functions.php'; 
  if(isConnect())
    header("Location: index.php");

  include 'include/ajout_membre.php';
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
      <section class="col-md-8 col-md-offset-2 well">
        <header class="heading">
          <h1>Inscription</h1>
        </header>
        <p>Pour vous inscrire et commencer votre galerie personnelle il faut remplir ce formulaire d'inscription.</p>
        <form method="POST" action="inscription.php">
          <div class="form-group <?php if (!empty($error['prenom'])) echo "has-error"; ?>">
            <?php if (!empty($error['prenom'])) echo '<label class="control-label" for="prenom">'.$error['prenom'].'</label>'; ?>
            <input type="text" class="form-control" name="prenom" placeholder="Prenom" value="<?php if (isset($_POST['prenom'])) echo $_POST['prenom']; ?>" autofocus/>
          </div>

          <div class="form-group <?php if (!empty($error['nom'])) echo "has-error"; ?>">
            <?php if (!empty($error['nom'])) echo '<label class="control-label" for="nom">'.$error['nom'].'</label>'; ?>
            <input type="text" class="form-control" name="nom" placeholder="Nom" value="<?php if (isset($_POST['nom'])) echo $_POST['nom']; ?>" />
          </div>

          <div class="form-group <?php if (!empty($error['email'])) echo "has-error"; ?>">
            <?php if (!empty($error['email'])) echo '<label class="control-label" for="email">'.$error['email'].'</label>'; ?>
            <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"/>
          </div>

          <div class="form-group <?php if (!empty($error['nomutili'])) echo "has-error"; ?>">
            <?php if (!empty($error['nomutili'])) echo '<label class="control-label" for="nomutili">'.$error['nomutili'].'</label>'; ?>
            <input type="text" class="form-control" name="nomutili" placeholder="Nom d'utilisateur" value="<?php if (isset($_POST['nomutili'])) echo $_POST['nomutili']; ?>" />
          </div>

          <div class="form-group <?php if (!empty($error['mdp'])) echo "has-error"; ?>">
            <?php if (!empty($error['mdp'])) echo '<label class="control-label" for="mdp">'.$error['mdp'].'</label>'; ?>
            <input type="password" class="form-control" name="mdp" placeholder="Mot de passe" />
          </div>

          <div class="form-group <?php if (!empty($error['mdp_confirm'])) echo "has-error"; ?>">
            <?php if (!empty($error['mdp_confirm'])) echo '<label class="control-label" for="mdp_confirm">'.$error['mdp_confirm'].'</label>'; ?>
            <input type="password" class="form-control" name="mdp_confirm" placeholder="Confirmation de mot de passe" />
          </div>
          
          <?php  if(isset($error['creation'])) echo '<div class="alert alert-danger" role="alert">'.$error['creation'].'</div>' ?>
          <div class="form-group right">
            <input type="submit" class="btn btn-primary" name="valider" value="S'enregistrer" />
          </div>

        </form>
      </section>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
