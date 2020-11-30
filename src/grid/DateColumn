<?php

namespace dynamikaweb\grid;

use Yii;
use yii\helpers\Html;

class DateColumn extends \yii\grid\DataColumn
{
    public $format = 'short';
    public $options = [];
    public $filterOptions = [];

    public function init ()
    {
        $this->format = ['date', $this->format];
        $this->filter = \yii\jui\DatePicker::widget([
            'model'=> $this->grid->filterModel,
            'attribute' => $this->attribute,
            'language' => Yii::$app->language,
            'dateFormat' => 'dd/MM/yyyy',
            'options' => array_merge(['class' => 'form-control'], $this->filterOptions)
        ]);
        $this->options = array_merge(['width' => '150px'], $this->options);

        parent::init();
    }
}
