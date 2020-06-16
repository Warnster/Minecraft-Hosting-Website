<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CloudServer;

class CloudServerController extends Controller
{
    public function index() {
        $cloudServers = CloudServer::with('minecraftServers')->get();
        return view('cloud-servers', ['cloudServers' => $cloudServers]);
    }
}
