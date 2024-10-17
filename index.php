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
        echo \x\test\_(true, $alert, '<pre style="background:0 0;border:0 solid #000;border-top-width:1px;border-radius:0;box-shadow:none;color:inherit;display:flex;font:inherit;margin:0;padding:0;text-align:left;text-shadow:none;white-space:pre;"><span style="background:#eee;padding:.5em .75em;text-align:right;user-select:none;">' . \implode("\n", \range(1, \count($trace))) . '</span><span style="flex:1;overflow:auto;padding:.5em .75em;">' . \implode("\n", \array_reverse($trace)) . '</span></pre>', ['#000', '#000', '#fff', '#fc9', '#000']);
        $exit && exit;
    }
    function test(...$lot) {
        $content = "";
        $deep = 0;
        $key = 0;
        $keys = [];
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $trace_at = $trace['line'] ?? 0;
        $trace_file = $trace['file'] ?? "";
        $z = \implode("\n", \array_slice(\file($trace_file, \FILE_IGNORE_NEW_LINES), $trace_at - 1));
        $z = \preg_split('/\btest\s*\(/i', $z, 2)[1];
        foreach (\array_slice(\token_get_all('<?php ' . $z), 1) as $v) {
            $value = $keys[$key] ?? "";
            if (\is_array($v)) {
                if (\T_CLOSE_TAG === $v[0]) {
                    break; // End of function call
                }
                if (\T_COMMENT === $v[0] || \T_DOC_COMMENT === $v[0] || \T_WHITESPACE === $v[0]) {
                    continue;
                }
                $keys[$key] = $value . $v[1];
                continue;
            }
            if ('(' === $v) {
                $deep += 1;
            } else if (')' === $v) {
                $deep -= 1;
                if ($deep < 0) {
                    continue;
                }
            }
            if (',' === $v && $deep <= 0) {
                $key += 1;
                $value = $v = "";
            }
            if (';' === $v) {
                break; // End of function call
            }
            $keys[$key] = $value . $v;
        }
        foreach ($keys as $k => $v) {
            $v = \strtr($v, ['->' => ""]); // Ignore `->` from the pattern test
            if ('$' !== ($v[0] ?? 0) || \strlen($v) !== \strcspn($v, '%(*+,-:=?')) {
                $keys[$k] = null; // Do not display non-variable(s) and mixed variable(s)
            }
        }
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
            if ($var = $keys[$k] ?? 0) {
                $value = \substr_replace($value, $keys[$k] . ' = ', 6, 0) . ';';
            }
            $v = \strip_tags(\highlight_string($value, true), '<br><span>');
            $v = \strtr($v, ['&nbsp;' => ' ', '<br />' => "\n"]); // PHP < 8.3
            $v = \strtr($v, [">\n<" => '><']); // `<span style="…">\n<span style="…">` to `<span style="…"><span style="…">`
            $v = \trim(\strtr($v, ['&lt;?php ' => ""]));
            $v = '<span style="background:' . ($var ? '0 0' : '#ffe') . ';border:0 ' . (0 === $k ? 'solid' : 'dotted') . ' #000;border-top-width:1px;border-radius:0;box-shadow:none;color:#000;display:block;margin:0;overflow:auto;padding:.5em .75em;text-shadow:none;white-space:pre;">' . $v . '</span>';
            $content .= $v;
        }
        echo \x\test\_(true, \strtr($trace_file, [\PATH . \D => '.' . \D]) . '(' . $trace_at . '): test()', $content, ['#000', '#000', '#fff', '#ff9', '#000']);
    }
    if (\defined("\\TEST") && 'x.test' === \TEST && \is_file($test = __DIR__ . \D . 'test.php')) {
        require $test;
    }
}

namespace x\test {
    function _($open, $summary, $body, $color) {
        return '<details aria-describedby="alert-content:' . ($id = \uniqid()) . '" aria-labelledby="alert-title:' . $id . '" id="alert:' . $id . '" ' . ($open ? ' open' : "") . ' role="alert" style="background:' . $color[2] . ';border:2px solid ' . $color[1] . ';border-radius:0;box-shadow:0 1px 3px rgba(0,0,0,.5);color:' . $color[0] . ';font:100%/1.25 monospace;margin:1px 0;padding:0;text-align:left;text-shadow:none;"><summary id="alert-title:' . $id . '" style="background:' . $color[3] . ';border:0;border-radius:0;box-shadow:none;color:' . $color[4] . ';cursor:pointer;display:block;font:inherit;margin:0;padding:.5em .75em;text-shadow:none;user-select:none;">' . $summary . '</summary><div id="alert-content:' . $id . '">' . $body . '</div></details>';
    }
}