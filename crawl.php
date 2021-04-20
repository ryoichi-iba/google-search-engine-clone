<?php
include "classes/DomDocumentParser.php";

function createLink($src, $url){
  
  $scheme = parse_url($url)["scheme"];
  $host = parse_url($url)["host"];

  if(substr($src,0,2) == "//") {
    $src = $scheme . ":" . $host;
  }elseif(substr($src,0,1) == "/") {
    $src = $scheme . "://" . $host . $src;
  }elseif(substr($src, 0, 2) == "./") {
    $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
  }elseif (substr($src, 0, 3) == "../") {
    $src = $scheme . "://" . $host . "/" . $src;
  } elseif (substr($src, 0, 5) != "https" && substr($src, 0, 4) == "http") {
    $src = $scheme . "://" . $host . "/" . $src;
  }

  return $src;
}


function followLinks($url) {
  $parsar = new DomDocumentParsar($url);

  $linkList = $parsar->getLinks();

  foreach($linkList as $link) {
    $href = $link->getAttribute("href");

    if(strpos($href, "#") !== false ) {
      continue;
    } elseif(substr($href, 0, 11 )  =="javascript:") {
      continue;
    }
    
    $href = createLink($href,$url);

    echo $href . "<br>";
  }
}



$startUrl = "https://google.com";
followLinks($startUrl);

?>