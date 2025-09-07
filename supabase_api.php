<?php
require_once 'supabase_api.php';

if (!function_exists('supabase_request')) {
    function supabase_request($endpoint, $method = 'GET', $data = null) {
        $base_url = "https://zflminlprlrmziqnowlq.supabase.co/rest/v1/";
        $api_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InpmbG1pbmxwcmxybXppcW5vd2xxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTcwMzg5NjUsImV4cCI6MjA3MjYxNDk2NX0.KXo-ipH94UmQXOlEs8IRSWbQJ1TPu1OqUAvx_AmQ200";
        $opts = [
            "http" => [
                "header" => "Content-Type: application/json\r\nAuthorization: Bearer $api_key",
                "method" => $method,
            ]
        ];
        if ($data !== null) {
            $opts["http"]["content"] = json_encode($data);
        }
        $context = stream_context_create($opts);
        $result = file_get_contents($base_url . $endpoint, false, $context);
        if ($result === FALSE) {
            return false;
        }
        return json_decode($result, true);
    }
}