<?php

namespace App\Extensions;

use Illuminate\Session\DatabaseSessionHandler;

class SessionHandler extends DatabaseSessionHandler
{
    protected function getDefaultPayload($data)
    {
        $payload = parent::getDefaultPayload($data);

        // Add your custom column value
        $payload['registrant_code'] = session('registrant_code');

        return $payload;
    }
}



?>