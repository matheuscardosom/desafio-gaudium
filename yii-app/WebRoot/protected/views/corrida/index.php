<?php
/* @var $this CorridaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Corridas',
);

?>

<h1>Corridas</h1>

<div class="search-form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
    )); ?>

    <div style="display: flex; gap: 20px; align-items: flex-end;">
        <div>
            <?php echo $form->label($model, 'Data Inicial'); ?><br>
            <input type="datetime-local" name="Corrida[data_hora_inicio]" id="Corrida_data_hora_inicio" value="<?php echo CHtml::encode(str_replace(' ', 'T', $model->data_hora_inicio)); ?>" step="1" />
        </div>

        <div>
            <?php echo $form->label($model, 'Data Final'); ?><br>
            <input type="datetime-local" name="Corrida[data_hora_fim]" id="Corrida_data_hora_fim" value="<?php echo CHtml::encode(str_replace(' ', 'T', $model->data_hora_fim)); ?>" step="1" />
        </div>

        <div>
            <?php echo CHtml::submitButton('Filtrar'); ?>
            <?php echo CHtml::link('Limpar', array('index')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>

<hr />

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
