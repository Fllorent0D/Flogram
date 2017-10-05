<?php
/* Retourne un tableau avec les infos de la photo
	INPUT : lien vers une photo
	OUTPUT : Tableau d'informations de la photo*/
function getInfoPhoto($photo)
{
	$txt = substr_replace($photo , 'txt', strrpos($photo , '.') +1);
	$infoPhoto = file_get_contents($txt);
    $infoPhoto = unserialize($infoPhoto);
	
	return $infoPhoto;
}
/*Retourne un tableau avec les info d'un utilisateur
	INPUT : nom d'utilisateur
	OUTPUT : Tableau d'informations de l'utilisateur*/
function getInfoUser($nomutili)
{
	$data = file_get_contents('utilisateurs/'.$nomutili.'/info.txt');
    $data = unserialize($data);
    return $data;
}	
function getExifDetails($photo)
{
	$ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
	if(($ext == "jpg" || $ext == "jpeg")&&exif_read_data ( $photo ,'EXIF' ,0 ) !== FALSE) 
		$exif = exif_read_data ( $photo );
	else
		$exif = 0;
		
	return $exif;
	
}

/*Retourne un tableau avec toutes les photos d'un utilisateur
	INPUT : nom d'utilisateur
	OUTPUT : Tableau avec les liens des photos
	Si '*' en argument la fonction recherche toutes les photos
	*/
function getPhotosFrom($nomutili)
{
	$photos = glob("utilisateurs/".$nomutili."/*.{jpg,png,gif,jpeg,tiff,TIFF,JPG,PNG,GIF,JPEG}", GLOB_BRACE);
	rsort($photos);

	if (count($photos) == 0)
		$photos = 0;

	return $photos;
}
function getUsers()
{
	$users = glob("utilisateurs/*");
	return $users;
}

/*Retourne le nom qu'il faut mettre en $_GET à detail.php	
	INPUT : lien d'une photo
	OUTPUT : basename de la photo sans l'extension*/
function getLinkForDetail($photo)
{
	//$ext = array(".jpg",".png",".gif",".jpeg",".JPG",".PNG",".GIF",".JPEG");
	//$photo = basename($photo).PHP_EOL;
    //$detail = str_replace($ext, "", $photo);
    $detail = substr_replace(basename($photo) , '', strrpos(basename($photo) , '.'));

    return $detail;
}
/*Retourne retourne le lien vers le txt d'une image	
	INPUT : lien d'une photo
	OUTPUT : lien fichier txt*/
function getLinkForTXT($photo)
{
	$txt = substr_replace($photo , 'txt', strrpos($photo , '.') +1);
    return $txt;
}
/*Retourne l'orientation d'une photo	
	INPUT : lien d'une photo
	OUTPUT : orientation*/
function getOrientation($photo)
{
	list($width, $height) = getimagesize($photo);
	if ($width > $height)
    	$orientation = "paysage";
	else
	  	$orientation = "portrait";

	return $orientation;
	

}
/*Retourne si l'utilisateur existe
	INPUT : nom d'utilisateur
	OUTPUT : vrai / faux*/
function isUser($nomutili)
{
	if(is_dir('utilisateurs/'.$nomutili))
		$existe = true;
	else
		$existe = false;
	return $existe;
}
function flo_encrypt($text)
{
	$key = "LACLEF";  // Clé de 8 caractères max
    $text = serialize($text);
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td,$key,$iv);
    $text = base64_encode(mcrypt_generic($td, '!'.$text));
    mcrypt_generic_deinit($td);
    return $text;}

function flo_decrypt($text)
{
    $key = "LACLEF";
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td,$key,$iv);
    $text = mdecrypt_generic($td, base64_decode($text));
    mcrypt_generic_deinit($td);
 
    if (substr($text,0,1) != '!')
        return false;
 
    $text = substr($text,1,strlen($text)-1);
    return unserialize($text);}

/*Retourne si l'utilisateur est connecté
	INPUT :  /
	OUTPUT : vrai / faux*/
function isConnect()
{
	
	if(isset($_SESSION['nomutili'])) // si seesion existe
	{
		$connect = true;
	}
	else
	{
		if(isset($_COOKIE['nomutili']) &&!empty($_COOKIE['nomutili'])) //si le cookie
		{
			if(isUser(flo_decrypt($_COOKIE['nomutili']))) //On regarde si ce qu'il y a dans le cookie est possible
			{
				$_SESSION['nomutili'] = flo_decrypt($_COOKIE['nomutili']);
				$connect = true;
			}
			else //Le cookie ne correspond pas à un utilisateur mieux vaut détruire la session pour sécurité
			{
				setcookie("nomutili", "", time()-3600, "/");
				$connect = false;
			}
		}
		else
		$connect = false;
	}
	

	return $connect;
}
/*Retrouve une photo
	INPUT : nom (1434xxxxx) sans l'extension
	OUTPUT : lien de la photo*/
