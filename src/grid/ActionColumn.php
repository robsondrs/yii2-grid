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

    public $controller = null;

    public $action = null;

    public $pjax = null;

    public $messages = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        ActionColumnAsset::register(Yii::$app->view);

        if(!$this->controller && !$this->action){
            $this->action = "{action}";
        }
        else if ($this->controller && !$this->action){
            $this->action = "{controller}/{action}";
        }
        else if (!$this->controller && $this->action) {
            $this->controller = Yii::$app->controller->id;
        }
    }

    protected function initDefaultButtons()
    {
        $this->initDefaultButton('go', 'external-link-alt', ['target' => '_blank']);
        $this->initDefaultButton('view', 'eye');
        $this->initDefaultButton('update', 'edit');
        $this->initDefaultButton('delete', 'trash', [
            'data-success' => Yii::t('yii', ArrayHelper::getValue($this->messages, 'success', 'Success!')),
            'data-delete' => Yii::t('yii', ArrayHelper::getValue($this->messages, 'delete', 'Are you sure you want to delete this item?')),
            'data-yes' => Yii::t('yii', ArrayHelper::getValue($this->messages, 'yes', 'Yes')),
            'data-no' => Yii::t('yii', ArrayHelper::getValue($this->messages, 'no', 'No')),
            'data-pjax' => $this->pjax
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

    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string) $key];
        $params = array_merge($params, is_array($this->action)? $this->action: [0 => $this->action]);
        $params[0] = strtr($params[0], [
            '{controller}' => $this->controller,
            '{action}' => $action
        ]);

        return Url::toRoute($params);
    }
}
