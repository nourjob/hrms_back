<?php

namespace App\Services;

use App\Models\User;

class NotificationService
{
    /**
     * إرسال إشعار إلى مجموعة من المستخدمين عبر Firebase
     */
    public static function sendToUsers($users, array $data)
    {
        foreach ($users as $user) {
            if ($user->device_token) {
                self::sendToFirebase($user->device_token, $data);
            }
        }
    }

    /**
     * إرسال إشعار إلى Firebase
     */
    protected static function sendToFirebase(string $deviceToken, array $data)
    {
        $payload = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $data['title'] ?? '',
                'body' => $data['body'] ?? '',
            ],
            'data' => $data,
        ];

        $headers = [
            'Authorization: key=' . config('services.firebase.server_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $result = curl_exec($ch);
        curl_close($ch);

        // يمكنك تسجيل النتيجة إذا أردت التحقق
    }
}
