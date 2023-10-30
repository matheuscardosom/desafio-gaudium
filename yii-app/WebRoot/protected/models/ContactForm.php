<?php

class ContactForm extends CFormModel {

	public $name;
	public $email;
	public $subject;
	public $body;
	public $verifyCode;

	public function rules() {
		return [
			['name, email, subject, body', 'required'],
			['email', 'email'],
			['verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()],
		];
	}

	public function attributeLabels() {
		return [
			'verifyCode' => 'Verification Code',
		];
	}
}
