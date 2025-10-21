# Hotfix: Session contract for Facebook persistent data handler

Error you saw:
`TypeError: App\Support\Facebook\LaravelPersistentDataHandler::__construct(): Argument #1 ($session) must be of type Illuminate\Contracts\Session\Session, Illuminate\Session\SessionManager given`

**Fix:** Inject `Illuminate\Contracts\Session\Session` into `FacebookClient` and pass it to
`LaravelPersistentDataHandler`. Do not call `app('session')` (SessionManager).

After copy:
```
php artisan optimize:clear
```
