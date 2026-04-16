<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'log_id';
    protected $allowedFields = ['user_id', 'action', 'ip_address', 'user_agent', 'timestamp'];
    protected $useTimestamps = false;
    
    // Get logs with user names
    public function getLogsWithUser()
    {
        return $this->select('logs.*, users.full_name as user_name')
                    ->join('users', 'users.user_id = logs.user_id', 'left')
                    ->orderBy('logs.timestamp', 'DESC')
                    ->findAll();
    }
}