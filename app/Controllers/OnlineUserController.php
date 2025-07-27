<?php

class OnlineUserController
{
    private $model;

    public function __construct()
    {
        $this->model = new OnlineUserModel();
    }

    public function track()
    {
        $sessionId = $_SESSION['user']['id'];
        $this->model->updateActivity($sessionId);
        $this->model->removeExpiredUsers(); // Xoá user cũ
        return $this->model->countOnlineUsers();
    }
}
