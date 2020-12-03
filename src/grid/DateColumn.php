<?php

namespace dynamikaweb\grid;

use Yii;

/**
 * DateColumn
 * 
 * @property string|array $format Cell Date Format
 * 
 * @property string|array $format_raw Uses when attribute not is a date
 * 
 * @property string $format_filter Filter input mask
 * 
 * @property string $format_filter Filter options
 * 
 * @property string $format_filter Cell options
 *
 * @property string $placeholder Prompt filter option
 *
 */
class DateColumn extends \yii\grid\DataColumn
{
    public $format = 'short';

    public $format_raw = null;

    public $format_filter = 'dd/MM/yyyy';

    public $options = [];
    
    public $filterOptions = [];

    public $placeholder = 'Selecione {attribute}';

    public function init ()
    {
        $this->format = empty($this->format_raw)? ['date', $this->format]: $this->format_raw;
        $this->placeholder = strtr(Yii::t('app', $this->placeholder), ['{attribute}' => $this->grid->filterModel->getAttributeLabel($this->attribute)]);
        $this->filter = \yii\jui\DatePicker::widget([
            'model'=> $this->grid->filterModel,
            'attribute' => $this->attribute,
            'language' => Yii::$app->language,
            'dateFormat' => $this->format_filter,
            'options' => array_merge(['class' => 'form-control', 'placeholder' => $this->placeholder], $this->filterOptions)
        ]);
        $this->options = array_merge(['width' => '150px'], $this->options);

        parent::init();
    }
}
