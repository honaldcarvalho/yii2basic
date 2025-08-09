<?php

use croacworks\yii2basic\widgets\MultiUpload;

echo MultiUpload::widget([
    'extensions'=>['jpeg','jpg','png'],
    'auto'=>true,
    'callback'=>'']); 
?>