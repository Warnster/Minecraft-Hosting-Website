<?php

namespace App\Http\Controllers\Admin;

use App\Http\Components\Hetzner;
use App\Http\Controllers\Controller;
use App\Http\Factory\CloudServerManagerFactory;
use Illuminate\Http\Request;

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
        return view('home', ['servers' => $servers]);
    }

    public function createServer() {
        $cloudServerManager = CloudServerManagerFactory::getManager('eu');
        $servers = $cloudServerManager->createServer();
    }
}
