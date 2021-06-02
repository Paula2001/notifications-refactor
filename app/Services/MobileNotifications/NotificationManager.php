<?php


namespace App\Services\MobileNotifications;

use App\Device;
use App\Enums\DeviceStateEnum;
use Illuminate\Database\Eloquent\Collection;
use App\Services\MobileNotifications\ThirdParty\ClientInterface;

class NotificationManager
{
    private ClientInterface $client;
    private string $messageBody ;
    private ?array $metaData ;

    public function __construct(
        ClientInterface $client,
        string $messageBody,
        ?array $metaData
    ){
        $this->client = $client;
        $this->messageBody = $messageBody;
        $this->metaData = $metaData?:['icon' => 'default.ico'];
    }

    public function notifyAllUsers(?Collection $devices): bool
    {
        $oneMessageAtLeastSent = false ;
        foreach($devices as $device){
            $this->sendNotificationToDevice($device);
            $oneMessageAtLeastSent = true;
        }
        return $oneMessageAtLeastSent;
    }

    public function notifyUser(Device $device):bool{
        $this->sendNotificationToDevice($device);
        return true;
    }

    private function sendNotificationToDevice(Device $device):void{
        $this->setDeviceStateInMetaData($device->state);
        $payload = $this->getPayload($device->registration_id);
        $this->client->send($payload);
    }

    private function setDeviceStateInMetaData(string $deviceState):void{
        $this->metaData['is_active'] = $deviceState === DeviceStateEnum::ACTIVE;
    }

    private function getPayload(string $registrationId):array{
        return [
            'message' => $this->messageBody,
            'registration_id' => $registrationId,
            'data' => $this->metaData
        ];
    }
}
