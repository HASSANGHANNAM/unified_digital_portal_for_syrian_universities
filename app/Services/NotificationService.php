<?php

namespace App\Services;

use App\Models\Notification as NotificationModel;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationService
{

    public function index()
    {
        return auth()->user()->notifications;
    }



    public function send($user, $title, $message, $type = 'basic')
    {
        if (empty($user->fcm_token)) {
            Log::warning("FCM token is missing for user ID: {$user->id}");
            return ['status' => false, 'message' => 'FCM token is missing'];
        }

        // Path to the service account key JSON file
        $serviceAccountPath = storage_path('app/firebase/echo_of_the_syrian_citizen_firebase_adminsdk_fbsvc_9e64271c2a.json');

        // Initialize the Firebase Factory with the service account
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);

        // Create the Messaging instance
        $messaging = $factory->createMessaging();

        // Prepare the notification array
        $notification = [
            'title' => $title,
            'body'  => $message,
            'sound' => 'default',
        ];

        // Additional data payload
        $data = [
            'type'    => $type,
            'id'      => $user->id,
            'message' => $message,
        ];

        // Create the CloudMessage instance
        $cloudMessage = CloudMessage::withTarget('token', $user->fcm_token)
            ->withNotification($notification)
            ->withData($data);

        try {
            $messaging->send($cloudMessage);


            Notification::create([
                'user_id' => $user->id,
                'message' => $message,
                'send_at' => now(),
            ]);

            return ['status' => true, 'message' => 'Notification sent'];
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            Log::error($e->getMessage());
            return ['status' => false, 'message' => 'Notification not sent'];
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            Log::error($e->getMessage());
            return ['status' => false, 'message' => 'Notification not sent'];
        }
    }


    public function markAsRead($notificationId): bool
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        if(isset($notification)) {
            $notification->markAsRead();
            return true;
        }else return false;
    }

    public function destroy($id): bool
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        if(isset($notification)) {
            $notification->delete();
            return true;
        }else return false;
    }

}
