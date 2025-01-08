<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TrackLink;
use Illuminate\Http\Request;

class TrackLinkController extends Controller
{
    public function track(Request $request)
    {
        $url = $request->query('url');

        if (! $url) {
            abort(404);
        }

        $url = urldecode($url);

        TrackLink::create([
            'user_id' => auth()->id() ?? null,
            'url' => $url,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'referer' => $request->header('referer'),
        ]);

        return redirect()->away($url);
    }
}
