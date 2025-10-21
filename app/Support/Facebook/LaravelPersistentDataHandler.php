<?php

namespace App\Support\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Illuminate\Contracts\Session\Session as SessionStore;

class LaravelPersistentDataHandler implements PersistentDataInterface
{
    protected SessionStore $session;
    protected string $prefix;

    public function __construct(SessionStore $session, string $prefix = 'fb_')
    {
        $this->session = $session;
        $this->prefix  = $prefix;
    }

    public function get($key)
    {
        return $this->session->get($this->prefix.$key);
    }

    public function set($key, $value)
    {
        $this->session->put($this->prefix.$key, $value);
    }
}
