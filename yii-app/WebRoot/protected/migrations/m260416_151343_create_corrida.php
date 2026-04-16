<?php

class m260416_151343_create_corrida extends CDbMigration
{
	public function up()
	{
		$sql = "CREATE TABLE `corrida` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`id_passageiro` int(11) NOT NULL,
			`id_motorista` int(11) DEFAULT NULL,
			`endereco_origem` varchar(255) NOT NULL,
			`endereco_destino` varchar(255) NOT NULL,
			`data_hora_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  			`status` enum('Em andamento','Não atendida','Finalizada') NOT NULL DEFAULT 'Não atendida',
			`previsao_chegada_destino` datetime NOT NULL,
			`tarifa` decimal(10,2) NOT NULL,
			`data_hora_fim` timestamp NULL DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `fk_corrida_passageiro` (`id_passageiro`),
			KEY `fk_corrida_motorista` (`id_motorista`),
			CONSTRAINT `fk_corrida_motorista` FOREIGN KEY (`id_motorista`) REFERENCES `motorista` (`id`),
			CONSTRAINT `fk_corrida_passageiro` FOREIGN KEY (`id_passageiro`) REFERENCES `passageiro` (`id`)
		)";

		$this->execute($sql);
	}

	public function down()
	{
		echo "m260416_151343_create_corrida does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}