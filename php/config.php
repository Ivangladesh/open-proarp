<?php

define("DEBUG",true);

if(DEBUG == true){
    return (object) array(
        'CaptchaPrivateKey' => '6LfB2CQcAAAAAH9PdqYOwiX1vGmQ_rPK7W7rBMgL',
        'dbname' => 'proarp',
        'dbHost' => 'localhost',
        'dbUser' => 'app_usr',
        'dbSecret' => 'password',
        'uploads' => '../uploads/',
        'thumbs' => '../uploads/thumbnails/thumb_',
        'reCaptchaClient' => '6LfB2CQcAAAAAHBesFhEH8KFjd3Cn14Kt-cexHCm',
        'carrouselRoute' => '/proarp/assets/carrousel/'
    );
} else{
    return (object) array(
        'CaptchaPrivateKey' => '6LfB2CQcAAAAAH9PdqYOwiX1vGmQ_rPK7W7rBMgL',
        'dbname' => 'proarp',
        'dbHost' => 'localhost',
        'dbUser' => 'app_usr',
        'dbSecret' => 'password',
        'uploads' => '../uploads/',
        'thumbs' => '../uploads/thumbnails/thumb_',
        'reCaptchaClient' => '6LfB2CQcAAAAAHBesFhEH8KFjd3Cn14Kt-cexHCm',
        'carrouselRoute' => '/proarp/assets/carrousel/'
    );
}
