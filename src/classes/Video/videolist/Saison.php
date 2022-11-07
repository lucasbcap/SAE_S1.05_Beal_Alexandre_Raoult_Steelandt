<?php

namespace iutnc\netvod\Video\videolist;

use iutnc\netvod\Video\videotrack\Video;

class Saison
{
    protected array $episode;
    protected int $numsaison;

    /**
     * @param array $episode
     * @param int $numsaison
     */
    public function __construct(array $episode=[], int $numsaison)
    {
        $this->episode = $episode;
        $this->numsaison = $numsaison;
    }

    public function ajouterEpisode(Video $d):void{
        array_push($this->episode,$d);
    }


}