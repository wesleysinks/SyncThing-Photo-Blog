<?php
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

function social($article)
{
  $shareUrl = 'http://' . $_SERVER['SERVER_NAME'] . "/" . str_replace(' ', '-', $article);
  return '<p class="share">share this: <a href="http://www.facebook.com/sharer.php?u=' . $shareUrl . '" target="_blank">fb</a> | <a href="http://twitter.com/share?url=' . $shareUrl . '&text=' . $article . ' via @_wesleysinks" target="_blank">twt</a> | <a href="https://plusone.google.com/_/+1/confirm?hl=en&url=' . $shareUrl . '" target="_blank">g+</a></p>';
}
?>
