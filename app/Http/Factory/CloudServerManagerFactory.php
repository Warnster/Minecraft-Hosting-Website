<?php


namespace App\Http\Factory;


use App\Http\Components\Hetzner;
use App\Http\Interfaces\CloudServerManager;
use App\Models\CloudServer;

class CloudServerManagerFactory
{
    public static function getManager($location) : CloudServerManager {
        if($location == CloudServer::LOCATION_EU_GERMANY) {
            return new Hetzner();
        }
        if($location == 'us') {
            return new Hetzner();
        }
        return new Hetzner();
    }
}
