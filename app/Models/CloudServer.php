<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CloudServer extends Model
{
    protected $guarded = [];

    const LOCATION_EU_GERMANY = 1;
    const LOCATION_EU_FINLAND = 2;
    const STATUS_INITIALISING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_DESTROYED = 2;

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = encrypt($value);
    }

    public function getPasswordAttribute($value) {
        return decrypt($value);
    }

    public static function create($attributes) {
        $cloudServer = new CloudServer();
        $cloudServer->name = $attributes['name'];
        $cloudServer->cloud_instance_id = $attributes['cloud_instance_id'];
        $cloudServer->user = $attributes['user'];
        $cloudServer->password = isset($attributes['password']) ? $attributes['password'] : '';
        $cloudServer->provider = $attributes['provider'];
        $cloudServer->ip_address = $attributes['ip_address'];
        $cloudServer->location = $attributes['location'];
        $cloudServer->ram = $attributes['ram'];
        $cloudServer->available_ram = $attributes['available_ram'];
        $cloudServer->status = CloudServer::STATUS_INITIALISING;
        try {
            return $cloudServer->saveOrFail();
        } catch (\Throwable $e) {
            //todo: Send Email Notification of failure
            $msg = $e->getMessage();
        }
    }

    public function getSshAddressAttribute() {
        return $this->user . '@' . $this->ip_address;
    }

    public function minecraftServers() {
        return $this->hasMany('App\Models\MinecraftServer');
    }

}
