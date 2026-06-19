<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    public function redirect(string $code): RedirectResponse
    {
        $link = ShortLink::where('short_code', $code)->firstOrFail();

        $link->increment('click_count');

        return redirect($link->original_url, 302);
    }
}
