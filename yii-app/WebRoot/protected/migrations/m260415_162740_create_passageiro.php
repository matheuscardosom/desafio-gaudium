<?php

class m260415_162740_create_passageiro extends CDbMigration
{
	public function up() {
		$sql = "CREATE TABLE `passageiro` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`nome` varchar(150) NOT NULL COMMENT 'Nome do passageiro',
			`data_nascimento` date NOT NULL COMMENT 'Data de nascimento do passageiro',
			`email` varchar(100) NOT NULL COMMENT 'Email do passageiro',
			`telefone` varchar(16) NOT NULL COMMENT 'Telefone do passageiro',
			`status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status do passageiro',
			`data_hora_status` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do status',
			`obs` varchar(200) DEFAULT NULL COMMENT 'Observações do passageiro',
			PRIMARY KEY (`id`)
		)";

		$this->execute($sql);
	}

	public function down()
	{
		echo "m260415_162740_create_passageiro does not support migration down.\n";
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