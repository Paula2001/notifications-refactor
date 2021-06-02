<?php


namespace App\Services\MobileNotifications\ThirdParty\ClientOne;


use App\Services\MobileNotifications\ThirdParty\ClientInterface;
use Illuminate\Support\Facades\Log;

class ClientOne implements ClientInterface
{

    public function send(array $payload)
    {
        dd("one");
        Log::info("sent from client one");
    }
}
