<?php


namespace App\Services\MobileNotifications\ThirdParty\ClientTwo;


use App\Services\MobileNotifications\ThirdParty\ClientInterface;
use Illuminate\Support\Facades\Log;

class ClientTwo implements ClientInterface
{

    public function send(array $payload)
    {
        Log::info("sent from client two payload" . json_encode($payload));
    }
}
