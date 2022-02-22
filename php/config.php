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
        'carrouselRoute' => '/proarp/assets/carrousel/',
        'locationPath' => '/views/index.php'
    );
} else{
    return (object) array(
        'CaptchaPrivateKey' => '6Ldti4YeAAAAAKernyCgLp6DQo8-YSgl6v2-Jr-L',
        'dbname' => 'id17674689_proarp',
        'dbHost' => 'localhost',
        'dbUser' => 'id17674689_app_usr',
        'dbSecret' => 'ukI]t]7o[WZa8i<P',
        'uploads' => '../uploads/',
        'thumbs' => '../uploads/thumbnails/thumb_',
        'reCaptchaClient' => '6Ldti4YeAAAAAJXpkIbB2XxRTC2m4hdNLPB_XepL',
        'carrouselRoute' => '/proarp/assets/carrousel/',
        'locationPath' => '/views/index.php'
    );
}
