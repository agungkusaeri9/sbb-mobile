<?php


namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function login(array $data);
    public function updateProfile($data);
    public function getProfile();
    public function delete_array($arr);
}
