<?php

namespace iutnc\netvod\render;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\video\episode;
use iutnc\netvod\video\Serie;

class CatalogueRender extends Render
{
    protected Serie $serie;

    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }

    public function render(int $selector = 1): string
    {
            $res = "<a href='?action=display-serie&id=" . $this->serie->id . "'>";
            $res .= "<h4><center>" . $this->serie->titre . "</h4>";
            $res .= "<center><a href='?action=display-serie&id=" . $this->serie->id . "' id='lien'><div class=zoom>
                    <div class=image>
                    <img src='Image/" . $this->serie->img . "' width='600' height='380'></a></center><br>
                    </div>
                    </div>";
        return $res;
    }
}