<?php
$title="Syncthing Photo Blog";
$siteDescription="Proof of concept for a simple Syncthing powered photo blog";
$author="Wesley Sinks"
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $siteDescription ?>">
    <meta name="author" content="<?php echo $author ?>">
  </head>
  <body>
    <header id="mainHeader">
      <h1 id="siteTitle"><?php echo $title ?></h1>
      <p id="siteDesc"><?php echo $siteDescription ?></p>
    </header>
    <?php
      // arrays for photos and description files
      $photos = glob('posts/*.{jpg,png,gif}', GLOB_BRACE);
      $descriptions = glob('posts/*.txt');

      // sort photos newest first
      usort($photos, function($a, $b) {
        return filemtime($a) < filemtime($b);
      });

      //strip file extensions
      function extensionStrip($f)
      {
        $f_info = pathinfo($f);
        $f_name =  basename($f,'.'.$f_info['extension']);
        return $f_name;
      }

      // for each photo file in posts
      foreach($photos as $p) {
        // if matching text file
        foreach($descriptions as $d) {
          if(extensionStrip($p) == extensionStrip($d) && pathinfo($d)['extension'] == 'txt') {
            // print image and text to page
            ?>
            <article>
              <img src="<?php echo $p ?>" alt="<?php extensionStrip($p) ?>" height="400px">
              <h2><?php echo date("m.d.y", filemtime($p)) . " | " . extensionStrip($p) ?></h2>
              <p><?php echo file_get_contents($d) ?></p>
            </article>
            <?php
          }
        }
      }
    ?>
  </body>
</html>
