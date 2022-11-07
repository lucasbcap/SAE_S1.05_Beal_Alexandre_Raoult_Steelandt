<?php

namespace iutnc\netvod\Render;

use iutnc\netvod\vidéo\Video;

class VideoRender extends Render
{
    protected Video $vid;

    public function __constructor(Video $vid){
        $this->vid = $vid;
    }

    public function render(): string
    {
        return "<div class='track'>".
            "<h1>{$this->vid->titre}</h1>".
            "<h2>{$this->vid->resume}</h2>".
            "<p><video controls src='{$this->vid->source}'></video></p>".
            "</div>";
    }
}