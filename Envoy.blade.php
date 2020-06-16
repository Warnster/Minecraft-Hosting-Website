@php
    $user = isset($user) ? $user : 'mike';
    $ram = isset($ram) ? $ram : 1;
    // or whatever servers you want to query
    $sshuser = isset($sshuser) ? $sshuser : null;
    $ip = isset($ip) ? $ip : '127.0.0.1';
    $sshAddress = $sshuser . '@' . $ip;
    $deploy_servers = [
        'this' => '127.0.0.1',
        'web' => $sshAddress
    ];
@endphp
@servers($deploy_servers)
@setup
$account = 'root';
@endsetup

@macro('deploy')
deploy-mc-server
@endmacro

@macro('delete-server')
delete-mc-server
@endmacro

@macro('get-ram')
current-ram
@endmacro

@task('authenticate-public-key',['on' => 'this'])
touch ~/.ssh/known_hosts
cp ~/.ssh/known_hosts ~/.ssh/known_hosts.tmp
rm ~/.ssh/known_hosts
sed "/{{$ip}}.*/d" ~/.ssh/known_hosts.tmp >> ~/.ssh/known_hosts
rm ~/.ssh/known_hosts.tmp
ssh-keyscan -t ecdsa {{$ip}} >> ~/.ssh/known_hosts
@endtask

@task('deploy-mc-server', ['on' => 'web'])
echo "creating a new user for {{$user}}"
echo "secret12" "secret12" | adduser {{$user}} || true
docker rm -f mc-{{$user}} || true
docker run -e EULA=TRUE -d -it -p 25565:25565 -v /home/{{$user}}:/data --name mc-{{$user}} itzg/minecraft-server
@endtask

@task('current-ram', ['on' => 'web'])
ram=$(awk '/^Mem/ {print $7}' <(free -m))
echo "CURRENT_RAM_START $ram CURRENT_RAM_END"
@endtask

@task('delete-mc-server', ['on' => 'web'])
@endtask
