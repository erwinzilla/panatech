<?php

use Illuminate\Support\Facades\Auth;
use BladeUI\Icons\Svg;

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

/**
 * Mari membuat helper function untuk memberikan hak ases dan langsung mendirect penggunanya
 *
 * @return response()
 */
if (! function_exists('getUserLevel')) {
    function getUserLevel($type)
    {
        // Level
        // 0:Forbidden, 1:Only See, 2:Can CRUD, 3:All Access
        if (isset(Auth::user()->privileges->$type)) {
            return Auth::user()->privileges->$type;
        } else {
            return false;
        }
    }
}

/**
 * Mari membuat helper function untuk memberikan badge color
 *
 * @return response()
 */
if (! function_exists('getBadge')) {
    function getBadge($color)
    {
        return 'badge bg-'.$color.' text-'.$color.' bg-opacity-25';
    }
}

/**
 * Mari membuat helper function untuk memberikan response params
 *
 * @return response()
 */
if (! function_exists('getStatus')) {
    function getStatus($status, $type, $name, $search = null)
    {
        return [
            'status'    => $status,
            'message'   => ucwords($status).' '.$type.' '.$name,
            'search'    => $search,
        ];
    }
}

/**
 * Mari membuat helper function untuk memberikan button crud
 *
 * @return response()
 */
if (! function_exists('getButtonCRUD')) {
    function getButtonCRUD($type = 'data', $url, $id)
    {
        $form = '';
        if ($type == 'data') {
            $form = '<a href="'.url($url.'/edit').'" class="btn btn-warning btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                              <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                              <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                            </svg>
                        </a>
                        <form id="delete-'.$id.'" method="post" action="'. url($url.'/'.$id) .'" onsubmit="confirm_delete('.$id.');return false;" data-bs-toggle="tooltip" data-bs-title="Delete">
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="_token" value="'. csrf_token() .'" />
                            <button type="submit" class="btn btn-danger btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>';
        }
        if ($type == 'trash') {
            $form = '<a href="'.url($url.'/restore/'.$id).'" class="btn btn-outline-info btn-icon me-2 restore-action-link" data-bs-toggle="tooltip" data-bs-title="Restore">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                                    <path d="M11.47 1.72a.75.75 0 011.06 0l3 3a.75.75 0 01-1.06 1.06l-1.72-1.72V7.5h-1.5V4.06L9.53 5.78a.75.75 0 01-1.06-1.06l3-3zM11.25 7.5V15a.75.75 0 001.5 0V7.5h3.75a3 3 0 013 3v9a3 3 0 01-3 3h-9a3 3 0 01-3-3v-9a3 3 0 013-3h3.75z" />
                                </svg>
                            </a>
                            <a id="delete-link-'.$id.'" href="'.url($url.'/delete/'.$id).'" class="btn btn-outline-danger btn-icon delete-action-link" onclick="confirm_delete_link('.$id.'); return false;" data-bs-toggle="tooltip" data-bs-title="Delete Permanent">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                </svg>
                            </a>';
        }
        return $form;
    }
}

/**
 * Mari membuat helper function untuk memberikan button crud
 *
 * @return response()
 */
if (! function_exists('responseJSON')) {
    function responseJSON($data, $with = null)
    {
        if ($data->get()->count() > 0) {
            if ($data->get()->count() > 1) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Data tidak spesifik masukan input yang sesuai',
                    'data'      => null,
                ]);
            }else {
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Sukses mengambil data',
                    'data'      => $with ? $with->first() : $data->first(),
                ]);
            }
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Data tidak ditemukan',
                'data'      => null,
            ]);
        }
    }
}