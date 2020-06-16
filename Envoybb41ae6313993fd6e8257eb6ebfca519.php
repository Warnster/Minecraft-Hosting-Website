<?php $account = isset($account) ? $account : null; ?>
<?php $deploy_servers = isset($deploy_servers) ? $deploy_servers : null; ?>
<?php $servers = isset($servers) ? $servers : null; ?>
<?php
    $servers = Servers::all(); // or whatever servers you want to query
    $deploy_servers = [
        'web' => '127.0.0.1'
    ];

?>
<?php $__container->servers($deploy_servers); ?>
<?php
$account = 'root';
?>
<?php $__container->startTask('deploy', ['on' => 'web']); ?>
ls -l
<?php $__container->endTask(); ?>
