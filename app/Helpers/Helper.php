<?php

namespace App\Helpers;

use App\Enums\UserViolateStatus;
use App\Models\ForbiddenWord;
use App\Models\KeywordUse;
use App\Models\UserViolate;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

class Helper
{
    /**
     * Create log admin
     * @param $log_id
     * @param $log_message
     * @return void
     */
    public static function create_admin_log($log_id, $log_message = null)
    {
        try {
            $log_data = [
                'admin_id' => auth('admin')->id(),
                'log_id' => $log_id,
                'log_time' => time(),
                'log_message' => serialize($log_message)
            ];
            DB::table('admin_log_content')->insert($log_data);
        }catch (\Exception $exception){
            Toastr::error("Lỗi không xác định");
        }

    }

    public static function array_remove_null($item)
    {
        if (!is_array($item)) {
            return $item;
        }

        return collect($item)
            ->reject(function ($item) {
                return is_null($item);
            })
            ->flatMap(function ($item, $key) {

                return is_numeric($key)
                    ? [Helper::array_remove_null($item)]
                    : [$key => Helper::array_remove_null($item)];
            })
            ->toArray();
    }
    public static function format_money($value){
        if ($value == 0)
            return 0;

        if ($value == null)
            return null;
        $num = str_replace('.00', '', number_format($value, 2, '.', '')) + 0;
        return number_format($num, 0, ',', ',');
    }

    public static function get_path_admin_image($url)
    {
        return $url
            ? '/system/img/avatar-admin/' . $url
            : asset('frontend/images/personal-logo.png');
    }
    public static function get_path_user_image($url)
    {
        if ($url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
            return asset('/img/avatar/' . $url);
        }

        return asset('frontend/images/personal-logo.png');
    }

    /**
     * Get Day of week
     * @return string
     */
    public static function get_day_of_week($strtotime){
        $weekMap = [
            0 => 'Chủ nhật',
            1 => 'Thứ hai',
            2 => 'Thứ ba',
            3 => 'Thứ tư',
            4 => 'Thứ năm',
            5 => 'Thứ sáu',
            6 => 'Thứ bảy',
        ];
        $dayOfTheWeek = Carbon::parse((int) $strtotime)->dayOfWeek;
        return $weekday = $weekMap[$dayOfTheWeek];
    }

    /**
     * Get upload dir of user
     * @param $user
     * @return string
     */
    public static function get_upload_dir_user($user){
        return "uploads/users/$user->user_code";
    }

    public static function get_time_to($time){
        return $time ? Carbon::createFromTimestamp($time)->diffForHumans() : '';

        $mininute = 60;
        $hour = 60*60;
        $day = $hour*24;
        $month = $day*30 ;
        $year = $month*12;

        if($time == null) return '';
        $check_time = time() - $time ;
        if($check_time <$hour){
            $out = $check_time /60;
            return (int)$out.' phút trước';
        }
        if($check_time <= $day){
            $out = (int)$check_time/$hour;
            return (int)$out.' giờ trước';
        }
        if($check_time <= $month){
            $out = (int)$check_time/$day;
            return (int)$out.' ngày trước';
        }
        if($check_time <= $year){
            $out = (int)$check_time/$month;
            return (int)$out.' tháng trước';
        }
        if($check_time >= $year){
            $out = (int)$check_time/$year;
            return (int)$out.' năm trước';
        }
        return date('d/m/Y H:i',$time);
    }

    /**
     * get diff for human time with period
     * @param DateTime|string|null $time
     * @param int|null $period = 24 | hours
     * @param int $format = 'd/m/Y'
     *
     * @return string $diffTime
     */
    public static function getHumanTimeWithPeriod($time, $period = 24, $format = 'd/m/Y'): string
    {
        if (!$time || !(is_string($time) || is_int($time) || is_object($time))) return '';

        if ($time instanceof DateTime) {
            $dateTime = Carbon::parse($time);
        } else {
            $dateTime = Carbon::createFromTimestamp($time);
        }

        if ($period) {
            $endNowDate = now()->endOf('day');
            if ($endNowDate->addHours($period * -1) < $dateTime) {
                return $dateTime->diffForHumans();
            } else {
                if ($endNowDate->addDay(-1) < $dateTime) {
                    // in day
                    return $dateTime->diffForHumans();
                } elseif ($endNowDate->startOf('year') < $dateTime) {
                    // in year
                    return $dateTime->format('d') . ' thg ' . $dateTime->format('m');
                } elseif ($format) {
                    return $dateTime->format($format);
                }
            }
        }

        return $dateTime->diffForHumans();
    }

    /**
     * increment keyword use count
     * @param string $str
     * @param int $type
     *
     * @return void
     */
    public static function check_keyword($str,$type){
        $keyword = trim(strtolower($str));
        $keyword = preg_replace("/[^A-Za-z0-9\-\s\-\_]/", "", $keyword);
        $keyword = preg_replace("/\s+/", " ", $keyword);

        if (!$keyword) return;

        KeywordUse::updateOrCreate([
            'keyword' => $keyword
        ], [
            'keyword_type' => $type
        ])->increment('number_use');
    }

    /**
     * Check blocked keywords for content
     * @param string|null $content
     * @param boolean $isReturnWords = false
     *
     * @return array|bool $results
     */
    public static function checkBlockedKeyword($content, bool $isReturnWords = false)
    {
        if (!$content) {
            return $isReturnWords ? [] : null;
        };

        $blockedWords = ForbiddenWord::select('forbidden_word')
            ->pluck('forbidden_word');

        $results = $isReturnWords ? [] : false;

        $baseContent = strtolower($content);

        foreach($blockedWords as $word) {
            $word = strtolower($word);
            // remove special characters keep vietnamese
            $word = preg_replace('/[!#$%^&*()+=\-_\[\]\';,.\/{}|":<>@?~\\\\¿§«»ω⊙¤°℃℉€¥£¢¡®©]/', '', $word);

            if (preg_match("/\b$word\b/", $baseContent)) {
            // if (str_contains($baseContent, $word) == true) {
                if ($isReturnWords) {
                    $results[] = $word;
                } else {
                    return true;
                }
            }
        }

        return $results;
    }

    /**
     * Create user violate
     * @param array $data
     *
     * @return UserViolate $userViolate
     */
    public static function createUserViolate(array $data)
    {
        return UserViolate::create([
            'user_id' => data_get($data, 'user_id'),
            'target_id' => data_get($data, 'target_id'),
            'target_type' => data_get($data, 'target_type'),
            'action' => data_get($data, 'action'),
            'action_url' => data_get($data, 'action_url'),
            'status' => data_get($data, 'status', UserViolateStatus::ACTIVE),
            'options' => (array) data_get($data, 'options')
        ]);
    }

    public static function convertKebabCase(string $str)
    {
        $str = self::replaceVietNamese($str);

        // Replace repeated spaces to underscore
        $str = preg_replace('/[\s.]+/', '_', $str);
        // Replace un-willing chars to hyphen.
        $str = preg_replace('/[^0-9a-zA-Z_\-]/', '-', $str);
        // Skewer the capital letters
        $str = strtolower(preg_replace('/[A-Z]+/', '-\0', $str));
        $str = trim($str, '-_');

        return preg_replace('/[_\-][_\-]+/', '-', $str);
    }

    public static function replaceVietNamese($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);

        return $str;
    }
}
