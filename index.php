<?php
$title="Syncthing Photo Blog";
$siteDescription="Proof of concept for a simple Syncthing powered photo blog";
$author="Wesley Sinks";
$current = basename($_SERVER[REQUEST_URI]);

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
      <h1 id="siteTitle"><a href="/"><?php echo $title ?></a></h1>
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

      function nameArray($a)
      {
        $returnArray = [];
        foreach ($a as $i) {
          array_push($returnArray, extensionStrip($i));
        }
        return $returnArray;
      }

      $articleName = str_replace('-', ' ', $current);
      if(array_search($articleName, nameArray($photos)) !== flase && array_search($articleName, nameArray($descriptions)) !== false){
        $articleName = str_replace('-', ' ', $current);
        // Display single here.
        $d = 'posts/' . $articleName . '.txt';
        if(glob('posts/' . $articleName . '.jpg')){
          $p = $articleName . '.jpg';
        } elseif (glob('posts/' . $articleName . '.png')) {
          $p = $articleName . '.png';
        } elseif (glob('posts/' . $articleName . '.gif')) {
          $p = $articleName . '.gif';
        }
        ?>
        <article id="<?php echo $articleName ?>">
          <img src="<?php echo '/posts/' . $p ?>" alt="<?php echo $articleName ?>" height="100%">
          <h2><?php echo $articleName . " | " . date("m.d.y", filemtime($p)) ?></h2>
          <?php foreach (file($d) as $line):
            if ($line != "\n") { ?>
            <p><?php echo trim($line, "\n") ?></p>
          <?php } endforeach; ?>
        </article>
      <?php
      } else {
        // for each photo file in posts
        foreach($photos as $p) {
          // if matching text file
          foreach($descriptions as $d) {
            if(extensionStrip($p) == extensionStrip($d)) {
              // print image and text to page
              $articleName = extensionStrip($p);
              ?>
              <article id="<?php echo $articleName ?>">
                <a href="<?php echo "/" . str_replace(' ', '-', $articleName) ?>"><img src="<?php echo $p ?>" alt="<?php echo $articleName ?>" height="400px"></a>
                <h2><a href="<?php echo "/" . str_replace(' ', '-', $articleName) ?>"><?php echo $articleName . " | " . date("m.d.y", filemtime($p)) ?></a></h2>
                <?php foreach (file($d) as $line):
                  if ($line != "\n") { ?>
                  <p><?php echo trim($line, "\n") ?></p>
                <?php } endforeach; ?>
              </article>
              <?php
            }
          }
        }
      }
    ?>
  </body>
</html>
