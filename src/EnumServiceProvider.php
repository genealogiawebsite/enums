<?php

namespace LaravelEnso\Enums;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Enums\App\Facades\Enums;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [];

    public function boot()
    {
        Enums::register($this->register);
    }
}
