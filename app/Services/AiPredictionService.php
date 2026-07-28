<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiPredictionService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.ai_service_url', env('AI_SERVICE_URL', 'http://localhost:5000'));
    }

    /**
     * يأخذ صف الموبايل من DB ويرسله لـ Flask ويرجع التنبؤ
     */
    public function predict(array $mobile): ?array
    {
        try {
            $payload = [
                'battery_power' => (float) $mobile['battery'],
                'blue'          => (int)   $mobile['bluetooth'],
                'clock_speed'   => (float) $mobile['clock_speed'],
                'dual_sim'      => (int)   $mobile['dual_sim'],
                'fc'            => (int)   $mobile['front_camera'],
                'four_g'        => (int)   $mobile['four_g'],
                'int_memory'    => (int)   $mobile['storage'],
                'm_dep'         => (float) $mobile['mobile_depth'],
                'mobile_wt'     => (int)   $mobile['mobile_wt'],
                'n_cores'       => (int)   $mobile['n_cores'],
                'pc'            => (int)   $mobile['camera'],
                'px_height'     => (int)   $mobile['px_height'],
                'px_width'      => (int)   $mobile['px_width'],
                'ram'           => (int)   $mobile['ram_mb'],
                'sc_h'          => (int)   $mobile['sc_h'],
                'sc_w'          => (int)   $mobile['sc_w'],
                'talk_time'     => (int)   $mobile['talk_time'],
                'three_g'       => (int)   $mobile['three_g'],
                'touch_screen'  => (int)   $mobile['touch_screen'],
                'wifi'          => (int)   $mobile['wifi'],
            ];

            $response = Http::timeout(5)
                ->post("{$this->baseUrl}/predict", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('AI Service error', ['status' => $response->status()]);
            return null;

        } catch (\Throwable $e) {
            Log::error('AI Service unreachable', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
