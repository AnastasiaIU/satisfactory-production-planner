<?php

/**
 * Data Transfer Object (DTO) for representing an item.
 */
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

    /**
     * Converts the object to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'icon_name' => $this->icon_name,
            'category' => $this->category,
            'display_order' => $this->display_order
        ];
    }
}