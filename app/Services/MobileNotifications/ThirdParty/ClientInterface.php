<?php


namespace App\Services\MobileNotifications\ThirdParty;


interface ClientInterface
{
    public function send(array $payload);
}
