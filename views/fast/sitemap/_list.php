<?

use grozzzny\sitemap\models\Sitemap;
use yii\easyii2\components\FastModel;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Sitemap $model
 * @var Sitemap $item
 * @var \yii\data\ActiveDataProvider $data
 */

$module = $this->context->module->id;
$sort = $data->getSort();
?>
<table class="table table-hover">
    <thead>
    <tr>
        <th width="50">
            <?=$sort->link('id');?>
        </th>
        <th><?=$sort->link('loc');?></th>
        <th><?=$sort->link('lastmod');?></th>
        <th><?=$sort->link('changefreq');?></th>
        <th><?=$sort->link('priority');?></th>
        <th width="100"><?=$sort->link('status');?></th>
        <th width="<?= $model::ORDER_NUM ? '120' : '40'?>"></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($data->models as $item) : ?>
    <tr>
        <td><?= $item->primaryKey ?></td>
        <td>
            <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $item->id, 'slug' => $item::getSlugModel()]) ?>">
                <?= $item->loc ?>
            </a>
        </td>
        <td>
            <?= $item->getLastmod() ?>
        </td>
        <td>
            <?= $item->getChangefreq() ?>
        </td>
        <td>
            <?= $item->getPriority() ?>
        </td>
        <td class="status vtop">
            <?= Html::checkbox('', $item->status == FastModel::STATUS_ON, [
                'class' => 'my-switch',
                'data-slug' => $item::getSlugModel(),
                'data-id' => $item->id,
                'data-link' => Url::to(['/admin/'.$module.'/a/']),
            ]) ?>
        </td>
        <td>
            <div class="btn-group btn-group-sm" role="group">

                <? if($item::ORDER_NUM):?>
                    <a href="<?= Url::to(['/admin/'.$module.'/a/up', 'id' => $item->primaryKey, 'slug' => $item::getSlugModel()]) ?>" class="btn btn-default move-up" title="<?= Yii::t('easyii2', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
                    <a href="<?= Url::to(['/admin/'.$module.'/a/down', 'id' => $item->primaryKey, 'slug' => $item::getSlugModel()]) ?>" class="btn btn-default move-down" title="<?= Yii::t('easyii2', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
                <? endif;?>

                <a href="<?= Url::to(['/admin/'.$module.'/a/delete', 'id' => $item->primaryKey, 'slug' => $item::getSlugModel()]) ?>" class="btn btn-default confirm-delete" title="<?= Yii::t('easyii2', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </td>
    <tr>
    <? endforeach; ?>
    </tbody>
</table>