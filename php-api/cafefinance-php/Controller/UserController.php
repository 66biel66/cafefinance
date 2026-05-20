<?php

class UserController
{
    public function __construct(private User $user)
    {
    }

    public function index(): array
    {
        return [
            'status' => 'success',
            'data' => $this->user->all(),
        ];
    }
}
