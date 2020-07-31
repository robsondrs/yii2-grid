<?php

namespace dynamikaweb\grid;

use yii\web\AssetBundle;

class ActionColumnAsset extends AssetBundle
{
    public $sourcePath = '@vendor/dynamikaweb/yii2-grid/src/assets';

    public $js = [
        'js/grid.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'dominus77\sweetalert2\assets\SweetAlert2Asset',
    ];
}
