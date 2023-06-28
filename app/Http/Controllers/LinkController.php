<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkRequest;
use App\Models\Link;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    private $token;
    public function getShortLink(LinkRequest $request)
    {
        $result = [
            'token' => '',
            'message' => 'ok'
        ];
        $url = $request->input('url');
        if (!$this->exists($url)) {
            $this->token = Str::random(7);
            Link::create([
                'link' => $url,
                'token' => $this->token
            ]);
        }
        $result['token'] = $this->token;
        return response($result);
    }

    function redirectLink($token){
        $url = Link::where('token', '=', $token)->first();
        if ($url->link) return redirect()->to($url->link);
        abort(404);
    }

    private function exists(string $url)
    {
        $link = Link::where('link', '=', $url)->first();
        $this->token = $link->token ?? false;
        return $this->token;
    }
}
