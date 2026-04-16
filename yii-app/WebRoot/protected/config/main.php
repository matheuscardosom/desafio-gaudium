<?php

return [
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'Desafio',
	'language' => 'pt_br',
	'timeZone' => 'America/Sao_Paulo',

	'preload' => ['log'],

	'import' => [
		'application.models.*',
		'application.components.*',
	],

	'modules' => [
		'gii' => [
			'class' => 'system.gii.GiiModule',
			'password' => 'admin',
			'ipFilters' => ['*'],
		],
	],

	'components' => [
		'user' => [
			'allowAutoLogin' => true,
		],

		'urlManager' => [
			'urlFormat' => 'path',
			'rules' => [
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		],

		'db' => require(dirname(__FILE__) . '/database.php'),

		'errorHandler' => [
			'errorAction' => YII_DEBUG ? null : 'site/error',
		],

		'log' => [
			'class' => 'CLogRouter',
			'routes' => [
				[
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				],
			],
		],

	],

	'params' => [
		'adminEmail' => 'webmaster@example.com',
	],
];
