<?php

namespace App\Observers;

use App\Http\Components\MinecraftServer as MinecraftServerComponent;
use App\Models\CloudServers;
use App\Models\MinecraftServer;
use Illuminate\Support\Facades\Log;

class MinecraftServerObserver
{
    /**
     * Handle the minecraft servers "created" event.
     *
     * @param  \App\MinecraftServer  $MinecraftServer
     * @return void
     */
    public function created(MinecraftServer $MinecraftServer)
    {
        //
    }

    /**
     * Handle the minecraft servers "updated" event.
     *
     * @param  \App\MinecraftServer  $MinecraftServer
     * @return void
     */
    public function updated(MinecraftServer $MinecraftServer)
    {
        //
    }

    /**
     * Handle the minecraft servers "deleted" event.
     *
     * @param  \App\MinecraftServer  $MinecraftServer
     * @return void
     */
    public function deleted(MinecraftServer $MinecraftServer)
    {
        //
    }

    public function saving(MinecraftServer $MinecraftServer)
    {
        $originalStatus = $MinecraftServer->getOriginal('status');
        $newStatus = $MinecraftServer->status;
        //Check status has switched to pending
        if($originalStatus == MinecraftServer::STATUS_CLOUD_PENDING &&
            $newStatus == MinecraftServer::STATUS_PENDING) {
            //create the minecraft server
            $cloudServer = CloudServers::find($MinecraftServer->cloud_server_id);
            $minecraftServerComponent = new MinecraftServerComponent($cloudServer->ip_address, $MinecraftServer->ram, $cloudServer->user);
            $result = $minecraftServerComponent->create();
            Log::info('minecraft server running');
            //Send websocket event to client
        }
    }
    public function saved(MinecraftServer $MinecraftServer)
    {
    }


    /**
     * Handle the minecraft servers "restored" event.
     *
     * @param  \App\MinecraftServer  $MinecraftServer
     * @return void
     */
    public function restored(MinecraftServer $MinecraftServer)
    {
        //
    }

    /**
     * Handle the minecraft servers "force deleted" event.
     *
     * @param  \App\MinecraftServer  $MinecraftServer
     * @return void
     */
    public function forceDeleted(MinecraftServer $MinecraftServer)
    {
        //
    }
}
