<?php

namespace App\Http\Controllers\Support\Messages;

use App\Http\Controllers\Controller;
use App\Mail\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendingController extends Controller
{

    /**
     * Send a support message.
     *
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'message' => ['required'],
        ]);

        Mail::to(config('taskmanager.contact.primary_email'))->send(new SupportMessage($request->message));

        return response()->json([
            'success' => true
        ]);
    }
}
