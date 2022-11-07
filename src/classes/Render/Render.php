<?php
namespace iutnc\netvod\Render;
abstract class Render
{

    abstract public function render(int $selector):string;

}