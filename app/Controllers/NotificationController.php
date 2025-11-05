<?php

namespace App\Controllers;

use App\Models\UserDeviceModel;
use Firebase\JWT\JWT;
use CodeIgniter\API\ResponseTrait;

class NotificationController extends BaseController
{
    use ResponseTrait;
    public function __construct()
    {
        helper(['form', 'url']);
        $this->request = \Config\Services::request();
    }
    private function getAccessToken()
    {
        $json = file_get_contents(APPPATH . 'Config/service-account-firebase.json');
        $data = json_decode($json, true);

        $now = time();
        $payload = [
            "iss" => $data['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => $data['token_uri'],
            "iat" => $now,
            "exp" => $now + 3600
        ];

        $jwt = JWT::encode($payload, $data['private_key'], 'RS256');

        // Request token ke Google OAuth
        $ch = curl_init($data['token_uri']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($response, true);
        return $res['access_token'] ?? null;
    }
    public function sendToUser()
    {
    $request = $this->request;
    /** @var \CodeIgniter\HTTP\IncomingRequest $request */
    $input = $request->getJSON(true); // ambil JSON dari request
    $user_id = $input['user_id'] ?? null;
    if (!$user_id) {
        return $this->fail('user_id required');
    }
    $model = new UserDeviceModel();
    $device = $model->where('user_id', $user_id)->first();
    if (!$device) {
        return $this->failNotFound('User token not found');
    }

    $fcmToken = $device['token'];

    $payload = [
        "message" => [
            "token" => $fcmToken,
            "notification" => [
                "title" => "Test Notification",
                "body"  => "Halo! Notifikasi berhasil."
            ],
            "data" => [
                "foo" => "bar",   // contoh custom data
                "user_id" => $user_id
            ]
        ]
    ];


    // Ambil access token dari service account
    $accessToken = $this->getAccessToken();
    $fcmUrl = env('FCM_URL', 'https://fcm.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/messages:send');

    $ch = curl_init($fcmUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $result = curl_exec($ch);
    $error  = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return $this->failServerError($error);
    }

    return $this->respond([
        'status' => 'success',
        'payload_sent' => $payload,
        'fcm_result' => json_decode($result, true)
    ]);
}



    public function index()
    {
        return view('dashboard/notification/index', [
            'fcmConfig' => [
                'apiKey' => getenv('FIREBASE_API_KEY'),
                'authDomain' => getenv('FIREBASE_AUTH_DOMAIN'),
                'projectId' => getenv('FIREBASE_PROJECT_ID'),
                'storageBucket' => getenv('FIREBASE_STORAGE_BUCKET'),
                'messagingSenderId' => getenv('FIREBASE_MESSAGING_SENDER_ID'),
                'appId' => getenv('FIREBASE_APP_ID'),
                'measurementId' => getenv('FIREBASE_MEASUREMENT_ID'),
            ]
        ]);
    }
    public function saveToken()
    {
        $request = $this->request;
        /** @var \CodeIgniter\HTTP\IncomingRequest $request */
        $data = $request->getJSON(true);
        $userId = session('user_id');
        if (!$userId) {
            return $this->failUnauthorized('You must login first');
        }

        if (!isset($data['token'])) {
            return $this->fail('Token required');
        }

        $deviceType = $request->getUserAgent()->isMobile() ? 'android' : 'web';

        $model = new UserDeviceModel();

        // Cek apakah token sudah ada
        $existing = $model->where('user_id', $userId)
                        ->where('token', $data['token'])
                        ->first();

        if (!$existing) {
            $model->insert([
                'user_id'     => $userId,
                'device_type' => $deviceType,
                'token'       => $data['token'],
                'user_agent'  => $request->getUserAgent()->getAgentString(),
                'last_active' => date('Y-m-d H:i:s'),
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        } else {
            // update last active
            $model->update($existing['id'], [
                'last_active' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->respond(['message' => 'Token saved'], 200);
    }
}
