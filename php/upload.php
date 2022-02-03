<?php
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
$response = new stdClass();
$msg = "";
$ok = true;
if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0) {
        if ($fileSize > 2000) {
            $uniqueName = uniqid('', true);
            $fileNewName = $uniqueName.".". $fileActualExt;
            $destination = $fileDestination. $fileNewName;
            move_uploaded_file($fileTmpName, $destination);
            makeThumbnails($destination, $fileActualExt, $fileNewName);
        } else{
            $msg .= "El tamaño del archivo es demasiado grande.";
            $ok = false;  
        }
    } else{
        $msg .= "Ha ocurrido un error";
        $ok = false;
    }
} else{
    $msg .= "Tipo de archivo no válido";
    $ok = false;

    $response-> callback = 'UploadImage';
    $response-> data = null;
    $response-> ok = $ok;
    echo json_encode($response);

}

function makeThumbnails($updir, $img, $id)
{
    $response = new stdClass();
    $thumbnail_width = 134;
    $thumbnail_height = 134;
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
        $response-> callback = 'UploadImage';
        $response-> data = null;
        $response-> ok = true;
        echo json_encode($response);
    } else{
        $response-> callback = 'UploadImage';
        $response-> data = null;
        $response-> ok = false;
        echo json_encode($response);
    }
}

?>