<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AiService
{
    private string $folderId;
    private string $apiKey;
    private string $modelUri;
    private string $baseUrl = 'https://llm.api.cloud.yandex.net/foundationModels/v1';

    public function __construct()
    {
        $this->folderId = config('services.yandex.folder_id');
        $this->apiKey = config('services.yandex.api_key');
        $this->modelUri = config('services.yandex.model_uri');
    }

    /**
     * Анализ тональности через YandexGPT
     */
    public function analyzeSentiment(string $text): string
    {
        try {
            $prompt = "Проанализируй тональность текста. Верни только одно слово: positive, negative или neutral. Текст: " . $text;

            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/completion', [
                'modelUri' => $this->modelUri,
                'completionOptions' => [
                    'temperature' => 0.3,
                    'maxTokens' => 20,
                ],
                'messages' => [
                    [
                        'role' => 'user',
                        'text' => $prompt,
                    ],
                ],
            ]);

            $result = $response->json();

            if (isset($result['result']['alternatives'][0]['message']['text'])) {
                $sentiment = strtolower(trim($result['result']['alternatives'][0]['message']['text']));
                return in_array($sentiment, ['positive', 'negative', 'neutral']) ? $sentiment : 'neutral';
            }

            return 'neutral';

        } catch (Exception $e) {
            Log::error('YandexGPT sentiment failed: ' . $e->getMessage());
            return 'neutral';
        }
    }

    /**
     * Генерация ответа через YandexGPT
     */
    public function generateResponse(string $text): string
    {
        try {
            $prompt = "Ты — вежливый менеджер поддержки. Сгенерируй профессиональный ответ на сообщение пользователя. Ответ должен быть кратким (2-3 предложения). Сообщение пользователя: " . $text;

            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/completion', [
                'modelUri' => $this->modelUri,
                'completionOptions' => [
                    'temperature' => 0.7,
                    'maxTokens' => 200,
                ],
                'messages' => [
                    [
                        'role' => 'user',
                        'text' => $prompt,
                    ],
                ],
            ]);

            $result = $response->json();

            if (isset($result['result']['alternatives'][0]['message']['text'])) {
                return $result['result']['alternatives'][0]['message']['text'];
            }

            return 'Спасибо за ваше обращение! Мы свяжемся с вами в ближайшее время.';

        } catch (Exception $e) {
            Log::error('YandexGPT response generation failed: ' . $e->getMessage());
            return 'Спасибо за ваше обращение! Мы свяжемся с вами в ближайшее время.';
        }
    }

    /**
     * Проверка доступности YandexGPT
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/completion', [
                'modelUri' => $this->modelUri,
                'completionOptions' => [
                    'temperature' => 0.1,
                    'maxTokens' => 5,
                ],
                'messages' => [
                    [
                        'role' => 'user',
                        'text' => 'Привет',
                    ],
                ],
            ]);

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }
}