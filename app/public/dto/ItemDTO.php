<?php

class ItemDTO
{
    public readonly string $id;
    public readonly string $display_name;
    public readonly string $icon_name;
    public readonly string $type;

    public function __construct(string $id, string $display_name, string $icon_name, string $type)
    {
        $this->id = $id;
        $this->display_name = $display_name;
        $this->icon_name = $icon_name;
        $this->type = $type;
    }
}