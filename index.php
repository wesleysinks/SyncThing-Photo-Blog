<?php
require_once 'inc/functions.php';

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

include 'inc/header.php';

// display single article based on URI
if(array_search($articleName, nameArray($photos)) !== false && array_search($articleName, nameArray($descriptions)) !== false){
  $articleName = str_replace('-', ' ', $current);
  if(glob('posts/' . $articleName . '.jpg')){
    $p = $articleName . '.jpg';
  } elseif (glob('posts/' . $articleName . '.png')) {
    $p = $articleName . '.png';
  } elseif (glob('posts/' . $articleName . '.gif')) {
    $p = $articleName . '.gif';
  }
  ?>
  <article id="<?php echo $articleName ?>">
    <img id="single" src="<?php echo '/posts/' . $p ?>" alt="<?php echo $articleName ?>" />
    <div class="imagetext single">
      <h2><?php echo $articleName . " | " . date("m.d.y", filemtime('posts/' . $p)) ?></h2>
      <?php echo descriptionText($d) . social($articleName); ?>
    </div>
  </article>
<?php
} else {

// display all articles
  $i = 0;                       //iterator for matching text to minimize buttons
  foreach($photos as $p) {                       // for each photo file in posts
    foreach($descriptions as $d) {
      if(extensionStrip($p) == extensionStrip($d)) {    // if matching text file
        $articleName = extensionStrip($p);       // print image and text to page
        ?>
        <article id="<?php echo $articleName ?>">
          <button class="minmax" onclick="minmax(<?php echo $i ?>)">info + / -</button>
          <div class="imagetext" id="<?php echo $i ?>">
            <a href="<?php echo "/" . str_replace(' ', '-', $articleName) ?>"><h2><?php echo $articleName . " | " . date("m.d.y", filemtime($p)) ?></h2></a>
            <?php echo descriptionText($d) . social($articleName); ?>
          </div>
          <a href="<?php echo "/" . str_replace(' ', '-', $articleName) ?>"><img src="<?php echo $p ?>" alt="<?php echo $articleName ?>" /></a>
        </article>
        <?php
        $i++;       //increase i only if a matching txt file is found for photo.
      }
    }
  }
}
include 'inc/footer.php';
?>
