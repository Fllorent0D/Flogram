<?php
  session_start();
  include 'include/functions.php'; 
  if(isConnect())
    header("Location: index.php");
  
  include 'include/connexion_membres.php';
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
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head> 

<body>
    <?php include 'include/nav.php'; ?>

    <div class="container">
      <section class="well col-md-6">
        <header class="heading">
          <h2>Se connecter</h2>
        </header>
        <h4>Nous vous attendons pour continuer votre galerie de photos</h4>
        <form method="POST" action="connexion.php">
          <div class="form-group <?php if (!empty($error['nomutili'])) echo "has-error"; ?>">
            <?php if (!empty($error['nomutili'])) echo '<label class="control-label" for="nomutili">'.$error['nomutili'].'</label>'; ?>
            <input type="text" class="form-control" name="nomutili" placeholder="Nom d'utilisateur" value="<?php if (isset($_POST['nomutili'])) echo $_POST['nomutili']; ?>" />
          </div>
          <div class="form-group <?php if (!empty($error['mdp'])) echo "has-error"; ?>">
            <?php if (!empty($error['mdp'])) echo '<label class="control-label" for="mdp">'.$error['mdp'].'</label>'; ?>
            <input type="password" class="form-control" name="mdp" placeholder="Mot de passe" />
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="souvenir"> Se souvenir de moi
            </label>
          </div>
          <?php  if(isset($error['connexion'])) echo '<div class="alert alert-danger" role="alert">'.$error['connexion'].'</div>' ?>
          <div class="form-group right">
            <input type="submit" class="btn btn-primary pull-right" name="valider" value="Se connecter" />
          </div>

        </form>
      </section>
      <section class="well col-md-5 col-md-offset-1">
        <header class="heading">
          <h2>Pas encore de compte?</h2>
        </header>
        <a href="inscription.php" class="btn btn-success">Créer un compte</a>
      </section>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
