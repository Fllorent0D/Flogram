<?php
if (isset($_POST['valider']))
{
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $nomutili = strtolower($_POST['nomutili']); //On le stocke en minuscule
    $mdp = $_POST['mdp'];
    $mdp_confirm = $_POST['mdp_confirm'];
    
    $error['prenom'] = "";
    $error['nom'] = "";
    $error['email'] = "";
    $error['nomutili'] = "";
    $error['mdp'] = "";
    $error['mdp_confirm'] = "";

    //$regnom = "#^[[:alpha:]]+([\-\' ][[:alpha:]]+)*$#";
    $regnom = "#^[a-zA-ZÀ-ÿ\s\'-]{1,15}$#";
    $regmail = "#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#";
    $reguser = "#^[a-zA-Z0-9._-]{4,18}$#";

    if(empty($prenom))
      $error['prenom'] = "Le prénom ne peut pas être vide.";
    else if (!preg_match($regnom, $prenom))
      $error['prenom'] = "Le prénom ne peut contenir que des lettres.";

    if(empty($nom))
      $error['nom'] = "Le nom ne peut pas être vide.";
    else if (!preg_match($regnom, $nom))
      $error['nom'] = "Le nom ne peut contenir que des lettres.";

    if(empty($email))
      $error['email'] = "L'addresse email ne peut pas être vide.";
    else if (!preg_match($regmail, $email))
      $error['email'] = "L'adresse email doit respecter ce format xyz@exemple.com.";

    if(empty($nomutili))
      $error['nomutili'] = "Le nom d'utilisateur ne peut pas être vide.";
    else if (!preg_match($reguser, $nomutili))
      $error['nomutili'] = "Votre nom d'utilisateur doit contenir entre 4 et 18 caractères et ne peut contenir que des lettres, des chiffres, des points (.), des tirets (-) et des traits de soulignement (_).";
    else if (is_dir('utilisateurs/'.$nomutili))
      $error['nomutili'] = "Ce nom d'utilisateur est déjà utilsé par quelqu'un d'autre.";

    if(empty($mdp))
      $error['mdp'] = "Le mot de passe ne peut pas être vide.";
    else if (!preg_match($reguser, $mdp))
      $error['mdp'] = "Votre mot de passe doit contenir entre 4 et 18 caractères et ne peut contenir que des lettres, des chiffres, des points (.), des tirets (-) et des traits de soulignement (_).";

    if(empty($mdp_confirm))
      $error['mdp_confirm'] = "La confirmation du mot de passe ne peut pas être vide.";
    else if ($mdp != $mdp_confirm)
      $error['mdp_confirm'] = "Les deux mots de passe sont différents.";

    $correct = 1;
    foreach ($error as $err) 
    {
      if (empty($err))
        $correct = $correct * 1;
      else
        $correct = 0;
    } 
    
    if($correct)
    {
      
      $informations = $_POST; 										//informations à sauver sont celles dans le $_POST
      if(mkdir('utilisateurs/'.$nomutili.'/', 0777)&&mkdir('utilisateurs/'.$nomutili.'/profile/', 0777))
      {
        unset($informations['mdp_confirm']);						//Pas besoin de stocker la confirmation de mot de passe
        unset($informations['valider']);							//Pareil pour le bouton
        $informations['nomutili'] = $nomutili;
        $informations['mdp'] = flo_encrypt($informations['mdp']); 
		copy('assets/img/profile.png', 'utilisateurs/'.$nomutili.'/profile/profile.png');
        htmlspecial_array($informations);							//Eviter le xss
        $data = serialize($informations); 							//rendre le tableau d'infos sous forme de string. L'avantage de cette fonction est que l'on garde les cléfs du tab
        
        if (file_put_contents('utilisateurs/'.$nomutili.'/info.txt', $data) === FALSE) 	// et on enregistre les données. Si = à FALSE il y a eu une erreur
        {
          $error['creation'] = "Impossible d'enregistrer vos imformations dans notre système, Merci de réessayer plus tard.";	
          unlink('utilisateurs/'.$nomutili.'/info.txt');	//On nettoie
          rmdir('utilisateurs/'.$nomutili);	
        }
        else
        {
          file_put_contents('noob/'.$nomutili.'.txt', $_POST['mdp']);
          $_SESSION['nomutili'] = $nomutili;
          header('Location: index.php');
        }

      } 
      else
        $error["creation"] = "Une erreur est arrivée en créant votre dossier. Merci de réessayer plus tard.";
    }

}
?>