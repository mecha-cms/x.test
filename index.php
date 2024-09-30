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
        $trace = \debug_backtrace()[0];
        $z = \implode("\n", \array_slice(\file($trace['file']), $trace['line'] - 1));
        foreach (\token_get_all((0 === \strpos($z, '<?php') ? "" : '<?php ') . $z) as $v) {
            if (\is_array($v)) {
                if (\T_CLOSE_TAG === $v[0]) {
                    break;
                }
                if (\T_CONSTANT_ENCAPSED_STRING === $v[0] || \T_DNUMBER === $v[0] || \T_LNUMBER === $v[0] || \T_STRING === $v[0]) {
                    $keys[] = $v[1];
                    continue;
                }
                if (\T_VARIABLE === $v[0]) {
                    $keys[] = $v[1];
                    continue;
                }
                continue;
            }
            if (';' === $v) {
                break;
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
        echo \x\test\_(true, strtr($trace['file'], [\PATH . \D => '.' . \D]) . '(' . $trace['line'] . '): test()', $content, ['#000', '#000', '#fff', '#ff9', '#000']);
    }
}

namespace x\test {
    function _($open, $summary, $body, $color) {
        return '<details' . ($open ? ' open' : "") . ' style="background:' . $color[2] . ';border:2px solid ' . $color[1] . ';border-radius:0;box-shadow:none;color:' . $color[0] . ';font:100%/1.25 monospace;margin:2px 0;padding:0;text-shadow:none;"><summary style="background:' . $color[3] . ';border:0;border-radius:0;box-shadow:none;color:' . $color[4] . ';cursor:pointer;display:block;font:inherit;margin:0;padding:.5em .75em;text-shadow:none;user-select:none;">' . $summary . '</summary>' . $body . '</details>';
    }
}