<?php

namespace dynamikaweb\grid;

use kartik\icons\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


use Yii;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $headerOptions = ['class' => 'action-column', 'width' => '110px'];

    public $redirect = null;

    public $baseUrl = "";

    protected function initDefaultButtons()
    {
        $this->initDefaultButton('go', 'external-link-alt', ['target' => '_blank']);
        $this->initDefaultButton('view', 'eye');
        $this->initDefaultButton('update', 'edit');
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }
    
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'go': 
                        $title = Yii::t('yii', 'Redirect');
                        
                        if ($this->redirect) {
                            $url = Url::to(["{$this->redirect}", 'id' => $model->id, 'slug' => $model->getAttribute('slug')], true);
                        } else {
                            $this->controller = Yii::$app->controller->id;
                            $url = Url::to(["{$this->controller}/view", 'id' => $model->id, 'slug' => $model->getAttribute('slug')], true);
                        }

                        $url = str_replace(Yii::$app->request->baseUrl, $this->baseUrl, $url);
                        break;
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon = Icon::show($iconName, ['framework' => Icon::FAS]);
                return Html::a($icon, $url, $options);
            };
        }
    }

}
