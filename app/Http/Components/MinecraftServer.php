<?php


namespace App\Http\Components;


use GuzzleHttp\Client;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MinecraftServer
{
    public function __construct($ipAddress = null, $mcRam = null, $sshUser = null)
    {
        $this->ipAddress = $ipAddress;
        $this->mcRam = $mcRam;
        $this->sshUser = $sshUser;
    }

    public function create() : bool {
        //If the server has enough ram then deploy the minecraft server
        if($this->checkRam()) {
            $result = $this->deployMinecraftServer();
            return $result;
        }
        return false;
      }

      public function checkRam() {
          $cloudRam = $this->getCloudRam($this->ipAddress);
          // +200 for overhead
          $mcMbRam = $this->mcRam * 1024 + 200;
          return $cloudRam > $mcMbRam;
      }

      private function deployMinecraftServer() {
          $command = "envoy run deploy --sshuser=$this->sshUser --ip=$this->ipAddress --ram=$this->mcRam";
          $process = $this->getSymphonyProcess($command);
          $process->run();

          // executes after the command finishes
          if (!$process->isSuccessful()) {
              throw new ProcessFailedException($process);
          }
          return true;
      }

      public function getCloudRam($ipAddress) {
          $getRamCommand = "envoy run get-ram --sshuser=$this->sshUser --ip=$ipAddress";
          // $getRamCommand = 'env';
          $process = $this->getSymphonyProcess($getRamCommand);
          $process->run();

          // executes after the command finishes
          if (!$process->isSuccessful()) {
              throw new ProcessFailedException($process);
          }
///usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
          $output = $process->getOutput();
          $ram = $this->get_string_between($output, 'CURRENT_RAM_START ', ' CURRENT_RAM_END');
          return intval($ram);
      }

      private function getSymphonyProcess($command) {
        return new Process($command,'/var/www/html', [
            'HOME' => '/home/devuser'
        ]);
      }

      private function get_string_between($string, $start, $end){
          $string = ' ' . $string;
          $ini = strpos($string, $start);
          if ($ini == 0) return '';
          $ini += strlen($start);
          $len = strpos($string, $end, $ini) - $ini;
          return substr($string, $ini, $len);
      }

}
