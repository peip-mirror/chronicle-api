<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use Psr\Cache\CacheItemPoolInterface;

final class CachedApi implements ApiInterface
{

	/**
	 * @var ApiInterface
	 */
	private $api;

	/**
	 * @var CacheItemPoolInterface
	 */
	private $cacheItemPool;

	public function __construct(ApiInterface $api, CacheItemPoolInterface $cacheItemPool)
	{
		$this->api = $api;
		$this->cacheItemPool = $cacheItemPool;
	}

	public function lastHash(): Promise
	{
		$item = $this->cacheItemPool->getItem(__METHOD__);
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->api->lastHash()->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function lookup(string $hash): Promise
	{
		$item = $this->cacheItemPool->getItem(\sprintf('%s%s', __METHOD__, $hash));
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->api->lookup($hash)->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function since(string $hash): Promise
	{
		$item = $this->cacheItemPool->getItem(\sprintf('%s%s', __METHOD__, $hash));
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->api->since($hash)->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function export(): Promise
	{
		$item = $this->cacheItemPool->getItem(__METHOD__);
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->api->export()->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function index(): Promise
	{
		$item = $this->cacheItemPool->getItem(__METHOD__);
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->api->index()->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function register(SigningPublicKey $publicKey, string $comment = null): Promise
	{
		return $this->api->register($publicKey, $comment);
	}

	public function revoke(string $clientId, SigningPublicKey $publicKey): Promise
	{
		return $this->api->revoke($clientId, $publicKey);
	}

	public function publish(string $message): Promise
	{
		return $this->api->publish($message);
	}

	public function replica(string $source): CommonEndpointInterface
	{
		return new CachedReplica($this->api->replica($source), $this->cacheItemPool);
	}

	public function replicas(): Promise
	{
		$item = $this->cacheItemPool->getItem(__METHOD__);
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->api->replicas()->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

}
