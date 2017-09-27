<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Promise\Promise;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @covers \Lookyman\Chronicle\CachedReplica
 */
final class CachedReplicaTest extends TestCase
{

	public function testLastHashHit()
	{
		$replica = $this->createMock(CommonEndpointInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lastHash', CachedReplica::class))->willReturn($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->lastHash()->wait());
	}

	public function testLastHashMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$replica = $this->createMock(CommonEndpointInterface::class);
		$replica->expects(self::once())->method('lastHash')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lastHash', CachedReplica::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->lastHash()->wait());
	}

	public function testLookupHit()
	{
		$replica = $this->createMock(CommonEndpointInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lookupfoo', CachedReplica::class))->willReturn($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->lookup('foo')->wait());
	}

	public function testLookupMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$replica = $this->createMock(CommonEndpointInterface::class);
		$replica->expects(self::once())->method('lookup')->with('foo')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lookupfoo', CachedReplica::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->lookup('foo')->wait());
	}

	public function testSinceHit()
	{
		$replica = $this->createMock(CommonEndpointInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::sincefoo', CachedReplica::class))->willReturn($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->since('foo')->wait());
	}

	public function testSinceMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$replica = $this->createMock(CommonEndpointInterface::class);
		$replica->expects(self::once())->method('since')->with('foo')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::sincefoo', CachedReplica::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->since('foo')->wait());
	}

	public function testExportHit()
	{
		$replica = $this->createMock(CommonEndpointInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::export', CachedReplica::class))->willReturn($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->export()->wait());
	}

	public function testExportMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$replica = $this->createMock(CommonEndpointInterface::class);
		$replica->expects(self::once())->method('export')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::export', CachedReplica::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedReplica = new CachedReplica($replica, $pool);

		self::assertEquals(['result'], $cachedReplica->export()->wait());
	}

}
