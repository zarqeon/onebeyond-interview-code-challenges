<?php

class AuthorService {

	public function __construct(private MockStorage $storage)
	{}


	public function getAuthorNameByAuthorId(int $authorId): string
	{
		// i don't believe exception handling is in the scope of this excercise
		$author = $this->getAuthorById($authorId);

		return $author->name;
	}

	public function getAuthorById(int $authorId): Author
	{
		foreach ($this->storage->authors as $author) {
			if ($author->id == $authorId) {
				return $author;
			}
		}

		throw new \Exception('author not found: '.$authorId);
	}

}
