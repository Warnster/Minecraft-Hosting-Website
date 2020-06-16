<?php


namespace App\Http\Controllers\Client;


use App\Http\Components\MinecraftServer as MinecraftServerComponent;
use App\Http\Controllers\Controller;
use App\Http\Factory\CloudServerManagerFactory;
use App\Models\CloudServer;
use App\Models\MinecraftServer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServerManagerController extends Controller
{
    public function createMinecraftServer(Request $request, Response $response) {
        $this->validate($request, [
            'location' => 'required',
            'ram' => 'integer'
        ]);
        $input = $request->input();
        //Gets cloud servers in region
        $cloudServers = CloudServer::where([['location', '=', $input['location']]])->get();
        //If no cloud servers then create one
        $cloudServer = null;
        $mcStatus = MinecraftServer::STATUS_CLOUD_PENDING;
        foreach($cloudServers as $cServer) {
            //If there should be enough ram on the server
            if($cServer->available_ram >= $input['ram']) {
                $minecraftServerComponent = new MinecraftServerComponent($cServer->ip_address, $input['ram']);
                //If there is actually enough ram on the server then server has been found
                if($minecraftServerComponent->checkRam()) {
                    $cloudServer = $cServer;
                    $mcStatus = MinecraftServer::STATUS_PENDING;
                    break;
                }
            }
        }

        //If there are no servers available then create a new one
        if($cloudServer == null) {
            $cloudServerManager = CloudServerManagerFactory::getManager(CloudServer::LOCATION_EU_GERMANY);
            $cloudServer = $cloudServerManager->createServer();
        }
        $minecraftServer = new MinecraftServer();
        $minecraftServer->ram = $input['ram'];
        $minecraftServer->cloud_server_id = $cloudServer->id;
        $minecraftServer->user_id = $request->user()->id;
        $minecraftServer->status = $mcStatus;
        $minecraftServer->port = 25565;
        $minecraftServer->save();
        //Once there is a cloud server with available ram, create the minecraft server
        //$minecraftServerComponent = new MinecraftServer();
        //$minecraftServer = $minecraftServerComponent->create($cloudServer->ip_address, $input['ram']);


    }
}
