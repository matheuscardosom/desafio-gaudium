<?php
/* @var $this PassageiroController */
/* @var $model Passageiro */

$this->breadcrumbs=array(
	'Passageiros'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Passageiro', 'url'=>array('/passageiro/index')),
	array('label'=>'Create Passageiro', 'url'=>array('/passageiro/create')),
	array('label'=>'Update Passageiro', 'url'=>array('/passageiro/update', 'id'=>$model->id)),
	array('label'=>'Delete Passageiro', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Passageiro', 'url'=>array('/passageiro/admin')),
);
?>

<h1>View Passageiro #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nome',
		'data_nascimento',
		'email',
		'telefone',
		'status',
		'data_hora_status',
		'obs',
	),
)); ?>
