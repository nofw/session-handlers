<?php

namespace spec\Nofw\Session;

use Doctrine\Common\Cache\Cache;
use PhpSpec\ObjectBehavior;

class DoctrineCacheSessionHandlerSpec extends ObjectBehavior
{
    function let(Cache $cache)
    {
        $this->beConstructedWith($cache);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nofw\Session\DoctrineCacheSessionHandler');
    }

    function it_is_a_session_handler()
    {
        $this->shouldImplement('SessionHandlerInterface');
    }

    function it_closes_the_handler()
    {
        $this->close()->shouldReturn(true);
    }

    function it_destroys_the_session(Cache $cache)
    {
        $cache->delete('id')->willReturn(true);

        $this->destroy('id')->shouldReturn(true);
    }

    function it_fails_destroying_the_session(Cache $cache)
    {
        $cache->delete('id')->willReturn(false);

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

    function it_reads_the_session(Cache $cache)
    {
        $cache->fetch('id')->willReturn('data');

        $this->read('id')->shouldReturn('data');
    }

    function it_fails_reading_the_session(Cache $cache)
    {
        $cache->fetch('id')->willReturn(false);

        $this->read('id')->shouldReturn('');
    }

    function it_writes_the_session(Cache $cache)
    {
        $cache->save('id', 'data')->willReturn(true);

        $this->write('id', 'data')->shouldReturn(true);
    }

    function it_fails_writing_the_session(Cache $cache)
    {
        $cache->save('id', 'data')->willReturn(false);

        $this->write('id', 'data')->shouldReturn(false);
    }
}
