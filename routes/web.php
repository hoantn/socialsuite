use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    return inertia('Dashboard');
});

Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
Route::post('/inbox/send', [InboxController::class, 'send'])->name('inbox.send');
Route::get('/auth/facebook/redirect', [OAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [OAuthController::class, 'callback'])->name('facebook.callback');

Route::match(['get', 'post'], '/webhook/facebook', [WebhookController::class, 'handle'])->name('facebook.webhook');