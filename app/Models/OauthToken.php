<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OauthToken extends Model
{
    protected $table = 'oauth_token';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function scopeValidate($query, $request) {
        if($request->query()) {
            if(!array_key_exists('token', $request->query())) {
                abort(404);
            }
            $token = $request->query()['token'];
            $entry = $query->where('token', $token)->first();
            return $entry ? true : abort(404);
        }
        abort(404);
    }
}