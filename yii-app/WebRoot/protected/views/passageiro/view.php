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



<h1 style="margin-top: 20px;">Últimas corridas</h1>

<table>
	<thead>
		<tr>
			<th>Data/Hora de início</th>
			<th>Destino</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($corridas as $corrida): ?>
			<tr>
				<td><?php echo $corrida->data_hora_inicio; ?></td>
				<td><?php echo $corrida->endereco_destino; ?></td>
				<td><?php echo $corrida->status; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php if (empty($corridas)): ?>
    <p>Você ainda não realizou nenhuma corrida.</p>
<?php endif; ?>
