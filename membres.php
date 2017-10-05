<?php
  session_start(); 
  include 'include/functions.php';
  $utilisateurs = getUsers();
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
    <section class="container animated fadeIn well">
    	<header>
    		<h1>Membres</h1>
    	</header>
        <section class="row text-center">
			<?php 
				$i=1;
				$colonnes = 6;
				for ($i = 0; $i < $colonnes; $i++)
				{
					echo '<div class="col-md-'.(12/$colonnes).'">';
					for ($j = $i; $j < count($utilisateurs); $j+=$colonnes)
					{
						$user = basename($utilisateurs[$j]);
						$profPic = profilePic($user);
						$info = getInfoUser($user);

						echo '
						<a href="galerie.php?u='.$user.'">
						<div class="row well nomargin">
							<h4>'.$info['prenom'].' '.$info['nom'].'</h4>
							<img src="thumbnail.php?f='.$profPic.'&amp;max=300" class="img-responsive img-rounded" alt="Image de profile de '.$user.'">
							<div class="clearfix"></div>
						</div>
						</a>';

					}
					echo '</div>';	
				}
			?>

        </section>
    </section>
</body>

</html>
