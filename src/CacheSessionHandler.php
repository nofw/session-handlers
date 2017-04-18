<?php

namespace Nofw\Session;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * PSR-6 Session handler implementation.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class CacheSessionHandler implements \SessionHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * @param CacheItemPoolInterface $pool
     * @param LoggerInterface|null   $logger
     */
    public function __construct(CacheItemPoolInterface $pool, LoggerInterface $logger = null)
    {
        $this->pool = $pool;
        $this->logger = $logger ?? new NullLogger();
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
            $this->logger->error(
                $e->getMessage(),
                [
                    'exception' => $e,
                    'session_id' => is_scalar($session_id) ? $session_id : '** Non scalar session ID **',
                    'operation' => 'destroy',
                ]
            );

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

            $this->logger->debug('Session not found in the storage', ['sessionId' => $session_id]);

            return '';
        } catch (InvalidArgumentException $e) {
            $this->logger->error(
                $e->getMessage(),
                [
                    'exception' => $e,
                    'session_id' => is_scalar($session_id) ? $session_id : '** Non scalar session ID **',
                    'operation' => 'read',
                ]
            );

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
            $this->logger->error(
                $e->getMessage(),
                [
                    'exception' => $e,
                    'session_id' => is_scalar($session_id) ? $session_id : '** Non scalar session ID **',
                    'operation' => 'write',
                ]
            );

            return false;
        }
    }
}
