<?php

namespace SLWDC\PaymentGateways\Tests\Token;

use PHPUnit\Framework\TestCase;
use SLWDC\PaymentGateways\Exception\BadMethodCallException;
use SLWDC\PaymentGateways\Token\CSRFToken;

class CSRFTokenTest extends TestCase {
	public function testCSRFTokenGenerationFailure() {
		$class = new CSRFToken();
		$this->expectException(BadMethodCallException::class);
		$class->getToken();
	}

	public function testCSRFTokenGeneration() {
		$secret = 'Foo';
		$data = ['key' => ''];

		$class = new CSRFToken();
		$class->setSecretKey($secret);
		$class->setAdditionalData($data);
		$token = $class->getToken();

		$class_new = new CSRFToken();
		$class_new->setSecretKey($secret);
		$class_new->setAdditionalData($data);
		$this->assertTrue($class_new->validate($token));

		$token = str_rot13($token);
		$this->assertFalse($class_new->validate($token));
	}
}
