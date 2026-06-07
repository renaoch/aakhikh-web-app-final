<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * POST /api/webhooks/ses
     * Handles AWS SES / SNS notifications:
     *  - SubscriptionConfirmation  → auto-confirm SNS topic
     *  - Notification (Bounce)     → mark subscriber as bounced
     *  - Notification (Complaint)  → unsubscribe complainant
     */
    public function ses(Request $request)
    {
        $messageType = $request->header('x-amz-sns-message-type');
        $body        = json_decode($request->getContent(), true);

        if (! $body) {
            return response()->json(['message' => 'Invalid payload.'], 400);
        }

        // Auto-confirm SNS subscription
        if ($messageType === 'SubscriptionConfirmation') {
            $subscribeUrl = $body['SubscribeURL'] ?? null;
            if ($subscribeUrl) {
                file_get_contents($subscribeUrl);
                Log::info('SNS subscription confirmed.');
            }
            return response()->json(['success' => true]);
        }

        if ($messageType !== 'Notification') {
            return response()->json(['success' => true]);
        }

        // Parse the inner SES notification
        $message = json_decode($body['Message'] ?? '{}', true);
        $notifType = $message['notificationType'] ?? $message['eventType'] ?? null;

        if ($notifType === 'Bounce') {
            $bounce = $message['bounce'] ?? [];
            $recipients = $bounce['bouncedRecipients'] ?? [];

            foreach ($recipients as $recipient) {
                $email = $recipient['emailAddress'] ?? null;
                if ($email) {
                    Subscriber::where('email', $email)->update([
                        'status'       => 'bounced',
                        'bounced_at'   => now(),
                    ]);
                    Log::info('Email bounced, subscriber deactivated.', ['email' => $email]);
                }
            }
        }

        if ($notifType === 'Complaint') {
            $complaint   = $message['complaint'] ?? [];
            $recipients  = $complaint['complainedRecipients'] ?? [];

            foreach ($recipients as $recipient) {
                $email = $recipient['emailAddress'] ?? null;
                if ($email) {
                    Subscriber::where('email', $email)->update([
                        'status'         => 'unsubscribed',
                        'unsubscribed_at'=> now(),
                    ]);
                    Log::info('Email complaint received, subscriber unsubscribed.', ['email' => $email]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/webhooks/razorpay
     * Proxied here from DonationController for route clarity.
     * Actual logic lives in DonationController@webhook.
     */
    public function razorpay(Request $request)
    {
        return app(DonationController::class)->webhook($request);
    }
}
