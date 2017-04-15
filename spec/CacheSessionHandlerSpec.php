<?php

namespace spec\Nofw\Session;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use PhpSpec\ObjectBehavior;

class CacheSessionHandlerSpec extends ObjectBehavior
{
    function let(CacheItemPoolInterface $pool)
    {
        $this->beConstructedWith($pool);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nofw\Session\CacheSessionHandler');
    }

    function it_is_a_session_handler()
    {
        $this->shouldImplement('SessionHandlerInterface');
    }

    function it_closes_the_handler()
    {
        $this->close()->shouldReturn(true);
    }

    function it_destroys_the_session(CacheItemPoolInterface $pool)
    {
        $pool->deleteItem('id')->willReturn(true);

        $this->destroy('id')->shouldReturn(true);
    }

    function it_fails_destroying_the_session(CacheItemPoolInterface $pool)
    {
        $pool->deleteItem('id')->willReturn(false);

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

    function it_reads_the_session(CacheItemPoolInterface $pool, CacheItemInterface $item)
    {
        $pool->getItem('id')->willReturn($item);
        $item->isHit()->willReturn(true);
        $item->get()->willReturn('data');

        $this->read('id')->shouldReturn('data');
    }

    function it_fails_reading_the_session(CacheItemPoolInterface $pool, CacheItemInterface $item)
    {
        $pool->getItem('id')->willReturn($item);
        $item->isHit()->willReturn(false);
        $item->get()->shouldNotBeCalled();

        $this->read('id')->shouldReturn('');
    }

    function it_writes_the_session(CacheItemPoolInterface $pool, CacheItemInterface $item)
    {
        $pool->getItem('id')->willReturn($item);
        $item->set('data')->shouldBeCalled();
        $pool->save($item)->willReturn(true);

        $this->write('id', 'data')->shouldReturn(true);
    }

    function it_fails_writing_the_session(CacheItemPoolInterface $pool, CacheItemInterface $item)
    {
        $pool->getItem('id')->willReturn($item);
        $item->set('data')->shouldBeCalled();
        $pool->save($item)->willReturn(false);

        $this->write('id', 'data')->shouldReturn(false);
    }
}
