<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Model::preventLazyLoading();

//        DB::listen(function($query) {
//            File::append(
//                storage_path('/logs/query.log'),
//                '[' . date('Y-m-d H:i:s') . ']' . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
//            );
//        });
//
        VarDumper::setHandler(function ($var) {
            $cloner = new VarCloner();
            $cloner->setMaxItems(-1); // Specifying -1 removes the limit
            $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

            $dumper->dump($cloner->cloneVar($var));
        });
    }
}
