<?php
/* @var $this CorridaController */
/* @var $data Corrida */
?>

<div class="view">
	<b><?php echo CHtml::encode('Situação: '); ?></b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode('Data e hora de início: '); ?></b>
	<?php echo CHtml::encode($data->data_hora_inicio); ?>
	<br />

	<b><?php echo CHtml::encode('Motorista: '); ?></b>
	<?php echo CHtml::encode($data->motoristaRel->nome); ?>
	<br />

	<b><?php echo CHtml::encode('Passageiro: '); ?></b>
	<?php echo CHtml::encode($data->passageiroRel->nome); ?>
	<br />

	<b><?php echo CHtml::encode('Origem: '); ?></b>
	<?php echo CHtml::encode($data->endereco_origem); ?>
	<br />

	<b><?php echo CHtml::encode('Destino: '); ?></b>
	<?php echo CHtml::encode($data->endereco_destino); ?>
	<br />

	<button style="font-size: 13px; margin-top: 10px;" onclick="window.location.href='<?php echo $this->createUrl('view', array('id'=>$data->id)); ?>'">
		Detalhes da corrida
	</button>

</div>