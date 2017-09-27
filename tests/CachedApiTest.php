<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use PHPUnit\Framework\TestCase;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @covers \Lookyman\Chronicle\CachedApi
 */
final class CachedApiTest extends TestCase
{

	public function testLastHashHit()
	{
		$api = $this->createMock(ApiInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lastHash', CachedApi::class))->willReturn($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->lastHash()->wait());
	}

	public function testLastHashMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('lastHash')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lastHash', CachedApi::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->lastHash()->wait());
	}

	public function testLookupHit()
	{
		$api = $this->createMock(ApiInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lookupfoo', CachedApi::class))->willReturn($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->lookup('foo')->wait());
	}

	public function testLookupMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('lookup')->with('foo')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::lookupfoo', CachedApi::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->lookup('foo')->wait());
	}

	public function testSinceHit()
	{
		$api = $this->createMock(ApiInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::sincefoo', CachedApi::class))->willReturn($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->since('foo')->wait());
	}

	public function testSinceMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('since')->with('foo')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::sincefoo', CachedApi::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->since('foo')->wait());
	}

	public function testExportHit()
	{
		$api = $this->createMock(ApiInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::export', CachedApi::class))->willReturn($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->export()->wait());
	}

	public function testExportMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('export')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::export', CachedApi::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->export()->wait());
	}

	public function testIndexHit()
	{
		$api = $this->createMock(ApiInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::index', CachedApi::class))->willReturn($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->index()->wait());
	}

	public function testIndexMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('index')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::index', CachedApi::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->index()->wait());
	}

	public function testRegister()
	{
		$key = new SigningPublicKey((string) Base64UrlSafe::decode('aAtpZ1BH8GbmKbXx7IN7_pTN9fM9WwGiZmKUajsLi6Q='));

		$promise = new FulfilledPromise(['result']);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('register')->with($key, 'foo')->willReturn($promise);

		$pool = $this->createMock(CacheItemPoolInterface::class);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->register($key, 'foo')->wait());
	}

	public function testRevoke()
	{
		$key = new SigningPublicKey((string) Base64UrlSafe::decode('aAtpZ1BH8GbmKbXx7IN7_pTN9fM9WwGiZmKUajsLi6Q='));

		$promise = new FulfilledPromise(['result']);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('revoke')->with('foo', $key)->willReturn($promise);

		$pool = $this->createMock(CacheItemPoolInterface::class);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->revoke('foo', $key)->wait());
	}

	public function testPublish()
	{
		$promise = new FulfilledPromise(['result']);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('publish')->with('foo')->willReturn($promise);

		$pool = $this->createMock(CacheItemPoolInterface::class);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->publish('foo')->wait());
	}

	public function testReplica()
	{
		$replica = $this->createMock(CommonEndpointInterface::class);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('replica')->with('foo')->willReturn($replica);

		$pool = $this->createMock(CacheItemPoolInterface::class);

		$cachedApi = new CachedApi($api, $pool);

		$cachedReplica = $cachedApi->replica('foo');
		self::assertInstanceOf(CachedReplica::class, $cachedReplica);

		$reflectionPropertyReplica = new \ReflectionProperty(CachedReplica::class, 'replica');
		$reflectionPropertyReplica->setAccessible(\true);
		self::assertSame($replica, $reflectionPropertyReplica->getValue($cachedReplica));

		$reflectionPropertyCacheItemPool = new \ReflectionProperty(CachedReplica::class, 'cacheItemPool');
		$reflectionPropertyCacheItemPool->setAccessible(\true);
		self::assertSame($pool, $reflectionPropertyCacheItemPool->getValue($cachedReplica));
	}

	public function testReplicasHit()
	{
		$api = $this->createMock(ApiInterface::class);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\true);
		$item->expects(self::once())->method('get')->willReturn(['result']);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::replicas', CachedApi::class))->willReturn($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->replicas()->wait());
	}

	public function testReplicasMiss()
	{
		$promise = $this->createMock(Promise::class);
		$promise->expects(self::once())->method('then')->with(self::callback(function (callable $then) use ($promise) {
			$promise->expects(self::once())->method('wait')->willReturn($then(['result']));
			return \true;
		}))->willReturn($promise);

		$api = $this->createMock(ApiInterface::class);
		$api->expects(self::once())->method('replicas')->willReturn($promise);

		$item = $this->createMock(CacheItemInterface::class);
		$item->expects(self::once())->method('isHit')->willReturn(\false);
		$item->expects(self::once())->method('set')->with(['result'])->willReturn($item);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->expects(self::once())->method('getItem')->with(\sprintf('%s::replicas', CachedApi::class))->willReturn($item);
		$pool->expects(self::once())->method('saveDeferred')->with($item);

		$cachedApi = new CachedApi($api, $pool);

		self::assertEquals(['result'], $cachedApi->replicas()->wait());
	}

}
