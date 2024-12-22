<?php

class ItemDTO
{
    public readonly string $id;
    public readonly string $display_name;
    public readonly string $icon_name;
    public readonly string $category;
    public readonly int $display_order;

    public function __construct(string $id, string $display_name, string $icon_name, string $category, int $display_order)
    {
        $this->id = $id;
        $this->display_name = $display_name;
        $this->icon_name = $icon_name;
        $this->category = $category;
        $this->display_order = $display_order;
    }
}