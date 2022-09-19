<?php

use Illuminate\Support\Facades\Auth;

/**
 * Mari membuat helper function untuk memberikan hak ases dan langsung mendirect penggunanya
 *
 * @return response()
 */
if (! function_exists('privilegeLevel')) {
    function privilegeLevel($type, $level)
    {
        // Level
        // 0:Forbidden, 1:Only See, 2:Can CRUD, 3:All Access
        if (isset(Auth::user()->privileges->$type)) {
            $userLevel = Auth::user()->privileges->$type;
            if ($userLevel < $level) {
                $params = [
                    'status'    => 'warning',
                    'message'   => 'Maaf anda tidak memiliki akses untuk melihat halaman ini'
                ];
                return redirect('home')->with($params)->send();
            }else{
                return false;
            }
        } else {
            $params = [
                'status'    => 'error',
                'message'   => 'Maaf kesalahan sistem'
            ];
            return redirect('home')->with($params)->send();
        }
    }
}