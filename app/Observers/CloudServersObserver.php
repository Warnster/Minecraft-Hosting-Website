<?php

namespace App\Observers;

use App\Models\CloudServer;
use App\Models\MinecraftServer;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CloudServersObserver
{
    /**
     * Handle the cloud servers "created" event.
     *
     * @param  \App\CloudServers  $cloudServers
     * @return void
     */
    public function created(CloudServer $cloudServers)
    {
        //
    }

    /**
     * Handle the cloud servers "updated" event.
     *
     * @param  \App\CloudServers  $cloudServers
     * @return void
     */
    public function updated(CloudServer $cloudServers)
    {
        //
    }

    /**
     * Handle the cloud servers "deleted" event.
     *
     * @param  \App\CloudServers  $cloudServers
     * @return void
     */
    public function deleted(CloudServer $cloudServers)
    {
        //
    }

    /**
     * Handle the cloud servers "restored" event.
     *
     * @param  \App\CloudServers  $cloudServers
     * @return void
     */
    public function restored(CloudServer $cloudServers)
    {
        //
    }

    /**
     * Handle the cloud servers "force deleted" event.
     *
     * @param  \App\CloudServers  $cloudServers
     * @return void
     */
    public function forceDeleted(CloudServer $cloudServers)
    {
        //
    }

    public function saving(CloudServer $cloudServers) {
        $originalStatus = $cloudServers->getOriginal('status');
        $newStatus = $cloudServers->status;
        //Check status has switched from initalising to running
        if($originalStatus == CloudServer::STATUS_INITIALISING &&
            $newStatus == CloudServer::STATUS_RUNNING) {

            //Save the ecdsa key into the known hosts file.
            $command = "envoy run authenticate-public-key --ip=$cloudServers->ip_address";
            $process = new Process($command, '/var/www/html', [
                'HOME' => '/home/devuser'
            ]);
            $process->run();
            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            //todo deploy any pending mc servers
            $minecraftServersToDeploy = MinecraftServer::where([
                ['status', '=', MinecraftServer::STATUS_CLOUD_PENDING],
                ['cloud_server_id', '=', $cloudServers->id]
            ])->get();
            foreach ($minecraftServersToDeploy as $mcs) {
                //update each status which will trigger the servers to be deployed
                $mcs->status = MinecraftServer::STATUS_PENDING;
                $mcs->save();
            }
        }
    }
}
