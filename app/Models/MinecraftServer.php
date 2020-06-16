<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MinecraftServer extends Model
{
    protected $table = 'minecraft_servers';
    //Pending on cloud server to be ready
    const STATUS_CLOUD_PENDING = 0;
    //pending on the mc server to be ready
    const STATUS_PENDING = 1;
    //server is running
    const STATUS_RUNNING = 2;

}
