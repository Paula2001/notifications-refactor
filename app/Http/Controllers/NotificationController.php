<?php

namespace App\Http\Controllers;

use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendNotificationAllUsersRequest;
use App\Http\Requests\SendNotificationForAUserRequest;
use App\Providers\RouteServiceProvider;
use App\Services\MobileNotifications\NotificationManager;
use App\Services\MobileNotifications\ThirdParty\ClientInterface;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class NotificationController extends Controller
{
    private NotificationManager $notificaionManager ;
    private ClientInterface $client;
    private Device $device;

    public function __construct(ClientInterface $client ,Device $device)
    {
        $this->client = $client;
        $this->device = $device;
    }

    public function sendNotificationForAllUsers(SendNotificationAllUsersRequest $sendNotificationAllUsersRequest){
        //Should use a DTO in here also out of scope
        $messageBody = $sendNotificationAllUsersRequest->message_body;
        $metaData = $sendNotificationAllUsersRequest->meta_data ?? null;
        $country_code = $sendNotificationAllUsersRequest->country_code ;

        //should be added in the business layer
        $this->notificaionManager = new NotificationManager(
            $this->client,
            $messageBody,
            $metaData
        );

        //This should follow the three tier architecture but I guess this out of the scope of the task
        $devices = $this->device->where('country_code' ,$country_code)->get();
        $this->notificaionManager->notifyAllUsers($devices);

        $message = true ? "This message sent successfully" : "no messages sent";
        return response()->json([
            'message' => $message
        ]);
    }

    public function sendNotificationForAUser(SendNotificationForAUserRequest $sendNotificationAllUsersRequest){
        //Should use a DTO in here also out of scope
        $messageBody = $sendNotificationAllUsersRequest->message_body;
        $metaData = $sendNotificationAllUsersRequest->meta_data ?? null;
        $userId = $sendNotificationAllUsersRequest->user_id ;

        //should be added in the business layer
        $this->notificaionManager = new NotificationManager(
            $this->client,
            $messageBody,
            $metaData
        );

        //This should follow the three tier architecture but I guess this out of the scope of the task
        $devices = $this->device->where('user_id' ,$userId)->first();
        $this->notificaionManager->notifyUser($devices);

        $message = true ? "This message sent successfully" : "no messages sent";
        return response()->json([
            'message' => $message
        ]);
    }
}
