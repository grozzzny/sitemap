<?php

use grozzzny\sitemap\models\Sitemap;
use kartik\select2\Select2;
use yii\easyii2\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use grozzzny\widgets\switch_checkbox\SwitchCheckbox;
use yii\web\View;
/**
 * @var View $this
 * @var Sitemap $model
 */

$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>


<?= $form->field($model, 'loc') ?>
<?= $form->field($model, 'lastmod')->widget(DateTimePicker::className()); ?>
<?= $form->field($model, 'changefreq')->widget(Select2::className(), [
    'data' => Sitemap::listChangefreq(),
    'options' => ['value' => empty($model->changefreq) ? Sitemap::CHANGEFREQ_MONTHLY : $model->changefreq],
]) ?>
<?= $form->field($model, 'priority')->widget(Select2::className(), [
    'data' => Sitemap::listPriority(),
    'options' => ['value' => empty($model->priority) ? Sitemap::PRIORITY_50 : $model->priority],
]) ?>

<?=SwitchCheckbox::widget([
    'model' => $model,
    'attributes' => [
        'status'
    ]
])?>

<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>