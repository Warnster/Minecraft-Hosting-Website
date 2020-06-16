<?php


namespace App\Http\Components;


use App\Http\Interfaces\CloudServerManager;
use App\Models\CloudServer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Hetzner implements CloudServerManager
{
    /**
     * @var array|false|string
     */
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = getenv('HETZNER_API_TOKEN');
        $this->apiUrl = getenv('HETZNER_API_URL');
    }

    public function getAllServers() {
        $servers = $this->sendRequestToHetzner('get', 'servers');
        return $servers;
    }

    public function createServer($location = CloudServer::LOCATION_EU_GERMANY) :CloudServer {
        $locations = [
            CloudServer::LOCATION_EU_GERMANY => 'nbg1',
            CloudServer::LOCATION_EU_FINLAND => 'hel1'
        ];

        // cloud config
        //phone_home:
        // url: http://webhook.site/a073feaa-6c5d-4dfe-abcf-c1469e953f6e/3956248/$INSTANCE_ID/
        // post: [ pub_key_ecdsa, instance_id ]
        // tries: 10
        $webhookUrl = getenv('WEBHOOK_URL');
        $body = [
            'name' => uniqid(),
            'server_type' => 'cx21' , //cx51 //cx11
            "location" => $locations[$location],
            "start_after_create"=> true,
            "image"=> 11009869,
            "ssh_keys"=> [
                1177270,
                1187898
            ],
            "user_data" => "#cloud-config
phone_home:
 url: $webhookUrl
 post: all"
        ];
        $server = $this->sendRequestToHetzner('post', 'servers', $body);
        $attributes = [];
        $attributes['name'] = $server['server']['name'];
        $attributes['cloud_instance_id'] = $server['server']['id'];
        $attributes['user'] = 'root';
        $attributes['password'] = $server['root_password'];
        $attributes['provider'] = 'Hetzner';
        $attributes['ip_address'] = $server['server']['public_net']['ipv4']['ip'];
        $attributes['location'] = $location;
        $attributes['ram'] = $server['server']['server_type']['memory'];
        $attributes['available_ram'] = $server['server']['server_type']['memory'] - 1;
        $cloudServer = new CloudServer();
        $cloudServer->fill($attributes);
        $cloudServer->save();
        return $cloudServer;
    }

    private function sendRequestToHetzner($method, $endpoint, $params = []) {
        $method = strtolower($method);
        $client = new Client(['base_uri' => $this->apiUrl]);
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
        if($method == 'get') {
            $request = new Request($method, $endpoint, $headers);
            $response = $client->send($request, ['query' => $params]);
        } else if ($method == 'post' || $method == 'put') {
            $request = new Request($method, $endpoint, $headers);
            $response = $client->send($request, ['json' => $params, 'headers' => [
                'Content-Type' => 'application/json'
            ]]);
        }
        $body = $response->getBody();
        return json_decode($body, true);
    }

    public function deleteServer()
    {
        // TODO: Implement deleteServer() method.
    }
}
