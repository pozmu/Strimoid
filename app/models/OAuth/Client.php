<?php

namespace OAuth;

use Jenssegers\Mongodb\Model as Eloquent;

class Client extends Eloquent {

    protected $collection = 'oauth_clients';

    public function user()
    {
        return $this->belongsTo('User');
    }

}