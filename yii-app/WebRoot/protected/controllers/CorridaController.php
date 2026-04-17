<?php

class CorridaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','listar','create','finalizarCorrida'),
				'users'=>array('*'),
			),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array('create','update'),
			// 	'users'=>array('@'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('admin','delete'),
			// 	'users'=>array('admin'),
			// ),
			// array('deny',  // deny all users
			// 	'users'=>array('*'),
			// ),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{ 
		$model = new Corrida('search');
		$model->unsetAttributes();

		if (isset($_GET['Corrida'])) {
			$model->attributes = $_GET['Corrida'];
		}
		
		$this->render('index',array(
			'model'=>$model,
			'dataProvider'=>$model->search(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Corrida('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Corrida']))
			$model->attributes=$_GET['Corrida'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Corrida the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Corrida::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Corrida $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='corrida-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionCreate() {
		header('Content-Type: application/json');

    	$this->validaToken();

		$jsonEntrada = file_get_contents('php://input');
		$dados = CJSON::decode($jsonEntrada);

		$model = new Corrida();
		$model->id_passageiro = $dados['passageiro']['id'];
		$model->endereco_origem = $dados['origem']['endereco'];
		$model->endereco_destino = $dados['destino']['endereco'];
		$model->data_hora_inicio = date('Y-m-d H:i:s');

		$passageiro = Passageiro::model()->findByPk($dados['passageiro']['id']);
		if (!$passageiro) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Passageiro não existe.']);
			Yii::app()->end();
		}
		if ($passageiro->status != 'A') {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Passageiro não está ativo.']);
			Yii::app()->end();
		}
		$corridaEmAndamento = Corrida::model()->find("id_passageiro = :id_passageiro AND status = 'Em andamento'", [
			':id_passageiro' => $dados['passageiro']['id']
		]);
		if ($corridaEmAndamento) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Passageiro já possui uma corrida em andamento.']);
			Yii::app()->end();
		}

		if ($model->endereco_origem == $model->endereco_destino) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Endereço de origem e destino são iguais.']);
			Yii::app()->end();
		}

		$latOrigem = $dados['origem']['lat'];
		$lngOrigem = $dados['origem']['lng'];
		$latDestino = $dados['destino']['lat'];
		$lngDestino = $dados['destino']['lng'];
		$distancia = ceil($this->calcularDistancia($latOrigem, $lngOrigem, $latDestino, $lngDestino));

		if ($distancia <= 100) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Distância insuficiente.']);
			Yii::app()->end();
		}

		$criteria = new CDbCriteria();
		$criteria->addCondition("id NOT IN (
			SELECT id_motorista FROM corrida WHERE status = 'Em andamento'
		)");
		$criteria->addCondition("status = 'A'");

		$motorista = Motorista::model()->find($criteria);
		
		if ($motorista) {
			$model->id_motorista = $motorista->id;
			$model->status = 'Em andamento';
		} else {
			$model->status = 'Não atendida';
			$model->id_motorista = null;
		}

		$minutoPorM = 1;
		$duracaoCorrida = $distancia / 200 * $minutoPorM;
		$previsaoDeChegada = ceil($duracaoCorrida + 3);

		if ($previsaoDeChegada > 480) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Corrida muito longa.']);
			Yii::app()->end();
		}

		$model->previsao_chegada_destino = date('Y-m-d H:i:s', strtotime("+$previsaoDeChegada minutes"));

		$tarifa = 5 + ($distancia / 1000 * 2) + ($previsaoDeChegada * 0.5);
		$model->tarifa = round($tarifa, 2);

		if (!$model->save()) {
			http_response_code(400);
			echo CJSON::encode([
				'sucesso' => false, 
				'erro' => 'Erro ao criar corrida.',
				'detalhes' => $model->getErrors()
			]);
			Yii::app()->end();
		}

		$totalCorridas = 0;
        if ($motorista) {
            $totalCorridas = Corrida::model()->count("id_motorista = :id_motorista", [
                ':id_motorista' => $motorista->id
            ]);
        }

		if ($motorista) {
			http_response_code(200);
			echo CJSON::encode(array(
				'sucesso' => true,
				'corrida' => [
					'id' => $model->id,
					'previsao_chegada_destino' => $model->previsao_chegada_destino,
				],
				'motorista' => [
					'nome' => $motorista->nome,
					'placa' => $motorista->placa,
					'quantidade_corridas' => (int)$totalCorridas
				]
			));
		} else {
			http_response_code(400);
			echo CJSON::encode([
				'sucesso' => false,
				'erro' => 'Nenhum motorista disponível.'
			]);
		}

		Yii::app()->end();
	}

	public function actionFinalizarCorrida() {
		header('Content-Type: application/json');

		$this->validaToken();
		
		$jsonEntrada = file_get_contents('php://input');
		$dados = CJSON::decode($jsonEntrada);

		$idCorrida = $dados['corrida']['id'];
		$idMotorista = $dados['motorista']['id'];

		$corrida = Corrida::model()->findByPk($idCorrida);

		if (!$corrida) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Corrida não existe.']);
			Yii::app()->end();
		}

		if ($corrida->id_motorista != $idMotorista) {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Motorista não corresponde à corrida.']);
			Yii::app()->end();
		}

		if ($corrida->status != 'Em andamento') {
			http_response_code(400);
			echo CJSON::encode(['sucesso' => false, 'erro' => 'Corrida não está em andamento.']);
			Yii::app()->end();
		}

		$corrida->status = 'Finalizada';
		$corrida->data_hora_fim = date('Y-m-d H:i:s');

		if (!$corrida->save()) {
			http_response_code(400);
			echo CJSON::encode([
				'sucesso' => false, 
				'erro' => 'Erro ao finalizar corrida.',
				'detalhes' => $corrida->getErrors()
			]);
			Yii::app()->end();
		}

		http_response_code(200);
		echo CJSON::encode([
			'sucesso' => true,
			'mensagem' => 'Corrida finalizada com sucesso.'
		]);

		Yii::app()->end();
	}

	private function calcularDistancia($latOrigem, $lngOrigem, $latDestino, $lngDestino) {

		$earthRadius = 6371000;

		$latOrigemRad = deg2rad($latOrigem);
		$lngOrigemRad = deg2rad($lngOrigem);
		$latDestinoRad = deg2rad($latDestino);
		$lngDestinoRad = deg2rad($lngDestino);

		$latDelta = $latDestinoRad - $latOrigemRad;
		$lngDelta = $lngDestinoRad - $lngOrigemRad;

		$distancia = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latOrigemRad) * cos($latDestinoRad) * pow(sin($lngDelta / 2), 2)));
		
		return $distancia * $earthRadius;
	}

	private function validaToken() {
		$tokenEnviado = isset($_SERVER['HTTP_API_TOKEN']) ? $_SERVER['HTTP_API_TOKEN'] : null;
    	$caminhoArquivo = Yii::getPathOfAlias('application.config') . '/secret.txt';
    
    	if (!file_exists($caminhoArquivo)) {
        	http_response_code(400);
        	echo CJSON::encode(['sucesso' => false, 'erro' => 'Configuração de segurança ausente no servidor.']);
        	Yii::app()->end();
    	}

    	$tokenEsperado = trim(file_get_contents($caminhoArquivo));

    	if (empty($tokenEnviado) || $tokenEnviado !== $tokenEsperado) {
        	http_response_code(400);
        	echo CJSON::encode(['sucesso' => false, 'erro' => 'Token de API inválido ou ausente.']);
        	Yii::app()->end();
    	}
	}

	public function actionListar()
	{
		header('Content-Type: application/json');

		$corridas = Corrida::model()->findAll();

		if (empty($corridas)) {
			echo CJSON::encode(array(
				'sucesso' => true,
				'erro' => 'Nenhuma corrida encontrada.',
				'dados' => array()
			));
			Yii::app()->end();
		}

		$resultado = array();
		foreach ($corridas as $corrida) {
			$resultado[] = $corrida->attributes;
		}

		echo CJSON::encode(array(
			'sucesso' => true,
			'total' => count($resultado),
			'dados' => $resultado
		));

		Yii::app()->end();
	}
}
