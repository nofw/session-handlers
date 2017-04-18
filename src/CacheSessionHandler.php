<?php

namespace Nofw\Session;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * PSR-6 Session handler implementation.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class CacheSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * @param CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
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
            return $this->pool->deleteItem($session_id);
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
            $item = $this->pool->getItem($session_id);

            if ($item->isHit()) {
                return $item->get();
            }

            return '';
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
            $item = $this->pool->getItem($session_id);

            $item->set($session_data);

            return $this->pool->save($item);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
