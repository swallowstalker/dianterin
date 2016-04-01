<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 4/2/16
 * Time: 3:43 AM
 */

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Dropbox\Client as DropboxClient;
use League\Flysystem\Dropbox\DropboxAdapter;
use Illuminate\Support\ServiceProvider;

class DropboxFilesystemServiceProvider extends ServiceProvider {

    public function boot()
    {
        Storage::extend('dropbox', function($app, $config)
        {
            $client = new DropboxClient(
                $config["access_token"], $config["client_identifier"]);

            return new Filesystem(new DropboxAdapter($client));
        });
    }

    public function register()
    {
        //
    }

}