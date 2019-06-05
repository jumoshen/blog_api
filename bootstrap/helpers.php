<?php

if (!function_exists('is_phone_number')) {
    /**
     * 判断是否为手机号
     *
     * @param $value
     * @return bool
     */
    function is_phone_number($value)
    {
        return !!preg_match('/^1[3456789]\d{9}$/', $value);
    }
}

if (!function_exists('is_email_address')) {
    /**
     * 判断是否为邮箱
     *
     * @param $value
     * @return bool
     */
    function is_email_address($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('generate_random_string')) {
    /**
     * 生成指定长度的随机字符串
     * @param null $characters
     * @param int $length
     * @return string
     */
    function generate_random_string($characters = null, $length = 8)
    {
        if (is_null($characters)) {
            $lowercase = 'abcdefghjkmnpqrstuvwxyz';
            $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            $digits = '23456789';
            $characters = $lowercase . $uppercase . $digits;
        }
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('generate_random_digits')) {
    /**
     * 生成指定长度的随机数字字符串
     *
     * @param null $digits
     * @param int $length
     * @return string
     */
    function generate_random_digits($digits = null, $length = 6)
    {
        if (is_null($digits)) {
            $digits = '0123456789';
        }

        return generate_random_string($digits, $length);
    }
}

if (!function_exists('clac_time_diff')) {
    /**
     * 计算两个时间的间隔
     *
     * @param $startdate
     * @param $enddate
     * @return string
     * @internal param null $digits
     * @internal param int $length
     */
    function clac_time_diff($startdate, $enddate)
    {
        $date = floor((strtotime($enddate) - strtotime($startdate)) / 86400);
        $hour = floor((strtotime($enddate) - strtotime($startdate)) % 86400 / 3600);
        $minute = floor((strtotime($enddate) - strtotime($startdate)) % (86400 * 3600) / 60);
        $second = floor((strtotime($enddate) - strtotime($startdate)) % (86400 * 3600 * 60) % 60);
        return $date . '天' . $hour . '小时' . $minute . '分' . $second . '秒';
    }
}

if (!function_exists('api_response')) {
    /**
     * API Response Wrapper.
     *
     * @param $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    function api_response($data = [], $status = 200, array $headers = [], $options = 0)
    {
        $now = microtime(true);

        $data = [
            'data' => $data,
            'meta' => [
                'timestamp' => $now,
                'response_time' => $now - LARAVEL_START,
            ],
        ];

        return response()->json($data, $status, $headers, $options);
    }
}

if (!function_exists('allows_any')) {
    /**
     * Determine if the given any abilities should be granted for the current user
     *
     * @param string|array $abilities
     * @param array $arguments
     * @return bool
     */
    function allows_any($abilities, $arguments = []) {
        foreach ((array) $abilities as $ability) {
            if (Gate::allows($ability, $arguments)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('allows_all')) {
    /**
     * Determine if the given all abilities should be granted for the current user
     *
     * @param string|array $abilities
     * @param array $arguments
     * @return bool
     */
    function allows_all($abilities, $arguments = []) {
        foreach ((array) $abilities as $ability) {
            if (!Gate::allows($ability, $arguments)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('auto_rename')) {
    /**
     * Auto rename new filename.
     *
     * when it taken in list, auto increment new name, likes file.txt, file (1).txt
     *
     * @param $name
     * @param array $collect
     * @return string
     */
    function auto_rename($name, $collect = []) {
        $pieces = pathinfo($name);
        $filename = $pieces['filename'];
        $extension = $pieces['extension'] ? '.' . $pieces['extension'] : '';

        $i = 0;
        while (1) {
            if (in_array($name, $collect)) {
                $name = $filename . ' (' . ++$i . ')' . $extension;
            } else {
                break;
            }
        }

        return $name;
    }
}

if (! function_exists('datetime_format')) {
    /**
     * 格式化输出日期/时间
     *
     * @param DateTime|integer|string $dateTime
     * @param string $format
     * @return false|string
     */
    function datetime_format($dateTime, $format = 'Y-m-d H:i:s') {
        if ($dateTime instanceof \DateTime) {
            return $dateTime->format($format);
        }

        if (is_numeric($dateTime)) {
            return date($format, $dateTime);
        }

        return date($format, strtotime($dateTime));
    }
}

if (!function_exists('html_error_response')) {
    /**
     * API Response Wrapper.
     *
     * @param $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    function html_error_response($data = [], $status = 500, array $headers = [])
    {
        $now = microtime(true);

        $data = [
            'data' => $data,
            'meta' => [
                'timestamp' => $now,
                'response_time' => $now - LARAVEL_START,
            ],
        ];

        return response()->view('error_file',$data,$status,$headers);
        //json($data, $status, $headers, $options);
    }
}

if (! function_exists('sanitize_invisible_characters')) {
    /**
     * 去除不可见的非 UTF-8 字符，比如退格键<0x08>等，可用于生成Word等文档时过滤无效的字符
     *
     * @param mixed $string 要无害滑的字符
     * @param string $replacer 代替字符
     * @return string
     */
    function sanitize_invisible_characters($string, $replacer = '?')
    {
        if (! is_string($string)) {
            return $string;
        }

        $string = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
            '|[\x00-\x7F][\x80-\xBF]+'.
            '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
            '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
            '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
            $replacer, $string);

        // reject overly long 3 byte sequences and UTF-16 surrogates and replace with replacer
        $string = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]'.
            '|\xED[\xA0-\xBF][\x80-\xBF]/S', $replacer, $string);

        return $string;
    }
}

if (!function_exists('diff_in_month')) {
    /**
     * 计算两个时间之间的差别，并格式化成 *年*月
     *
     * @param $startDate
     * @param string $endDate
     * @return string
     */
    function diff_in_month($startDate, $endDate = '')
    {
        if (!$startDate) return '';

        $months = (new \Carbon\Carbon($endDate))->diffInMonths($startDate);
        $years = ($months/12 >= 1) ? floor($months/12) . '年' : '';
        $month = $months%12 > 0 ? ($months%12 . '个月') : '';
        return $years . $month;
    }
}

if (!function_exists('get_ie_file_name')) {
    function get_ie_file_name($file_name)
    {
        $userAgent = strtolower(request()->userAgent());
        # 判断是否为IE或Edge浏览器
        if (preg_match('/msie/', $userAgent) || preg_match('/edge/', $userAgent)) {
            $file_name = str_replace('+', '%20', urlencode($file_name));
        }
        # ie 11
        if (preg_match('/^((?!webkit).)*like gecko/', $userAgent)) {
            $file_name = rawurlencode($file_name);
        }

        return $file_name;
    }
}
