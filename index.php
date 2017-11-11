<?php
$title="Syncthing Photo Blog";
$description="Proof of concept for a simple Syncthing powered photo blog";
$author="Wesley Sinks"
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $description ?>">
    <meta name="author" content="<?php echo $author ?>">
  </head>
  <body>
    <header id="mainHeader">
      <h1 id="siteTitle"><?php echo $title ?></h1>
      <p id="siteDesc"><?php echo $description ?></p>
    </header>
    <php
      ### for each photo file in posts
        ### if matching text file
          ### print image and text to page
    ?>
  </body>
</html>
