<?php

class RecipeDTO
{
    public readonly string $id;
    public readonly string $produced_in;
    public readonly string $display_name;
    public readonly bool $is_alternative;
    public readonly array $output;
    public readonly array $input;

    public function __construct(string $id, string $produced_in, string $display_name, bool $is_alternative, array $output, array $input)
    {
        $this->id = $id;
        $this->produced_in = $produced_in;
        $this->display_name = $display_name;
        $this->is_alternative = $is_alternative;
        $this->output = $output;
        $this->input = $input;
    }
}