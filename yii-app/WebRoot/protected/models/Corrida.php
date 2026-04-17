<?php

/**
 * This is the model class for table "corrida".
 *
 * The followings are the available columns in table 'corrida':
 * @property integer $id
 * @property integer $id_passageiro
 * @property integer $id_motorista
 * @property string $endereco_origem
 * @property string $endereco_destino
 * @property string $data_hora_inicio
 * @property string $status
 * @property string $previsao_chegada_destino
 * @property string $tarifa
 * @property string $data_hora_fim
 *
 * The followings are the available model relations:
 * @property Motorista $idMotorista
 * @property Passageiro $idPassageiro
 */
class Corrida extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'corrida';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_passageiro, endereco_origem, endereco_destino, data_hora_inicio, previsao_chegada_destino, tarifa', 'required'),
			array('id_passageiro, id_motorista', 'numerical', 'integerOnly'=>true),
			array('endereco_origem, endereco_destino', 'length', 'max'=>255),
			array('status', 'length', 'max'=>13),
			array('tarifa', 'length', 'max'=>10),
			array('data_hora_fim', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_passageiro, id_motorista, endereco_origem, endereco_destino, data_hora_inicio, status, previsao_chegada_destino, tarifa, data_hora_fim', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'motoristaRel' => array(self::BELONGS_TO, 'Motorista', 'id_motorista'),
			'passageiroRel' => array(self::BELONGS_TO, 'Passageiro', 'id_passageiro'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_passageiro' => 'Id Passageiro',
			'id_motorista' => 'Id Motorista',
			'endereco_origem' => 'Endereco Origem',
			'endereco_destino' => 'Endereco Destino',
			'data_hora_inicio' => 'Data Hora Inicio',
			'status' => 'Status',
			'previsao_chegada_destino' => 'Previsao Chegada Destino',
			'tarifa' => 'Tarifa',
			'data_hora_fim' => 'Data Hora Fim',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->with = array('passageiroRel', 'motoristaRel');

		$criteria->compare('id',$this->id);
		$criteria->compare('id_passageiro',$this->id_passageiro);
		$criteria->compare('id_motorista',$this->id_motorista);
		$criteria->compare('endereco_origem',$this->endereco_origem,true);
		$criteria->compare('endereco_destino',$this->endereco_destino,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('previsao_chegada_destino',$this->previsao_chegada_destino,true);
		$criteria->compare('tarifa',$this->tarifa,true);

		$data_inicio = str_replace('T', ' ', $this->data_hora_inicio);
		$data_fim = str_replace('T', ' ', $this->data_hora_fim);

		if (!empty($this->data_hora_inicio) && !empty($this->data_hora_fim)) {
			$criteria->addBetweenCondition('t.data_hora_inicio', $data_inicio, $data_fim);
		} elseif (!empty($this->data_hora_inicio)) {
			$criteria->addCondition('t.data_hora_inicio >= :data_hora_inicio');
			$criteria->params[':data_hora_inicio'] = $data_inicio;
		} elseif (!empty($this->data_hora_fim)) {
			$criteria->addCondition('t.data_hora_inicio <= :data_hora_fim');
			$criteria->params[':data_hora_fim'] = $data_fim;
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.status = "Em andamento" DESC, t.data_hora_inicio DESC',
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Corrida the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
