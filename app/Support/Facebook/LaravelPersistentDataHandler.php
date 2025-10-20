<?php

namespace App\Support\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Illuminate\Contracts\Session\Session as SessionContract;
use Illuminate\Session\SessionManager;

/**
 * Persistent data handler that stores OAuth state in Laravel session.
 * Accepts either SessionManager or Session Store and normalizes to Store.
 */
class LaravelPersistentDataHandler implements PersistentDataInterface
{
    /** @var SessionContract */
    protected $session;
    protected string $prefix;

    /**
     * @param \Illuminate\Contracts\Session\Session|\Illuminate\Session\SessionManager $session
     */
    public function __construct($session, string $prefix = 'fb_')
    {
        if ($session instanceof SessionManager) {
            // Normalize to the underlying Store (implements Contracts\Session\Session)
            $session = $session->driver();
        }
        $this->session = $session;
        $this->prefix  = $prefix;
    }

    protected function key(string $key): string
    {
        return $this->prefix.$key;
    }

    public function get($key)
    {
        return $this->session->get($this->key($key));
    }

    public function set($key, $value)
    {
        $this->session->put($this->key($key), $value);
    }
}
