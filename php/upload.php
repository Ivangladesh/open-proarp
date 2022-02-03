<?php
// $allowed_file_types = ['image/jpeg', 'image/png', 'image/jpg'];
// $allowed_size_mb = 2; 
$fileName = $_FILES['file']['name'];
$fileTmpName = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];
$fileError = $_FILES['file']['error'];
$fileType = $_FILES['file']['type'];
$fileExt = explode('.', $fileName);
$fileActualExt = strtolower(end($fileExt));
$allowed = array('jpg','jpeg','png');
$fileDestination = '../uploads/';
$fileDestinationThumb = '/thumbnails';

if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0) {
        if ($fileSize > 2000) {
            $uniqueName = uniqid('', true);
            $fileNewName = $uniqueName.".". $fileActualExt;
            $destination = $fileDestination. $fileNewName;
            move_uploaded_file($fileTmpName, $destination);
            makeThumbnails($destination, $fileActualExt, $fileNewName);
        } else{
            echo "El tamaño del archivo es demasiado grande.";    
        }
    } else{
        echo "Ha ocurrido un error";
    }
} else{
    echo "Tipo de archivo no válido";
}

function makeThumbnails($updir, $img, $id)
{
    $thumbnail_width = 134;
    $thumbnail_height = 189;
    $thumb_beforeword = "thumb";
    $imgcreatefrom = "";
    $fileDestination = '../uploads/';
    $fileDestinationThumb = '/thumbnails';
    $arr_image_details = getimagesize($updir); // pass id to thumb name
    $original_width = $arr_image_details[0];
    $original_height = $arr_image_details[1];
    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);
    if ($arr_image_details[2] == IMAGETYPE_GIF) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    }
    if ($arr_image_details[2] == IMAGETYPE_JPEG) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    }
    if ($arr_image_details[2] == IMAGETYPE_PNG) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
    if ($imgt) {
        $old_image = $imgcreatefrom($updir);
        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
        $imgt($new_image, "$fileDestination"."$fileDestinationThumb/"."$thumb_beforeword".'_'.$id);
    }
}

?>