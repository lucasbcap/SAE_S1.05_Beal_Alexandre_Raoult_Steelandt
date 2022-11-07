<?php

namespace iutnc\netvod\Render;

use iutnc\netvod\Video\episode;

class EpisodeRender extends Render
{
    protected episode $ep;

    public function __construct(episode $ep){
        $this->ep = $ep;
    }

    public function render(): string
    {
        return "<div class='track'>".
            "<h1>{$this->ep->titre}</h1>".
            "<h2>{$this->ep->resume}</h2>".
            "<p><video controls src='{$this->ep->source}'></video></p>".
            "</div>";
    }
}