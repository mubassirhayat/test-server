<?php

namespace Meveto\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;

class GetServerKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meveto:getserverkey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get the server's public_key";

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
        $client = new Client();
        $url = 'http://meveto.local/api/v1/server-key';
        $request = $client->request('GET', $url);
        $body = json_decode($request->getBody());
        $this->info($body->data->server_public_key);
    }
}
