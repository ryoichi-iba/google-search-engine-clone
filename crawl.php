<?php
include("classes/DomDocumentParser.php");
include("config.php");

$alreadyCrawled = array();
$Crawling       = array();

function insertLink($url,$title,$description,$keywords) {
  global $con;

  $query = $con->prepare("INSERT INTO sites(url, title, description, keywords)
                  VALUES(:url, :title, :description, :keywords)");

  $query->bindParam(":url" , $url);
  $query->bindParam(":title" , $title);
  $query->bindParam(":description" , $description);
  $query->bindParam(":keywords" , $keywords);

  return $query->execute();
}

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

function getDetails($url) {
  $parsar = new DomDocumentParsar($url);

  $titleArray = $parsar->getTitleTag();

  if(sizeof($titleArray) == 0 || $titleArray->item(0) == NULL) {
    return;
  }

  $title = $titleArray->item(0)->nodeValue;
  $title = str_replace("\n", "", $title);

  if($title == "") {
    return;
  }


  $description = "";
  $keywords = "";

  $metaArray = $parsar->getMetaTags();

  foreach($metaArray as $meta) {
    if($meta->getAttribute("name") == "description") {
      $description = $meta->getAttribute("content");
    }
    if($meta->getAttribute("name") == "keywords") {
      var_dump($keywords);
      $keywords = $meta->getAttribute("content");
    }

    
  }

    $description = str_replace("\n", "", $description);
    $keywords = str_replace("\n", "", $keywords);

    

    insertLink($url,$title,$description,$keywords);
  echo "URL: $url, Desc: $description , key: $keywords <br>";
}


function followLinks($url) {

  global $alreadyCrawled;
  global $crawling;

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

    if(!in_array($href, $alreadyCrawled)) {
      $alreadyCrawled[] = $href;
      $crawling[] = $href;

      getDetails($href);
    }else {
      return;
    }

  }

  array_shift($crawling);

  foreach($crawling as $site) {
    followLinks($site);
  }
}



$startUrl = "https://www.bbc.com";
followLinks($startUrl);

?>