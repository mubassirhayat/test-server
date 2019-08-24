<?php

namespace Meveto\Console\Commands;

use Illuminate\Console\Command;
use Validator;
use \GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Register extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "meveto:register";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register user using their public_key';

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
        $fullName = $this->ask('What is your full name?');
        $email = $this->ask('What is your email?');
        $username = $this->ask('Please choose a username');
        $publicKey = $this->ask('Please provide your public_key');
        $passphrase = $this->ask('What is the passphrase for your public_key (If there is no passphrase just press enter)');

        $data = [
            'name'                    => $fullName,
            'email'                   => $email,
            'username'                => $username,
            'public_key'              => $publicKey,
            'passphrase'              => $passphrase,
            'passphrase_confirmation' => $passphrase
        ];

        $validator = Validator::make($data, [
            'name'          => 'required',
            'email'         => 'bail|required|email',
            'username'      => 'bail|required',
            'public_key'    => 'bail|required',
            'passphrase'    => 'confirmed',
        ]);
        
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $field => $errors) {
                $this->error($field . ": " . implode(', ', $errors));
            }
        } else {
            $client = new Client();
            $url = 'http://meveto.local/api/v1/register';
            $request = $client->request('POST', $url, [
                'form_params' => $data
            ]);
            $body = json_decode($request->getBody());
            $this->info($body->message);
        }
    }
}
