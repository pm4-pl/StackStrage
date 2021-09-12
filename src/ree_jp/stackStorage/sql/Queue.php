<?php

namespace ree_jp\stackStorage\sql;

use Closure;

class Queue
{
    static array $queues = [];

    static function enqueue(string $xuid, Closure $func): void
    {
        $empty = empty(self::$queues[$xuid]);
        if ($empty) {
            self::$queues[$xuid] = [];
        }
        array_push(self::$queues[$xuid], $func);
        if ($empty) $func();
    }

    static function dequeue(string $xuid): void
    {
        if (isset(self::$queues[$xuid]) && !empty(self::$queues[$xuid])) {
            array_shift(self::$queues[$xuid]);
            $next = current(self::$queues[$xuid]);
            if ($next !== false) {
                $next();
            }
        }
    }

    static function isEmpty(): bool
    {
        foreach (self::$queues as $queue) {
            if (!empty($queue)) return false;
        }
        return true;
    }
}
