<?php

class m260416_131821_create_motorista extends CDbMigration
{
	public function up()
	{
		$sql = "CREATE TABLE `motorista` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`nome` varchar(150) NOT NULL COMMENT 'Nome do motorista',
			`data_nascimento` date NOT NULL COMMENT 'Data de Nascimento do motorista',
			`email` varchar(100) NOT NULL COMMENT 'Email do motorista',
			`telefone` varchar(16) NOT NULL COMMENT 'Telefone do motorista',
			`placa` varchar(8) NOT NULL COMMENT 'Placa do veículo do motorista',
			`status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status do motorista',
			`data_hora_status` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do status',
			`obs` varchar(200) DEFAULT NULL COMMENT 'Observações do motorista',
			PRIMARY KEY (`id`)
		);";

		$this->execute($sql);
	}

	public function down()
	{
		echo "m260416_131821_create_motorista does not support migration down.\n";
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