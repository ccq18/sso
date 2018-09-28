<?php
if (!function_exists('build_url')) {

    function build_url($path, $parameters = [])
    {

        $output = get_url_params($path);
        $parm_str = http_build_query(array_merge($output, $parameters));

        return url(ltrim(clear_urlcan($path), '\/') . (empty($parm_str) ? "" : '?' . $parm_str));
    }

}
if (!function_exists('get_url_params')) {

    function get_url_params($path)
    {
        $info = parse_url($path);
        $params = isset($info['query']) ? $info['query'] : "";
        parse_str($params, $output);

        return $output;
    }
}
if (!function_exists('clear_urlcan')) {

    function clear_urlcan($url)
    {
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }

        return $url;
    }
}