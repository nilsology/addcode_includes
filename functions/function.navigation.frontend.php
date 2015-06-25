<?php

function getSubItems($item, $d, $c) {
    $items[$count] = sizeof($item->getChildren()); // set items of nesting level
    if ($c[1]) {
      getSubArticles($item, $c);
    }

    if ($d >= $c[0]) return; // stop nesting when max_depth is reached
    $d++; // adjust nesting-counter

    // work with items if any
    if ($items[$count] >= 1) {
      echo "<ul>"; // start HTML-SubList
      // go through subitems and print them
      foreach($item->getChildren() as $item) {
        if (!$item->isOnline()) continue;
          echo '<li><a href="'. $item->getUrl() .'" title="'. $item->getName() .'">'. $item->getName() .'</a></li>';
          // same procedure as above for any children
          getSubItems($item, $d, $c);
      }
      echo "</ul>";
    }
}

function getSubArticles($item, $c) {
  $start_id = $item->getStartArticle()->getId();
  if (! sizeof($item->getArticles()) >= 1) return;
  echo "<ul>";
  foreach($item->getArticles() as $art) {
    if (!$art->isOnline()) continue;
      if ($c[2] && $start_id == $art->getId()) {
        echo '<li><a href="'. $art->getUrl() .'" title="'. $art->getName() .'">'. $art->getName() .'</a></li>';
      } elseif ($start_id != $art->getId()) {
        echo '<li><a href="'. $art->getUrl() .'" title="'. $art->getName() .'">'. $art->getName() .'</a></li>';
      }
  }
  echo "</ul>";
}

function navi() {
  echo "<ul>"; // start HTML-List
  $max_depth = 3; // max nesting-depth root is depth=0
  $inc_art = true; // include articles
  $inc_root_art = false; // include root articles
  $inc_start_art = false; // include start article
  $config = array($max_depth, $inc_art, $inc_start_art, $inc_root_art); // config array
  $items = []; // wrapper for subitem sizes
  $count = 0; // items per nesting level
  foreach (OOCategory::getRootCategories() as $item) {
    if (!$item->isOnline()) continue;
    $count++; // initalize each root-item
    // print root-item
    echo '<li><a href="'. $item->getUrl() .'" title="'. $item->getName() .'">'. $item->getName() .'</a></li>';
    $d = 0; // set nesting-depth for root-item
    getSubItems($item, $d, $config);      
    $d = 0; // REset nesting-depth for root-item
  }
  // get Root Articles
  if (sizeof(OOArticle::getRootArticles()) >= 1 && $config[3]) {
    foreach(OOArticle::getRootArticles() as $art) {
      if (!$art->isOnline()) continue;
      echo '<li><a href="'. $art->getUrl() .'" title="'. $art->getName() .'">'. $art->getName() .'</a></li>';
    }
  }
  echo "</ul>";
}
?>
