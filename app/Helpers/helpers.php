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

if (! function_exists('getPrice')) {
    function getPrice($num, $class = null)
    {
        return '<div class="d-flex '.$class.'">
                    <span class="me-2">Rp. </span>
                    <span>'.number_format($num, 0, ',', '.').'</span>
                </div>';
    }
}

if (! function_exists('getWorkingDay')) {
    function getWorkingDay($now = null)
    {
        // do strtotime calculations just once
        $endDate = strtotime(date('Y-m-t'));
        $startDate = strtotime(date('Y-m-01'));

        // jika memiliki tanggal
        if ($now) {
            $startDate = strtotime($now);
        }

        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        $holidays = [
            '2022-01-01',
            '2022-01-01', // Tahun Baru Masehi 2022
            '2022-01-02', // Tahun Baru Imlek
            '2022-01-03', // Isra' Mi'raj
            '2022-01-04', // Nyepi
            '2022-01-05', // Wafat Isa Al-masih
            '2022-01-06', // Hari Buruh
            '2022-01-07', // Idul Fitri
            '2022-01-08', // Idul Fitri
            '2022-01-09', // Libur Perusahaan
            '2022-01-10', // Cuti Bersama
            '2022-01-11', // Cuti Bersama
            '2022-01-12', // Waisak
            '2022-01-13', // Kenaikan Isa
            '2022-01-14', // Pancasila
            '2022-01-15', // Idul Adha
            '2022-01-16', // Tahun Baru Hijriah
            '2022-01-17', // Kemerdekaan
            '2022-01-18', // Maulid Nabi
            '2022-01-19', //	Natal
            '2022-01-20', //	Libur Perusahaan
            '2022-01-21', //	Cuti Bersama
            '2022-01-22', //	Cuti Bersama
        ];

        //We subtract the holidays
        foreach($holidays as $holiday){
            $time_stamp=strtotime($holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }
}