<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\CloudServer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ServerManagerController extends Controller
{
    public function cloudServerInitialised(Request $request, Response $response, $apiKey) {
        //Check the request is valid
        $webhookKey = getenv('WEBHOOK_URL_KEY');
        if($apiKey !== $webhookKey) {
            return $response->setStatusCode('401');
        }
        $input = $request->input();
        //Validate the post parameters
        Validator::make($request->all(), [
            'instance_id' => 'required|string'
        ])->validate();
        //update status to running.
        $cloudServer = CloudServer::where('cloud_instance_id', '=', $input['instance_id'])->first();
        $cloudServer->status = CloudServer::STATUS_RUNNING;
        $cloudServer->save();
        $yes = '';
        //Check any minecraft servers queued for deployment on this instance

        return $response;
    }
}
