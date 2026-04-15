<?php

/**
 * This is the model class for table "passageiro".
 *
 * The followings are the available columns in table 'passageiro':
 * @property integer $id
 * @property string $nome
 * @property string $email
 * @property string $telefone
 * @property string $status
 * @property string $data_hora_status
 * @property string $obs
 */
class Passageiro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'passageiro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nome, email, telefone, data_hora_status', 'required'),
			array('nome', 'length', 'max'=>150),
			array('email', 'email', 'message'=>'O formato do e-mail é inválido.'),
			array('telefone', 'length', 'max'=>16),
			array('status', 'length', 'max'=>1),
			array('obs', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nome, email, telefone, status, data_hora_status, obs', 'safe', 'on'=>'search'),

			array('nome', 'match', 'pattern'=>'/^[a-zA-ZÀ-ÿ]{3,} [a-zA-ZÀ-ÿ]{3,}/', 
              'message'=>'O nome deve ter no mínimo duas palavras, cada uma com no mínimo 3 caracteres.'),

			array('telefone', 'match', 'pattern'=>'/^\+[0-9]{2}-[0-9]{2}-[0-9]{9}$/', 
              'message'=>'Telefone deve seguir o padrão +99-99-999999999.'),

			array('status', 'in', 'range' => array('A', 'I'),
              'message' => 'O Status deve ser "A" (Ativo) ou "I" (Inativo).'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nome' => 'Nome',
			'email' => 'Email',
			'telefone' => 'Telefone',
			'status' => 'Status',
			'data_hora_status' => 'Data Hora Status',
			'obs' => 'Obs',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telefone',$this->telefone,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('data_hora_status',$this->data_hora_status,true);
		$criteria->compare('obs',$this->obs,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Passageiro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
