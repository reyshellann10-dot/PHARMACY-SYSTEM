<?php

// Custom URL helper functions

if (!function_exists('is_active_route')) {
    /**
     * Check if current route is active
     */
    function is_active_route($route, $output = 'active')
    {
        $currentRoute = service('router')->getMatchedRoute()[0] ?? '';
        
        if ($currentRoute == $route) {
            return $output;
        }
        return '';
    }
}

if (!function_exists('route_class')) {
    /**
     * Get class for active route
     */
    function route_class($routes, $class = 'active')
    {
        $currentRoute = service('router')->getMatchedRoute()[0] ?? '';
        
        if (in_array($currentRoute, (array)$routes)) {
            return $class;
        }
        return '';
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if user has permission
     */
    function has_permission($permission)
    {
        $session = \Config\Services::session();
        $role = $session->get('role');
        
        $permissions = [
            'admin' => ['all'],
            'pharmacist' => ['products', 'categories', 'reports', 'customers'],
            'cashier' => ['sales', 'customers', 'pos']
        ];
        
        if ($role == 'admin') return true;
        
        return in_array($permission, $permissions[$role] ?? []);
    }
}