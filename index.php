<?php

namespace {
    function abort(string $alert, $exit = true) {
        \ob_start();
        \debug_print_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $trace = \explode("\n", \strtr(\trim(\ob_get_clean()), [\PATH . \D => '.' . \D]));
        foreach ($trace as &$v) {
            $v = \trim(\strstr($v, ' '));
        }
        unset($v);
        echo \x\test\_(true, $alert, '<pre style="background:0 0;border:0 solid #000;border-top-width:1px;border-radius:0;box-shadow:none;color:inherit;display:flex;font:inherit;margin:0;padding:0;text-shadow:none;white-space:pre;"><span style="background:#eee;padding:.5em .75em;user-select:none;">' . \implode("\n", \range(1, \count($trace))) . '</span><span style="flex:1;overflow:auto;padding:.5em .75em;">' . \implode("\n", \array_reverse($trace)) . '</span></pre>', ['#000', '#000', '#fff', '#fc9', '#000']);
        $exit && exit;
    }
    function test(...$lot) {
        $content = "";
        $keys = [];
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $at = $trace['line'] ?? 0;
        $file = $trace['file'] ?? "";
        $z = \implode("\n", \array_slice(\file($file, \FILE_IGNORE_NEW_LINES), $at - 1));
        $z = \preg_split('/\b(?=test\s*\()/', $z, 2)[1]; // Ignore any code before `test()` in the current line
        $open = 0;
        foreach (\token_get_all((0 === \strpos($z, '<?php') ? "" : '<?php ') . $z) as $v) {
            $var = '$' === (\end($keys)[0] ?? 0);
            if (\is_array($v)) {
                if (\T_ARRAY === $v[0] && $var) {
                    $keys[\count($keys) - 1] .= $v[1];
                    continue;
                }
                if (\T_CLOSE_TAG === $v[0]) {
                    break; // End of function call
                }
                if (\T_CONSTANT_ENCAPSED_STRING === $v[0] || \T_DNUMBER === $v[0] || \T_LNUMBER === $v[0] || \T_STRING === $v[0]) {
                    if ($var && false !== \strpos('(,>[ ', \substr(\end($keys), -1))) {
                        $keys[\count($keys) - 1] .= $v[1];
                        continue;
                    }
                    $keys[] = $v[1];
                    continue;
                }
                if (\T_OBJECT_OPERATOR === $v[0] && $var) {
                    $keys[\count($keys) - 1] .= $v[1];
                    continue;
                }
                if (\T_VARIABLE === $v[0]) {
                    $keys[] = $v[1];
                    continue;
                }
                continue;
            }
            if ('(' === $v) {
                if ($var) {
                    $keys[\count($keys) - 1] .= $v;
                }
                $open += 1;
                continue;
            }
            if (')' === $v) {
                if ($var && $open > 1) {
                    $keys[\count($keys) - 1] .= $v;
                }
                $open -= 1;
                continue;
            }
            if ($var && ('[' === $v || ']' === $v)) {
                $keys[\count($keys) - 1] .= $v;
                continue;
            }
            if (',' === $v) {
                if ($open > 1 && $var) {
                    $keys[\count($keys) - 1] .= $v . ' ';
                }
                continue;
            }
            if ($var && $open) {
                $keys[\count($keys) - 1] = null; // Mixed variable and value
                continue;
            }
            if (';' === $v) {
                break; // End of function call
            }
        }
        \array_shift($keys); // Drop function name
        foreach (\array_values($lot) as $k => $v) {
            $dent = "";
            $v = \z($v, true);
            $value = "";
            foreach (\token_get_all('<?php ' . $v) as $vv) {
                if (\is_array($vv)) {
                    if (\T_DOUBLE_ARROW === $vv[0]) {
                        $value .= ' ' . $vv[1] . ' ';
                        continue;
                    }
                    $value .= $vv[1];
                    if (\T_OBJECT_CAST === $vv[0]) {
                        $value .= ' ';
                    }
                    continue;
                }
                if (',' === $vv) {
                    $value .= $vv . "\n" . $dent;
                    continue;
                }
                if ('.' === $vv) {
                    $value .= ' ' . $vv . ' ';
                    continue;
                }
                if ('[' === $vv) {
                    $value .= $vv . "\n" . ($dent .= '  ');
                    continue;
                }
                if (']' === $vv) {
                    $dent = \substr($dent, 2);
                    if ('[' === \substr($test = \trim($value), -1)) {
                        $value = $test . $vv; // Empty array
                        continue;
                    }
                    $value .= "\n" . $dent . $vv;
                    continue;
                }
                $value .= $vv;
            }
            if ($var = '$' === ($keys[$k][0] ?? 0)) {
                $value = \substr_replace($value, $keys[$k] . ' = ', 6, 0) . ';';
            }
            $v = \strip_tags(\highlight_string($value, true), '<br><span>');
            $v = \strtr($v, ['&nbsp;' => ' ', '<br />' => "\n"]); // PHP < 8.3
            $v = \strtr($v, [">\n<" => '><']); // `<span style="…">\n<span style="…">` to `<span style="…"><span style="…">`
            $v = \trim(\strtr($v, ['&lt;?php ' => ""]));
            $v = '<span style="background:' . ($var ? '0 0' : '#ffe') . ';border:0 ' . (0 === $k ? 'solid' : 'dotted') . ' #000;border-top-width:1px;border-radius:0;box-shadow:none;color:#000;display:block;margin:0;overflow:auto;padding:.5em .75em;text-shadow:none;white-space:pre;">' . $v . '</span>';
            $content .= $v;
        }
        echo \x\test\_(true, \strtr($file, [\PATH . \D => '.' . \D]) . '(' . $at . '): test()', $content, ['#000', '#000', '#fff', '#ff9', '#000']);
    }
    if (\defined("\\TEST") && 'x.test' === \TEST && \is_file($test = __DIR__ . \D . 'test.php')) {
        require $test;
    }
}

namespace x\test {
    function _($open, $summary, $body, $color) {
        $id = \uniqid();
        return '<details aria-describedby="alert-content:' . $id . '" aria-labelledby="alert-title:' . $id . '" id="alert:' . $id . '" ' . ($open ? ' open' : "") . ' role="alert" style="background:' . $color[2] . ';border:2px solid ' . $color[1] . ';border-radius:0;box-shadow:none;color:' . $color[0] . ';font:100%/1.25 monospace;margin:2px 0;padding:0;text-shadow:none;"><summary id="alert-title:' . $id . '" style="background:' . $color[3] . ';border:0;border-radius:0;box-shadow:none;color:' . $color[4] . ';cursor:pointer;display:block;font:inherit;margin:0;padding:.5em .75em;text-shadow:none;user-select:none;">' . $summary . '</summary><div id="alert-content:' . $id . '">' . $body . '</div></details>';
    }
}