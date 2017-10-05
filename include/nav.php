<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<div class="container">
<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span> <!---- Accueil/ajoutet/ma gallerie/membre/rechercher -->
    <span class="icon-bar"></span>
   </button> 
     
    </div> 
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      	<li><a href="index.php">Accueil</a></li>
      <?php if (isConnect()) : ?>
		<li><a href="upload.php">Ajouter une photo</a></li>
        <li><a href="galerie.php?u=<?php echo $_SESSION["nomutili"]; ?>">Ma galerie</a></li>
      
      <?php else: ?>
        <li><a href="inscription.php">S'inscrire</a></li>
        <li class="visible-sm visible-md"><a href="connexion.php">Se connecter</a></li>
       <?php endif; ?>
        <li><a href="membres.php">Membres</a></li>
        <li><a href="decouvrir.php">Rechercher</a></li>
        
      </ul>
        
      <?php 
      if (!isConnect()) : 
      ?>      

      <form class="navbar-form navbar-right hidden-sm hidden-md" role="form" action="connexion.php" method="POST">
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

      <ul class="nav navbar-nav navbar-right">
     	<li><a href="galerie.php?u=<?php echo $_SESSION['nomutili']; ?>"><img src="thumbnail.php?f=<?php echo profilePic($_SESSION['nomutili']); ?>&max=50" class="img-rounded"> <?php echo $_SESSION['nomutili']; ?></a></li>
        <li class="dropdown">
           <a href="#"><i class="fa fa-cog"></i></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="profilepic.php">Changer ma photo de profil</a></li>
            <li><a href="include/deco.php">Déconnexion</a></li>
          </ul>
        </li>
      </ul>
      <?php endif; ?>
    </div>
  </div>
</nav>