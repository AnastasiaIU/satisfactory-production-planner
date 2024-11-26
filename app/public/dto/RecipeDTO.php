<?php

class RecipeDTO
{
    public readonly string $id;
    public readonly string $produced_in;
    public readonly string $display_name;

    public function __construct(string $id, string $produced_in, string $display_name)
    {
        $this->id = $id;
        $this->produced_in = $produced_in;
        $this->display_name = $display_name;
    }
}