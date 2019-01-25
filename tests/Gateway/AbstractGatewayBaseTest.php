<?php

namespace SLWDC\PaymentGateways\Tests\Gateway;

use PHPUnit\Framework\TestCase;
use SLWDC\PaymentGateways\Gateway\AbstractGatewayBase;

class AbstractGatewayBaseTest extends TestCase {
  public function testAutoloader() {
  	$this->assertTrue(class_exists(AbstractGatewayBase::class, true));
  }
}
