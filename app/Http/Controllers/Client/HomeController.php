<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Factory\CloudServerManagerFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cloudServerManager = CloudServerManagerFactory::getManager('eu');
        $servers = $cloudServerManager->getAllServers();
        $user = Auth::user();
        $guid = $user->guid;
        return view('home', ['servers' => $servers, 'guid' => $guid]);
    }

    public function createServer() {
        $cloudServerManager = CloudServerManagerFactory::getManager('eu');
        $servers = $cloudServerManager->createServer();
    }
}
