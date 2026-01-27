<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log notifikasi masuk untuk debugging
        Log::info('Midtrans Webhook Received', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Verifikasi Keamanan
        if ($hashed !== $request->signature_key) {
            Log::warning('Invalid Webhook Signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Pecah order_id CAFE-{id}-{time}
        $orderIdParts = explode('-', $request->order_id);
        if (count($orderIdParts) < 2) {
            return response()->json(['message' => 'Invalid Order ID Format'], 400);
        }

        $transactionId = $orderIdParts[1];
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            Log::error('Transaction not found: ' . $transactionId);
            return response()->json(['message' => 'Not found'], 404);
        }

        $status = $request->transaction_status;

        // Update status berdasarkan laporan Midtrans
        if ($status == 'settlement' || $status == 'capture') {
            $transaction->update(['status' => 'success']);
            Log::info('Transaction Success: ' . $transactionId);
        } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
            $transaction->update(['status' => 'failed']);
            Log::info('Transaction Failed/Canceled: ' . $transactionId);
        }

        return response()->json(['message' => 'Webhook Processed Successfully']);
    }
}
