<?php

namespace App\Http\Interfaces;

use App\Models\CloudServer;

interface CloudServerManager {
    public function createServer() : CloudServer;
    public function deleteServer();
    public function getAllServers();
}
