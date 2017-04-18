<?php

namespace spec\Nofw\Session;

use Nofw\Session\SimpleCacheSessionHandler;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class SimpleCacheSessionHandlerSpec extends ObjectBehavior
{
    function let(CacheInterface $cache)
    {
        $this->beConstructedWith($cache);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleCacheSessionHandler::class);
    }

    function it_is_a_session_handler()
    {
        $this->shouldImplement(\SessionHandlerInterface::class);
    }

    function it_closes_the_handler()
    {
        $this->close()->shouldReturn(true);
    }

    function it_destroys_the_session(CacheInterface $cache)
    {
        $cache->delete('id')->willReturn(true);

        $this->destroy('id')->shouldReturn(true);
    }

    function it_fails_destroying_the_session(CacheInterface $cache)
    {
        $cache->delete('id')->willReturn(false);

        $this->destroy('id')->shouldReturn(false);
    }

    function it_seriously_fails_destroying_the_session(CacheInterface $cache)
    {
        $cache->delete('id')->willThrow(MockSimpleCacheInvalidArgumentException::class);

        $this->destroy('id')->shouldReturn(false);
    }

    function it_collects_garbage()
    {
        $this->gc(1234)->shouldReturn(true);
    }

    function it_opens_session()
    {
        $this->open('path', 'name')->shouldReturn(true);
    }

    function it_reads_the_session(CacheInterface $cache)
    {
        $cache->get('id')->willReturn('data');

        $this->read('id')->shouldReturn('data');
    }

    function it_fails_reading_the_session(CacheInterface $cache)
    {
        $cache->get('id')->willThrow(MockSimpleCacheInvalidArgumentException::class);

        $this->read('id')->shouldReturn('');
    }

    function it_writes_the_session(CacheInterface $cache)
    {
        $cache->set('id', 'data')->willReturn(true);

        $this->write('id', 'data')->shouldReturn(true);
    }

    function it_fails_writing_the_session(CacheInterface $cache)
    {
        $cache->set('id', 'data')->willReturn(false);

        $this->write('id', 'data')->shouldReturn(false);
    }

    function it_seriously_fails_writing_the_session(CacheInterface $cache)
    {
        $cache->set('id', 'data')->willThrow(MockSimpleCacheInvalidArgumentException::class);

        $this->write('id', 'data')->shouldReturn(false);
    }
}

class MockSimpleCacheInvalidArgumentException extends \Exception implements InvalidArgumentException {}
