<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $siteDescription ?>">
    <meta name="author" content="<?php echo $author ?>">
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
  </head>
  <body>
    <img src="/img/Logo.svg" class="logo" alt="logo"/>
    <header id="mainHeader">
      <a href="/"><h1 id="siteTitle"> <?php echo $title ?></h1></a>
      <?php if (!$current) {
        echo $siteDescription;
      } ?>
    </header>
