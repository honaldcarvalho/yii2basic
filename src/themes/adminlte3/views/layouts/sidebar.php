<?php
if(Yii::getAlias('@leftbar', false)) {
    echo $this->render('leftbar');
} else {
    echo $this->render('@vendor/croacworks/yii2basic/src/views/menu/sidebar');
}