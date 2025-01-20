<?php

namespace Theme\Providers\Fields;

use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Message;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;
use Illuminate\Support\ServiceProvider;

class MenuFieldsProvider extends ServiceProvider
{
    public function register()
    {
        add_action('acf/init', function () {
            register_extended_field_group([
                'title' => '',
                'fields' => [
                    TrueFalse::make(__('Enable highlight?', 'apiary'), 'enable_highlight')
                        ->stylisedUi(),
                    Group::make('', 'menu_highlight')
                        ->fields([
                            Message::make('', 'highlight_label_1')
                                ->message('<div style="margin-top: 10px"><b>'.__('First highlight', 'apiary').'</b></div>'),
                            Image::make(__('Image', 'apiary'), '1_img')->returnFormat('id')
                                ->previewSize('thumbnail'),
                            Text::make(__('Title', 'apiary'), '1_title'),
                            Link::make(__('Link', 'apiary'), '1_link'),
                            Message::make('', 'highlight_label_2')
                                ->message('<div style="margin-top: 10px"><b>'.__('Second highlight', 'apiary').'</b></div>'),
                            Image::make(__('Image', 'apiary'), '2_img')->returnFormat('id')
                                ->previewSize('thumbnail'),
                            Text::make(__('Title', 'apiary'), '2_title'),
                            Link::make(__('Link', 'apiary'), '2_link'),
                        ])
                        ->conditionalLogic([
                            ConditionalLogic::where('enable_highlight', '==', '1'), // available operators are ==, !=, >, <, ==pattern, ==contains, ==empty, !=empty
                        ]),
                ],
                'layout' => 'default',
                'location' => [
                    Location::where('menu_level', '==', '0'),
                ],
            ]);
        });
    }
}
