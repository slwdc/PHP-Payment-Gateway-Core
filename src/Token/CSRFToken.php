<?php


namespace SLWDC\PaymentGateways\Token;


use Ayesh\StatelessCSRF\StatelessCSRF;
use SLWDC\PaymentGateways\Exception\BadMethodCallException;
use SLWDC\PaymentGateways\Order\Order;

class CSRFToken {
	/**
	 * @var int
	 */
	private $ttl;

	/**
	 * @var Order
	 */
	private $order;

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @var string
	 */
	private $merchant_secret;

	/**
	 * @var string
	 */
	private $secret;

	public function setTTL(int $ttl = null) : void {
		$this->ttl = $ttl;
	}

	public function setSecretKey(string $secret_key): void {
		$this->secret = $secret_key;
	}

	public function setOrder(Order $order): void {
		$this->order = $order;
	}

	public function setAdditionalData(array $data): void {
		$this->data = $data;
	}

	public function setMerchantSecret(string $secret): void {
		$this->merchant_secret = $secret;
	}

	public function getToken(): string {
		$generator = $this->configureGenerator();
		return $generator->getToken();
	}

	private function configureGenerator(): StatelessCSRF {
		if ($this->secret === null) {
			throw new BadMethodCallException('Attempt to generate a token without setting secret key.');
		}
		$generator = new StatelessCSRF($this->secret, $this->ttl);
		foreach ($this->getOrderFields() as $key => $value) {
			$generator->addData($value, $key);
		}

		foreach ($this->data as $key => $value) {
			$generator->addData($value, $key);
		}

		return $generator;
	}

	private function getOrderFields(): array {
		return [];
	}

	public function validate(string $given_token): bool {
		$generator = $this->configureGenerator();
		$generator->setToken($given_token);
		return $generator->validate();
	}

	/**
	 * Override debug info to hide secret and merchant secret in case things
	 * go south.
	 *
	 * @return array
	 */
	public function __debugInfo() {
		return [
			'ttl' => $this->ttl,
			'order' => $this->order,
			'data' => $this->data
		];
	}
}
