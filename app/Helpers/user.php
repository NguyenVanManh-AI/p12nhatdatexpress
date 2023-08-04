<?php

use App\Enums\AdminConfigCode;
use App\Enums\UserEnum;
use App\Enums\UserLogType;
use App\Models\AdminConfig;
use App\Models\District;
    use App\Models\Property;
    use App\Models\Province;
    use App\Models\UserLog;
use App\Models\Ward;
use Carbon\Carbon;
    use Gumlet\ImageResize;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Cookie;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Str;

    /**
     * Show validate error message
     * @param $errors
     * @param string $validate_name
     * @return void
     */
    function show_validate_error($errors, $validate_name)
    {
        if ($errors->has("$validate_name")) {
            echo '<small class="text-danger d-block mt-1">' . $errors->first("$validate_name") . '</small>';
        }
    }

    function showError($errors, $validate_name)
    {
        if ($errors->has("$validate_name")) {
            echo '<span class="c-input__error invalid-feedback">' . $errors->first("$validate_name") . '</span>';
        }
    }

    /**
     * Create user log
     * @param int $log_id
     * @param string $log_message
     * @return void
     */
    function create_user_log($log_id, $log_message = null)
    {
        $user = Auth::guard('user')->user();
        if (!$user) return;

        $log_data = [
            'user_id' => $user->id,
            'log_id' => $log_id,
            'log_time' => time(),
            'log_message' => $log_message
        ];
        DB::table('user_log_content')
            ->insert($log_data);
    }

    /**
     * Create user log by action
     * @param string $action
     * @param string $message = null
     * @param string $logType = UserLogType::UPDATE_INFO
     *
     * @return void
     */
    function createUserLog($action, $message = null,  $logType = UserLogType::UPDATE_INFO) {
        $logAction = UserLog::where('log_type', $logType)
            ->firstWhere('key', $action);

        if ($logAction) {
            create_user_log($logAction->id, $message);
        }
    }

    /**
     * achieve user file from upload form
     * @param $file
     * @param $upload_dir
     * @return mixed
     */
    function file_helper($file, $upload_dir)
    {
        $new_file_name = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file_url = $file->move($upload_dir, $new_file_name)->getPathName();
        return $file_url;
    }

    function upload_image($file, $upload_dir, $width = 300, $height = 300)
    {
       $image = new ImageResize($file);
       $image->resize($width, $height);
       $filePath = $upload_dir.'/'.$new_file_name = Str::random(40) . '.' . $file->getClientOriginalExtension();
       $image->save($filePath);
       return $filePath;
    }


    /**
     * Convert time from int format to dateformat
     * @param int $time
     * @return false|string
     */
    function vn_date($time)
    {
        return date('d/m/Y', $time);
    }


    /**
     * @param $date
     * @return string
     */
    function get_time($date)
    {
        Carbon::setLocale('vi');
        $time = Carbon::createFromTimeStamp($date);
        $now = Carbon::now();
        $diff = $now->diffInMonths($time);
        if ($diff < 1) {
            return $time->diffForHumans();
        }
        return $time->format('d/m/Y');
    }

     /**
     * get diff for human time with period
     * @param DateTime|string|null $time
     * @param int|null $period = 24 | hours
     * @param int $format = 'd/m/Y'
     *
     * @return string $diffTime
     */
    function getHumanTimeWithPeriod($time, $period = 24, $format = 'd/m/Y'): string
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
     * @param $date
     * @return int
     */
    function get_diff_minute($date)
    {
        Carbon::setLocale('vi');
        $time = Carbon::createFromTimeStamp($date);
        $now = Carbon::now();
        return $now->diffInMinutes($time);
    }

    /**
     * Share facebook with url
     * @param string $url
     * @return string
     */
    function share_fb($url): string
    {
        return "https://www.facebook.com/sharer/sharer.php?u=$url";
    }


    /**
     * show data collection  to  select element html
     * @param \Illuminate\Support\Collection $collect
     * @param mixed $option_value
     * @param string $option_text
     * @param string $select_name
     * @param mixed $current_value
     * @param int $level
     * @param boolean $withChildren
     * @param string $subText = ''
     * @return void
     */
    function show_select_option(
        $collect,
        $option_value,
        $option_text,
        $select_name,
        $current_value = null,
        $level = 0,
        $withChildren = true,
        $subText = ''
    ) {
        if (!old($select_name) && $current_value == null && $level++ == 0) {
            echo "<option value='' selected></option>";

        }

        if ($collect) {
            foreach ($collect as $item) {
                $value = data_get($item, $option_value, $item);

                $text = data_get($item, $option_text);
                if (!$text) {
                    $text = is_string($item) || is_int($item) ? $item . ' ' : '';
                    if ($subText)
                        $text .= $subText;
                }

                $allChildren = [];
                if (isset($item->allChildren)) {
                    $allChildren = $item->allChildren;
                } elseif (isset($item->children)) {
                    $allChildren = $item->children;
                }

                $disabled = isset($allChildren->items) ? 'disabled' : null;
                $oldValue = $select_name != null ? old($select_name, $current_value) : $current_value;
                $selected = $value == $oldValue ? 'selected' : '';

                switch ($level) {
                    case 2:
                        $text = '-- ' . $text;
                        break;
                    case 3:
                        $text = '---- ' . $text;
                        break;
                    default:
                        break;
                }

                echo "<option value='$value' $selected class='text-black' $disabled>$text</option>";

                if (isset($allChildren) && $withChildren) {
                    show_select_option($allChildren, $option_value, $option_text, $select_name, $current_value ?? null, $level);
                }
            }
        }
    }

    /**
     * show data collection to select element html
     * @param \Illuminate\Support\Collection $items
     * @param mixed $itemValue
     * @param string $itemText
     * @param array $selectedItems
     *
     * @return void
     */
    function showSelectMultipleOptions($items, $itemValue, $itemText, $selectedItems = [])
    {
        foreach ($items as $item) {
            $value = data_get($item, $itemValue);
            $text = data_get($item, $itemText);

            $selected = in_array($value, $selectedItems) ? 'selected' : '';

            echo "<option value='$value' class='text-black' $selected>$text</option>";
        }
    }

    /**
     * achieve files from input file form
     * @param $files
     * @param $upload_dir
     * @return array
     */
    function multi_file_helper($files, $upload_dir)
    {
        $file_array = [];
        foreach ($files as $file) {
            $file_path = file_helper($file, $upload_dir);
            array_push($file_array, $file_path);
        }
        return $file_array;
    }


    /**
     * check user was blocked
     * @return string|void
     */
    function user_blocked($action = 'đăng tin')
    {
        $user = Auth::guard('user')->user();

        if (!$user) return null;

        if ($user->isForbidden()) {
            return 'Tài khoản bị cấm, không thể ' . $action;
        }
        if ($user->isBlocked()) {
            return 'Tài khoản bị khóa, không thể ' . $action;
        }
        if ($user->is_deleted == 1) {
            return 'Tài khoản đã xóa, không thể ' . $action;
        }
        if ($user->is_active == 0) {
            return 'Tài khoản chưa kích hoạt, không thể ' . $action;
        }

        return null;
    }

     /**
     * check user was spammed
     * @return string|void
     */
    function userSpammed()
    {
        $user = Auth::guard('user')->user();

        if (!$user) return '';

        return $user->isSpammed()
            ? 'Vui lòng thử lại sau 24h hệ thống nhận thấy dấu hiệu Spam'
            : '';
    }

    /**
     * Check account was verifed if user was deposited morethan 100.000 vnd
     * @return int: 1.Da xac thuc, 0. Chua xac thuc
     */
    function account_verify()
    {
        $user = Auth::guard('user')->user();

        if (!$user) return 0;

        $depositAmount = $user->deposits()
            ->where('deposit_status', 1)
            ->sum('deposit_amount');

        return $depositAmount >= UserEnum::VERIFY_DEPOSIT_AMOUNT
            ? 1
            : 0;
    }

    /**
     * Create random string used to payment code
     * @param $n
     * @return string
     */
    function random_string($n)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    function format_phone_number($phoneNumber): string
    {
        return substr($phoneNumber, 0, 3) . ' ' . substr($phoneNumber, 3, 3) . ' ' . substr($phoneNumber, 6);
    }

    function format_hide_phone_number($phoneNumber): string
    {
        return substr($phoneNumber, 0, 3) . ' ' . substr($phoneNumber, 3, 3) . ' ' . '****';
    }

    # get province with cache
    function get_cache_province()
    {
        return Cache::remember('provinces', now()->addDay(1), function () {
            return Province::select('id', 'province_name', 'province_type', 'province_url')->orderBy('province_type', 'desc')->showed()->get();
        });
    }

    # get province with cache
    function get_cache_district($province_id = null)
    {
        return Cache::rememberForever('districts', function () use ($province_id) {
            $query = District::select('id', 'district_name', 'district_type', 'district_url', 'province_id')->orderBy('district_name', 'asc')->showed();
            if ($province_id) $query->where('province_id', $province_id);
            return $query->get();
        });
    }

    # get ward with cache
    function get_cache_ward($district_id = null)
    {
        return Cache::rememberForever('ward', function () use ($district_id) {
            $query = Ward::select('id', 'ward_name', 'ward_type', 'district_id')->orderBy('ward_name', 'asc')->showed();
            if ($district_id) $query->where('province_id', $district_id);
            return $query->get();
        });
    }

    /**
     * get properties
     */
    function getCacheProperties()
    {
        return Cache::rememberForever('properties', function() {
            return Property::where('properties_type', 0)
                ->get();
        });
    }

    /**
     * get watched classifieds
     */
    function getWatchedClassifieds()
    {
        return json_decode(Cookie::get('watched_classified'), true) ?? [];
    }

    # get fee service
    function get_fee_service($id)
    {
        return DB::table('service_fee')->where('id', $id)->select([
            'id',
            'service_name',
            'service_unit',
            'existence_time',
            DB::raw("
                (CASE WHEN service_discount_coin IS NOT NULL
                     THEN service_discount_coin
                     ELSE service_coin
                END) as service_fee
                ")
        ])->first();
    }


    /**
     * Cap phat ma tin dang
     * @return string
     */
    function get_classified_code(): string
    {
        $code = "ND" . substr(time(), -8);
        while (DB::table('classified')->where('classified_code', $code)->first()) {
            $code = "ND" . substr(time(), -8);
        }

        return $code;

    }

    /**
     * get google api key
     * @return string $googleApiKey
     */
    function getGoogleApiKey()
    {
        $systemConfig = Cache::rememberForever('system_config', function() {
            return DB::table('system_config')->first();
        });

        return data_get($systemConfig, 'google_map', config('services.google_map.key'));
    }

    /**
     * get district location
     * @param $forceAccept = false
     *
     * @return $districtId|null
     */
    function getDistrictLocation($forceAccept = false)
    {
        return Session::get('accept_location') || $forceAccept ? Session::get('district') : null;
    }

    /**
     * check already accept location
     *
     * @return bool
     */
    function isAcceptLocation(): bool
    {
        return Session::has('accept_location');
    }

    /**
     * get session location
     * @param string $locationName
     * @param $forceAccept = false
     *
     * @return $districtId|null
     */
    function getSessionLocation($locationName, $forceAccept = false)
    {
        return Session::get('accept_location') || $forceAccept ? Session::get($locationName) : null;
    }

    /**
     * get unauthorized message
     * @return string
     */
    function getUnauthorizedMessage()
    {
        return 'Bạn không đủ quyền';
    }

    function removeFile($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }

    /**
     * chuyen base64 thanh file
     * @param $base64_data
     * @param $directory
     * @param boolean $thumbnail = false
     * @param int $width = 800
     * @param int $height = 800
     * @return string
     */
    function base64ToFile($base64_data, $directory, $thumbnail = false, $width = 800, $height = 800)
    {
        if (!isBase64($base64_data)) return '';

        $extension = explode('/', mime_content_type($base64_data))[1];
        $content = explode(',', $base64_data)[1];
        $imageContent = str_replace(' ', '+', $content);
        $filename = "$directory/" . Str::random(20);

        try {
            mkdir($directory);
        } catch (Exception $e) {}

        $image = ImageResize::createFromString(base64_decode($imageContent));

        $originWidth = $image->getSourceWidth();
        $originHeight = $image->getSourceHeight();

        $filename .= $thumbnail ? '_thumb' : '';
        $filename .= ".$extension";

        $width = $width ?: $originWidth;
        $height = $height ?: $originHeight;

        // crop center image depends on min width and height
        $image->crop((min($width, $originWidth)), min($height, $originHeight));
        $image->save($filename);

        return "$filename";
    }

    /**
     * Chuyen file thanh base64
     * @param $fileUrls
     * @return array
     */
    function fileToBase64($fileUrls = [])
        {
            $base64Files = [];
            if ($fileUrls) {
                foreach ($fileUrls as $fileUrl) {
                    $type = pathinfo($fileUrl, PATHINFO_EXTENSION);
                    try {
                        $data = file_get_contents($fileUrl);
                        $base64File = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        array_push($base64Files, $base64File);
                    } catch (Exception $ex) {
                    }

                }
            }

            return $base64Files;

        }

    /**
     * Format price
     * @param string|integer $price = 0
     * @param integer $decimals = 2
     * @param string $decimalSeparator = '.'
     * @param string $thousandsSeparator = ','
     *
     * @return string $price
     */
    function formatPrice($price = 0, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = ',')
    {
        $priceFormat = number_format($price, $decimals);

        $priceFormat = rtrim($priceFormat, 0);
        $priceFormat = rtrim($priceFormat, '.');

        return $priceFormat;
    }

    /**
     * format timestamp to date time
     * @param string|integer $timestamp
     *
     * @return string $dateTimeFormat
     */
    function timestampToDateTime($timestamp)
    {
        if (!$timestamp) return '';

        $isTime = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp) !== false;

        return $isTime
            ? $timestamp
            : Carbon::createFromTimestamp($timestamp);
    }

    /**
     * check string is base64
     * @param string $string
     *
     * @return bool
     */
    function isBase64($string): bool
    {
        $string = preg_replace("/^data:image\/(\w+);base64,/", "", $string);

        return $string && base64_encode(base64_decode($string, true)) === $string;
    }

    /**
     * check admin config is enabled mail campaign or not for user
     * @return bool
     */
    function getEnabledMailCampaign(): bool
    {
        $emailConfig = Cache::rememberForever('mail_campaign_config', function() {
            return AdminConfig::select('config_value')->firstWhere('config_code', AdminConfigCode::MAIL_CAMPAIGN);
        });

        return data_get($emailConfig, 'config_value') ? true : false;
    }
