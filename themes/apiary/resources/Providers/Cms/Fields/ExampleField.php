<?php

namespace Theme\Providers\Cms\Fields;

use Extended\ACF\Location;
use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;

class ExampleField extends ServiceProvider
{
    public function register()
    {
        Action::add('acf/init', [$this, 'registerFields']);
    }

    public function registerFields()
    {
        register_extended_field_group([
            'title' => 'About',
            'fields' => [
                \Extended\ACF\Fields\Text::make('Title', 'title'),
            ],
            'location' => [
                Location::where('block', 'theme/example'),
            ],
        ]);
    }
}
