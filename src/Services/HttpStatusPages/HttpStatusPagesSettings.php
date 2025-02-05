<?php
namespace OffbeatWP\Services\HttpStatusPages;

use OffbeatWP\Form\Fields\Post;
use OffbeatWP\Form\Form;

class HttpStatusPagesSettings
{
    const ID = 'http-status-pages';
    const PRIORITY = 90;

    public function title(): string
    {
        return __('Http status pages', 'offbeatwp');
    }

    public function form(): Form
    {
        $form = new Form();

        if (!($httpStatusPagesCodes = config('app.http_status_pages_codes'))) {
            $httpStatusPagesCodes = collect('404');
        }

        $httpStatusPagesCodes->each(function($statusCode) use ($form) {
            $form->addField(Post::make('http-status-page-' . $statusCode, 'Page for ' . $statusCode)->fromPostTypes(['page']));
        });

        return $form;
    }
}
