<?php

namespace Nofw\Session;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * PSR-16 Session handler implementation.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class SimpleCacheSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($session_id)
    {
        try {
            return $this->cache->delete($session_id);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($session_id)
    {
        try {
            return $this->cache->get($session_id);
        } catch (InvalidArgumentException $e) {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($session_id, $session_data)
    {
        try {
            return $this->cache->set($session_id, $session_data);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
