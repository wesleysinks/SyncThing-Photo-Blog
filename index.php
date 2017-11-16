<?php
$title="Syncthing Photo Blog";
$siteDescription="<p>Proof of concept for a simple Syncthing powered photo blog</p>";
$author="Wesley Sinks";
$current = basename($_SERVER['REQUEST_URI']);
$articleName = str_replace('-', ' ', $current);
// arrays for photos and description files
$photos = glob('posts/*.{jpg,png,gif}', GLOB_BRACE);
$descriptions = glob('posts/*.txt');
$d = 'posts/' . $articleName . '.txt';

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

function descriptionText($file)
{
  $htmlOutput = '';
  foreach (file($file) as $line) {
    if ($line != "\n") {
      $htmlOutput .= '<p>' . trim($line, "\n") . '</p>';
    }
  }
  return $htmlOutput;
}

function social($article){
  $shareUrl = 'http://' . $_SERVER['SERVER_NAME'] . "/" . str_replace(' ', '-', $article);
  return '<p class="share">share this: <a href="http://www.facebook.com/sharer.php?u=' . $shareUrl . '" target="_blank">fb</a> | <a href="http://twitter.com/share?url=' . $shareUrl . '&text=' . $article . ' via @_wesleysinks" target="_blank">twt</a> | <a href="https://plusone.google.com/_/+1/confirm?hl=en&url=' . $shareUrl . '" target="_blank">g+</a></p>';
}

if ($current) {
  $siteDescription = "";
}

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
  <style>
  @import url('https://fonts.googleapis.com/css?family=Lora|Roboto+Condensed:300,400');

  body {
    margin: 0;
    padding: 0;
    background-color: #000000;
    background-image: url("/img/graph-tile.png");
  }

  #mainHeader {
    position: relative;
    width: auto;
    max-width: 85%;
    margin: 0 auto;
  }

  footer {
    margin: 40px 0 20px;
    text-align: center;
  }

  article {
    position: relative;
    width: auto;
    height: auto;
    max-width: 85%;
    margin: 20px auto;
  }

  .imagetext {
    margin: 0;
    box-sizing: border-box;
    padding: 5px;
    display: block;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    background-color: rgba(0, 0, 0, 0.6);
  }

  .imagetext.single {
    top: 0;
    bottom: auto;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
  }

  .share {
    position: absolute;
    top: 0;
    right: 0;
    padding: 7px;
  }

  .share, .share a {
    color: rgba(255, 255, 255, 0.7);
  }

  h1, h2, h3, h4, h5, p {
    margin: 0.3rem 0;
    padding: 0;
    opacity: 0.5;
  }

  h1, h2, h3, h4, h5 {
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.7);
  }

  p {
    font-family: 'Lora', serif;
    color: rgba(255, 255, 255, 0.6);
  }

  #siteTitle {
    border-bottom: 1px solid;
    font-size: 2.5em;
  }

  a {
    text-decoration: none;
    color: #fcc;
  }

  img {
    display: block;
    width: 100%;
    max-width: 100%;
    max-height: 90%;
    margin: 0 0;
    border-radius: 10px;
    box-shadow: 0 0 40px -15px;
    color: white;
  }

  img#single {
    max-width: 100%;
    max-height: 100%;
  }

  footer img {
    display: inline;
    height: 1rem;
    width: 1rem;
  }

  img.logo {
    display: inline;
    height: 10rem;
    width: 10rem;
    filter: invert(100%);
    position: absolute;
    right: 5px;
    top: 0;
    opacity: 0.1;
  }

  </style>
  <body>
    <img src="/img/Logo.svg" class="logo" alt="logo"/>
    <header id="mainHeader">
      <a href="/"><h1 id="siteTitle"> <?php echo $title ?></h1></a>
      <?php echo $siteDescription ?>
    </header>
    <?php

      if(array_search($articleName, nameArray($photos)) !== false && array_search($articleName, nameArray($descriptions)) !== false){
        $articleName = str_replace('-', ' ', $current);
        // Display single here.

        if(glob('posts/' . $articleName . '.jpg')){
          $p = $articleName . '.jpg';
        } elseif (glob('posts/' . $articleName . '.png')) {
          $p = $articleName . '.png';
        } elseif (glob('posts/' . $articleName . '.gif')) {
          $p = $articleName . '.gif';
        }
        ?>
        <article id="<?php echo $articleName ?>">
          <div class="imagetext single">
            <h2><?php echo $articleName . " | " . date("m.d.y", filemtime('posts/' . $p)) ?></h2>
            <?php echo descriptionText($d) . social($articleName); ?>
          </div>
          <img id="single" src="<?php echo '/posts/' . $p ?>" alt="<?php echo $articleName ?>" />
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
                <div class="imagetext">
                  <a href="<?php echo "/" . str_replace(' ', '-', $articleName) ?>"><h2><?php echo $articleName . " | " . date("m.d.y", filemtime($p)) ?></h2></a>
                  <?php echo descriptionText($d) . social($articleName); ?>
                </div>
                <a href="<?php echo "/" . str_replace(' ', '-', $articleName) ?>"><img src="<?php echo $p ?>" alt="<?php echo $articleName ?>" /></a>
              </article>
              <?php
            }
          }
        }
      }
    ?>
    <footer>
      <p><a href="https://creativecommons.org/licenses/by/2.0/"><?php echo 'CC BY 2.0 - ' . date('Y') ?> <img src="/img/cc_icon_white_x2.png" /> <img src="/img/attribution_icon_white_x2.png" /></a> <a href="http://wsle.me"> - <?php echo $author ?></a></p>
    </footer>
  </body>
</html>
