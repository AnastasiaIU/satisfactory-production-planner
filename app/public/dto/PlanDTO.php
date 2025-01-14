<?php

/**
 * Data Transfer Object (DTO) for representing a production plan.
 */
class PlanDTO
{
    public readonly string $id;
    public readonly string $created_by;
    public readonly string $display_name;
    public readonly array $items;

    public function __construct(string $id, string $created_by, string $display_name, array $items)
    {
        $this->id = $id;
        $this->created_by = $created_by;
        $this->display_name = $display_name;
        $this->items = $items;
    }
}