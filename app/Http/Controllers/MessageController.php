<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->status = 'read';
        $message->save();

        return response()->json(['status' => 'success']);
    }

    public function alertAsRead($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->is_read = true;
        $alert->save();

        return response()->json(['status' => 'success']);
    }
}
