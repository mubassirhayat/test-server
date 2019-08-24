<?php

namespace Meveto\Console\Commands;

use Illuminate\Console\Command;

class GetSecretMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meveto:get-message {message_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get encrypted message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->ask('Please enter your username.');
        $passphrase = $this->ask('Enter passphrase for your key.');

        $client = new Client();
        $url = 'http://meveto.local/api/v1/server-key';
        $request = $client->request('GET', $url);
        $body = json_decode($request->getBody());
        $serverPublicKey = $body->data->server_public_key;
        $rsa = new RSA();
        if ($passphrase) {
            $rsa->setPassword($passphrase);
        }
        $rsa->loadKey($serverPublicKey);

        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        
    }
}
