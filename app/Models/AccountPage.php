<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPage extends Model {
    protected $table = 'account_page';
    protected $fillable = ['fb_account_id','page_id','role','granted_scopes'];
    protected $casts = ['granted_scopes' => 'array'];

    public function grantedScopes(): array {
        return $this->granted_scopes ?? [];
    }
}
