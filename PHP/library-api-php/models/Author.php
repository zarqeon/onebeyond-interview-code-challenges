<?php

class Author {
	public const NAME = 'name';

    public $id;
    public $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
}
