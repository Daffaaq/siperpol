<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Message;
use Illuminate\Http\Request;

class AlertMessageController extends Controller
{
    public function fetchAlerts(Request $request)
    {
        $alertsAll = Alert::paginate(5);  // Ambil 10 data per halaman
        return response()->json([
            'alerts' => view('partials.alerts', compact('alertsAll'))->render(),  // Render view alerts
            'pagination' => (string) $alertsAll->links()  // Pagination links
        ]);
    }

    // app/Http/Controllers/MessageController.php

    public function fetchMessages(Request $request)
    {
        $messagesAll = Message::paginate(10);  // Ambil 10 data per halaman
        return response()->json([
            'messages' => view('partials.messages', compact('messagesAll'))->render(),  // Render view messages
            'pagination' => (string) $messagesAll->links()  // Pagination links
        ]);
    }
}
