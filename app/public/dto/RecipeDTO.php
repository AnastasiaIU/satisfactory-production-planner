<?php

/**
 * Data Transfer Object (DTO) for representing a recipe.
 */
class RecipeDTO
{
    public readonly string $id;
    public readonly string $produced_in;
    public readonly string $display_name;
    public readonly array $output;
    public readonly array $input;

    public function __construct(string $id, string $produced_in, string $display_name, array $output, array $input)
    {
        $this->id = $id;
        $this->produced_in = $produced_in;
        $this->display_name = $display_name;
        $this->output = $output;
        $this->input = $input;
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
            'produced_in' => $this->produced_in,
            'display_name' => $this->display_name,
            'output' => array_map(fn($dto) => $dto->toArray(), $this->output),
            'input' => array_map(fn($dto) => $dto->toArray(), $this->input)
        ];
    }
}

class RecipeOutputDTO
{
    public readonly string $recipe_id;
    public readonly string $item_id;
    public readonly int $amount;
    public readonly bool $is_standard_recipe;

    public function __construct(string $recipe_id, string $item_id, int $amount, bool $is_standard_recipe)
    {
        $this->recipe_id = $recipe_id;
        $this->item_id = $item_id;
        $this->amount = $amount;
        $this->is_standard_recipe = $is_standard_recipe;
    }

    /**
     * Converts the object to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'recipe_id' => $this->recipe_id,
            'item_id' => $this->item_id,
            'amount' => $this->amount,
            'is_standard_recipe' => $this->is_standard_recipe
        ];
    }
}

class RecipeInputDTO
{
    public readonly string $recipe_id;
    public readonly string $item_id;
    public readonly int $amount;

    public function __construct(string $recipe_id, string $item_id, int $amount)
    {
        $this->recipe_id = $recipe_id;
        $this->item_id = $item_id;
        $this->amount = $amount;
    }

    /**
     * Converts the object to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'recipe_id' => $this->recipe_id,
            'item_id' => $this->item_id,
            'amount' => $this->amount
        ];
    }
}