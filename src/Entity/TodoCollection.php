<?php
namespace App\Entity;

use PhF\Collection\Collection;

class TodoCollection extends Collection {
    protected static $invalidElementMessageAllowed = Todo::class;

    public function validate(
        $value,
    ): bool {
        return $value instanceof Todo;
    }
}
