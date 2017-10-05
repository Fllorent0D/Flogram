<?php
if(isset($_POST["envoyer"]))
{
	$titre = $_POST['titre'];
	$petite_descript = $_POST['petite_descript'];
	$longue_descript = $_POST['longue_descript'];
	$photo = $_FILES['photo'];

	$error['photo'] = "";
    $error['titre'] = "";

	$extensions = pathinfo($photo["name"],PATHINFO_EXTENSION);
	$extensionsauto = array('jpg', 'png', 'jpeg', 'gif'); //Ajouter extensions ici
 	if(empty($titre))
		$error["titre"] = "Le titre ne peut pas être vide.";
	else if(strlen($titre) > 45)
		$error["titre"] = "Le titre ne peut pas être aussi long.";
	
	if(empty($petite_descript))
	  $error["petite_descript"] = "La description courte ne peut pas être vide.";
	else if(strlen($petite_descript) > 400)
	  $error["petite_descript"] = "La description courte ne peut pas être aussi longue. Au maximum 400 caractères.";
	else if(strlen($petite_descript) > strlen($longue_descript))
	  $error["petite_descript"] = "La description courte doit être plus petite que la longue description";  
	  	
	if (empty($longue_descript))
	 $error["longue_descript"] = "La description longue ne peut pas être vide.";
	else if(strlen($longue_descript) > 800)
	 $error["petite_descript"] = "La description longue ne peut pas être aussi longue. Au maximum 800 caractères.";
	

	if(empty($photo["name"]))
		$error["photo"] = "Vous devez choisir une photo.";
	else if(!in_array(strtolower($extensions), $extensionsauto)) 
		$error['photo'] = "Seulement les formats JPG, JPEG, PNG et GIF sont acceptés.";
 	else if ($photo["size"] > 8000000) 
	    $error['photo'] = "La photo est trop grosse.";
	

	$bon = 1;
	foreach ($error as $err)
	{
		if(empty($err))
			$bon *= 1;
		else
			$bon = 0;
	}

	if ($bon) 
	{
		$nomphoto = round(microtime(true) * 100); 		//micros secondes
		$nomphotoext = $nomphoto. ".". $extensions;
		$destination = "utilisateurs/".$_SESSION['nomutili']."/"; //dans le dossier de l'utilisateur
		$destination = $destination . $nomphotoext;
	    if (move_uploaded_file($photo["tmp_name"], $destination)) 
	    {
	    	$to_save = $_POST;
	    	unset ($to_save["envoyer"]);
	    	$to_save["auteur"] = $_SESSION['nomutili'];
	    	htmlspecial_array($to_save);				//XSS
	    	$to_save = serialize($to_save);				//STOCKAGE
	        
	        if (file_put_contents('utilisateurs/'.$_SESSION["nomutili"].'/'.$nomphoto.'.txt', $to_save) === FALSE)
	        {
	        	$error['creation'] = "Une erreur est survenue en enregistrant les détails de la photo. Merci de réessayer plus tard.";
	          	unlink('utilisateurs/'.$_SESSION["nomutili"].'/'.$nomphotoext);
	        }
	        else
	        {
	        	$likes = array();
				$likes = serialize($likes);	        	
	        	file_put_contents('utilisateurs/'.$_SESSION["nomutili"].'/'.$nomphoto.'_likes.txt', $likes);

	        	header("Location: detail.php?f=".$nomphoto);
	        }	    	

	    }
	    else 
	    {
	        $error["photo"] = "Une erreur s'est produite en sauvegardant votre image dans votre galerie. Réessayer plus tard.";
	    }
	}		
}
?>