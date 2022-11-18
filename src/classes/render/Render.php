<?php
namespace iutnc\netvod\render;
abstract class Render
{

    abstract public function render(int $selector):string;

}