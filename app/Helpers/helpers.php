<?php

if (!function_exists('retry')) {
    /**
     * Retry a callback until it succeeds or the maximum number of attempts is reached.
     *
     * @param  int  $times
     * @param  callable  $callback
     * @return mixed
     * @throws Exception
     */
    function retry(int $times, callable $callback): mixed
    {
        $attempts = 0;
        while ($attempts < $times) {
            try {
                return $callback();
            } catch (Exception $e) {
                $attempts++;
                if ($attempts >= $times) {
                    throw $e;
                }
            }
        }
        return null;
    }
}
