<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $update = $request->all();
        $text   = trim((string) data_get($update, 'message.text', ''));
        $chatId = (string) data_get($update, 'message.chat.id');
        $firstName = (string) data_get($update, 'message.from.first_name', 'User');

        \Log::info('Telegram webhook received', ['text' => $text, 'chat_id' => $chatId, 'full_update' => $update]);

        if (!$chatId) return response()->json(['ok' => true]);

        if ($text === '/start') {
            $this->sendMessage($chatId,
                "សូមស្វាគមន៍ {$firstName}! ✅\nដើម្បីភ្ជាប់គណនី សូមវាយ:\n/bind <លេខទូរសព្ទ>\nឧទា: /bind 012345678"
            );
            return response()->json(['ok' => true]);
        }

        // /bind 012345678
        if (preg_match('/^\/bind\s+(\+?\d{7,20})$/', $text, $m)) {
            $phone = $m[1];
            \Log::info('Bind command detected', ['phone' => $phone]);

            $user = User::where('phone', $phone)->first();
            if (!$user) {
                \Log::warning('User not found for bind', ['phone' => $phone]);
                $this->sendMessage($chatId, "❌ លេខនេះមិនមានក្នុងប្រព័ន្ធទេ: {$phone}");
                return response()->json(['ok' => true]);
            }

            // prevent linking same chat_id to another account
            if (User::where('telegram_chat_id', $chatId)->where('id','!=',$user->id)->exists()) {
                $this->sendMessage($chatId, "⚠️ Telegram account នេះបានភ្ជាប់រួចហើយ។");
                return response()->json(['ok' => true]);
            }

            $user->telegram_chat_id = $chatId;
            $user->save();
            \Log::info('User telegram_chat_id updated', ['user_id' => $user->id, 'chat_id' => $chatId]);

            $this->sendMessage($chatId, "✅ ភ្ជាប់ជោគជ័យ! ឥឡូវអ្នកអាចទទួល OTP តាម Telegram បាន។");
            return response()->json(['ok' => true]);
        }

        \Log::info('Unknown command', ['text' => $text]);
        $this->sendMessage($chatId, "⁉️សូមបញ្ចូលជាលេខទូរសព្ទ\n🔔របៀបប្រើប្រាស់:\n/bind <លេខទូរសព្ទលោកអ្នក>\nឧទាហរណ៍: /bind 012345678");
        return response()->json(['ok' => true]);
    }

    private function sendMessage(string $chatId, string $text): void
    {
        $token = config('services.telegram.bot_token');
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
