<?php

namespace App\Controllers;

use App\Models\LogModel;

class Logs extends BaseController
{
    private $logModel;
    
    public function __construct()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            exit('Access denied');
        }
        $this->logModel = new LogModel();
    }
    
    public function log()
    {
        $data['title'] = 'System Logs';
        $data['logs'] = $this->logModel->getLogsWithUser();
        return view('logs/index', $data);
    }
    
    public function clear()
    {
        $this->logModel->truncate();
        // Log the clearing action
        $this->saveLog('Cleared all system logs');
        return redirect()->to('/log')->with('success', 'All logs cleared');
    }
    
    public function export()
    {
        $logs = $this->logModel->getLogsWithUser();
        $filename = 'logs_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'User', 'Action', 'IP Address', 'User Agent', 'Timestamp']);
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['log_id'],
                $log['user_name'] ?? 'System',
                $log['action'],
                $log['ip_address'],
                $log['user_agent'],
                $log['timestamp']
            ]);
        }
        fclose($output);
        exit();
    }
    
    // Helper to save logs (can be called from other controllers)
    public static function saveLog($action, $userId = null)
    {
        $model = new LogModel();
        $request = \Config\Services::request();
        $session = \Config\Services::session();
        
        $data = [
            'user_id'    => $userId ?? $session->get('user_id'),
            'action'     => $action,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'timestamp'  => date('Y-m-d H:i:s')
        ];
        return $model->insert($data);
    }
}