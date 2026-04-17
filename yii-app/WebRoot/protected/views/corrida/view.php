<?php
/* @var $this CorridaController */
/* @var $model Corrida */

$this->breadcrumbs=array(
	'Corridas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Corrida', 'url'=>array('index')),
);
?>

<h1>Detalhes da Corrida #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'name' => 'passageiroRel.nome',
			'label' => 'Nome do passageiro',
		),
		array(
			'name' => 'motoristaRel.nome',
			'label' => 'Nome do motorista',
		),
		array(
			'name' => 'endereco_origem',
			'label' => 'Endereço de Origem',
		),
		array(
			'name' => 'endereco_destino',
			'label' => 'Endereço de Destino',
		),
		array(
			'name' => 'data_hora_inicio',
			'label' => 'Data e Hora de Início',
		),
		array(
			'name' => 'status',
			'label' => 'Status',
		),
		array(
			'name' => 'previsao_chegada_destino',
			'label' => 'Previsão de Chegada',
		),
		array(
			'name' => 'tarifa',
			'label' => 'Tarifa',
		),
		array(
			'name' => 'data_hora_fim',
			'label' => 'Data e Hora de Fim',
		),
	),
)); ?>
