<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Home extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'page-home'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $post = get_post();

        $content = $post->post_content;

        $blocks = parse_blocks($content);

        $main_heading = current(array_filter($blocks, function($block) {
            return $block['blockName'] == 'core/heading';
        })) ?? [];

        return [
            'heading' => render_block($main_heading)
        ];
    }
}
