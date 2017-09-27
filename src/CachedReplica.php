<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use Psr\Cache\CacheItemPoolInterface;

final class CachedReplica implements CommonEndpointInterface
{

	/**
	 * @var CommonEndpointInterface
	 */
	private $replica;

	/**
	 * @var CacheItemPoolInterface
	 */
	private $cacheItemPool;

	public function __construct(CommonEndpointInterface $replica, CacheItemPoolInterface $cacheItemPool)
	{
		$this->replica = $replica;
		$this->cacheItemPool = $cacheItemPool;
	}

	public function lastHash(): Promise
	{
		$item = $this->cacheItemPool->getItem(__METHOD__);
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->replica->lastHash()->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function lookup(string $hash): Promise
	{
		$item = $this->cacheItemPool->getItem(\sprintf('%s%s', __METHOD__, $hash));
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->replica->lookup($hash)->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function since(string $hash): Promise
	{
		$item = $this->cacheItemPool->getItem(\sprintf('%s%s', __METHOD__, $hash));
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->replica->since($hash)->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

	public function export(): Promise
	{
		$item = $this->cacheItemPool->getItem(__METHOD__);
		return $item->isHit()
			? new FulfilledPromise($item->get())
			: $this->replica->export()->then(function (array $data) use ($item) {
				$this->cacheItemPool->saveDeferred($item->set($data));
				return $data;
			});
	}

}
