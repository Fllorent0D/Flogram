<?php
if (isset($_POST['valider']))
{
    $error['nomutili'] = "";
    $error['mdp'] = "";
	$nomutili = strtolower($_POST['nomutili']);
    $mdp = $_POST['mdp'];


    if(empty($nomutili))
      $error['nomutili'] = "Le champ est vide.";
    else if(!is_dir('utilisateurs/'.$nomutili))
      $error['nomutili'] = "Le compte n'existe pas.";
    
    if(empty($mdp))
      $error['mdp'] = "Le champ est vide.";


    $correct = 1;
    foreach ($error as $err)
    {
      if(empty($err))
        $correct = $correct * 1;
      else
        $correct = 0;
    }

    if ($correct)
    { 
      htmlspecial_array($_POST); //XSS     
      $infoUser = getInfoUser($nomutili); //retourne un tableau avec les infos de l'utilisateur écrit dans le form
      if(flo_encrypt($mdp) == $infoUser['mdp'])
      {
        if(isset($_POST['souvenir'])) //Se souvenir de moi est coché
        {
          $cookie_name = 'nomutili';
          $cookie_value = $nomutili;
          $cookie_value = flo_encrypt($nomutili);
          setcookie($cookie_name,$cookie_value, time() + (86400 * 30), "/"); // 86400 * 30 = 1 jour * 30
        }
        else
          $_SESSION['nomutili'] = strtolower($nomutili);
        

        header("Location: index.php");
      }
      else
        $error['connexion'] = "Le nom d'utilisateur et le mot de passe ne correspondent pas.";
    }


}
?>