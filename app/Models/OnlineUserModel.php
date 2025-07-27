<?php

class OnlineUserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function updateActivity($sessionId)
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("REPLACE INTO online_users (session_id, last_activity) VALUES (?, ?)");
        $stmt->execute([$sessionId, $now]);
    }

    public function removeExpiredUsers($seconds = 300)
    {
        $timeout = date('Y-m-d H:i:s', time() - $seconds);
        $stmt = $this->db->prepare("DELETE FROM online_users WHERE last_activity < ?");
        $stmt->execute([$timeout]);
    }

    public function countOnlineUsers()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM online_users");
        return $stmt->fetchColumn();
    }
}
