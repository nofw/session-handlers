<?php

namespace Nofw\Session;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * PSR-16 Session handler implementation.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class SimpleCacheSessionHandler implements \SessionHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param CacheInterface       $cache
     * @param LoggerInterface|null $logger
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger = null)
    {
        $this->cache = $cache;
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
            return $this->cache->delete($session_id);
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
            return $this->cache->get($session_id);
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
            return $this->cache->set($session_id, $session_data);
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
