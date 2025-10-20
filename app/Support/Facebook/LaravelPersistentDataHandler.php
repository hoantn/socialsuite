<?php

namespace App\Support\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Illuminate\Contracts\Session\Session as SessionContract;

class LaravelPersistentDataHandler implements PersistentDataInterface
{
    protected SessionContract $session;
    protected string $prefix;

    public function __construct(SessionContract $session, string $prefix = 'fb_')
    {
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
