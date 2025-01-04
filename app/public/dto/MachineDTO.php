<?php

/**
 * Data Transfer Object (DTO) for representing a machine.
 */
class MachineDTO
{
    public readonly string $id;
    public readonly string $display_name;
    public readonly string $icon_name;

    public function __construct(string $id, string $display_name, string $icon_name)
    {
        $this->id = $id;
        $this->display_name = $display_name;
        $this->icon_name = $icon_name;
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
            'icon_name' => $this->icon_name
        ];
    }
}