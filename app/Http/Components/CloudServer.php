<?php


namespace App\Http\Components;


use App\Models\CloudServers;

class CloudServer
{
    public function __construct($id = null) {
        $this->cloudServer = CloudServers::find(1);
    }
}