function getPhoto($nom)
{
	$recherche = $nom;
    $photos = getPhotosFrom("*");                                            // * = tous les utilisateurs
    for($i = 0, $trouve = false; $i < count($photos) && $trouve == false; $i++)                                               //On les passe une par une
    {
      $test = basename(preg_replace('/\.[^.]+$/','',$photos[$i]));          
      if ($test == $recherche)               
      {          
      	$lienImage = $photos[$i];
      	$trouve = true;
      }
      else
      	$lienImage = 0;
    }
    return $lienImage;
}
/*Supprime les photos privées
	INPUT : tableau avec les liens des photos
	OUTPUT : même tableau sans les privées*/
	
function removePrivate($photos)
{
	foreach ($photos as $key => $photo)
	{
		$info = getInfoPhoto($photo);
		if($info["statut"] == 'prive')
			unset($photos[$key]);
	}

	rsort($photos);

	return $photos;
}
/* Garde les photos d'une certaine extension
	INPUT : tableau avec les liens des photos et l'ext a garder
	OUTPUT : le tableau avec l'ension gardée*/
function keepExt($photos, $ext)
{
	foreach ($photos as $key => $photo) 
	{
		$currentExt = pathinfo($photo, PATHINFO_EXTENSION);
		if (strtolower($ext) != strtolower($currentExt))
			unset($photos[$key]);
	}
	rsort($photos);
	return $photos;
}
/* Trouve toutes les definiton possible dans les photos publique
	INPUT : /
	OUTPUT : le tableau avec les définitons*/
function getDefinitions()
{
	$dim = array();
	$photos = removePrivate(getPhotosFrom('*'));
	foreach ($photos as $photo) 
	{
		list($largeur, $hauteur) = getimagesize($photo);
		$size = $largeur.' x '.$hauteur;

		if(!in_array($size, $dim))
			array_push($dim, $size);
	}
	sort($dim);
	return $dim;
}
/* Garde les photos d'une certaine définition
	INPUT : tableau avec les liens des photos et la taille a garder
	OUTPUT : le tableau avec la taille gardée*/
function keepSize($photos, $goodSize)
{
	foreach ($photos as $key => $photo) 
	{
		list($largeur, $hauteur) = getimagesize($photo);
		$size = $largeur.' x '.$hauteur;
		if($goodSize != $size)
			unset($photos[$key]);
	}
	rsort($photos);
	return $photos;
}
/* Trie les photos par ordre chronologique sans perdre les chemins d'accès
	INPUT : tableau avec les liens des photos
	OUTPUT : le tableau trié */
function sortArray($photos)
{
	$arr = array();
	foreach ($photos as $photo) 
	{
  		$arr[basename($photo, '.'.pathinfo($photo, PATHINFO_EXTENSION))] = $photo;
	}
	krsort($arr);

	$renvoie = array_values($arr);


	return $renvoie;
}
function profilePic($user)
{
	$photo = glob('utilisateurs/'.$user.'/profile/*');
	
	return $photo[0];
}
/* Fonction recursive pour éviter le xss dans un tableau
	INPUT : tableau avec infos à stocker
	OUTPUT : le tableau sécurisé*/
function htmlspecial_array(&$array) {
    
    foreach ($array as &$value) 
    {
        if (!is_array($value)) 
        	$value = htmlspecialchars($value); 
        else 
        	htmlspecial_array($value); 
    }
}
/* Garde les photos avec un certain titre
	INPUT : tableau avec les liens des photos et l'expression régulière
	OUTPUT : le tableau avec les photos gardées*/
function searchTitle($regex, $photos)
{
	$renvoi = array();
	foreach ($photos as $key => $photo) 
	{
		$infoPhoto = getInfoPhoto($photo);
		if(preg_match($regex, $infoPhoto['titre']))
			array_push($renvoi, $photo);
	}

	return $renvoi;
}
function addRemoveLike($nomutili, $photo)
{
    $lientxt = substr_replace($photo , '_likes.txt', strrpos($photo , '.'));
    $likes = file_get_contents($lientxt);
    $likes = unserialize($likes);
    if(in_array($nomutili, $likes))
    {
	    $pos = array_search($nomutili, $likes);
	    unset($likes[$pos]);
    }
    else
    	array_push($likes, $nomutili);
    	
    $likes = serialize($likes);
    file_put_contents($lientxt, $likes); 
    
}
function getLikes($photo)
{
    $lientxt = substr_replace($photo , '_likes.txt', strrpos($photo , '.'));
    $likes = file_get_contents($lientxt);
    $likes = unserialize($likes);
    
    return $likes;
}
?>
