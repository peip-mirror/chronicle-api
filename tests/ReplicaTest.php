<?php
declare(strict_types=1);

namespace Lookyman\Chronicle;

use Http\Client\HttpAsyncClient;
use Http\Promise\FulfilledPromise;
use Interop\Http\Factory\RequestFactoryInterface;
use PHPUnit\Framework\TestCase;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Lookyman\Chronicle\Replica
 * @covers \Lookyman\Chronicle\AbstractApi
 */
final class ReplicaTest extends TestCase
{

	public function testLastHash(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/aaa/lasthash'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			'aaa',
			$publicKey
		);

		self::assertEquals(['result'], $replica->lastHash()->wait());
	}

	public function testLookup(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/aaa/lookup/foo'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			'aaa',
			$publicKey
		);

		self::assertEquals(['result'], $replica->lookup('foo')->wait());
	}

	public function testSince(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/aaa/since/foo'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			'aaa',
			$publicKey
		);

		self::assertEquals(['result'], $replica->since('foo')->wait());
	}

	public function testExport(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/aaa/export'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			'aaa',
			$publicKey
		);

		self::assertEquals(['result'], $replica->export()->wait());
	}

}
