<?php

namespace Meveto\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use phpseclib\Crypt\RSA;
use Validator;

class SendSecretMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meveto:send-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Save a secret message on the server encrypted with server's public_key and signed with user's private_key";

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
        $plaintext = $this->ask('What secret do you want to store?');
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
        $ciphertext = $rsa->encrypt($plaintext);
        
        // $this->info($ciphertext);

        $rsa2 = new RSA();
        $clientPrivateKey = \Storage::disk('local')->get('keys/client_key');
        $rsa2->loadKey($clientPrivateKey); // private key

        $rsa2->setSignatureMode(RSA::SIGNATURE_PKCS1);
        $signature = $rsa2->sign($ciphertext);

        $rsa3 = new RSA();
        $clientPublicKey = \Storage::disk('local')->get('keys/client_key.pub');
        $rsa3->loadKey($clientPublicKey); // public key


        $data = [
            'username' => $username,
            'message'     => $signature,
        ];

        $validator = Validator::make($data, [
            'username' => 'required',
            'message'  => 'required',
        ]);
        
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $field => $errors) {
                $this->error($field . ": " . implode(', ', $errors));
            }
        } else {
            if ($rsa3->verify($ciphertext, $signature)) {
                $this->info('verification passed');
                $client = new Client();
                $url = 'http://meveto.local/api/v1/secret';
                $request = $client->request('POST', $url, [
                    'form_params' => $data
                ]);
                $body = json_decode($request->getBody());
                $this->info($body->message);
            } else {
                $this->error('verification failed');
            }
        }
    }
}
