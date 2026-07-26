<?php
/**
 * Plugin Name: 标签打印工具 Label Tool
 * Description: 嵌入标签设计与打印工具；把 PDF 解析／条码解码服务反向代理到 VPS 回环端口 8632（不暴露公网），支持公开或登录访问。打印仍走操作员本机的打印服务。
 * Version:     1.0.38
 * Author:      mrocioa
 * Text Domain: labeltool
 */

if (!defined('ABSPATH')) exit;

define('LABELTOOL_VER', '1.0.38');
define('LABELTOOL_DIR', plugin_dir_path(__FILE__));
define('LABELTOOL_URL', plugin_dir_url(__FILE__));

/* ────────────────────────────────────────────────
   设置项
   ──────────────────────────────────────────────── */

function labeltool_opt($key, $default = '') {
    $o = get_option('labeltool_options', array());
    return isset($o[$key]) && $o[$key] !== '' ? $o[$key] : $default;
}

/** 解码服务内部地址。默认本机 8632 —— 它不需要、也不应该监听公网。 */
function labeltool_decode_base() {
    return rtrim(labeltool_opt('decode_base', 'http://127.0.0.1:8632'), '/');
}

/** 使用工具所需能力。默认 read（任何已登录用户）。 */
function labeltool_cap() {
    return labeltool_opt('cap', 'read');
}

/** 公开模式允许访客使用；login 模式恢复为仅登录账号可用。 */
function labeltool_access_mode() {
    $mode = labeltool_opt('access_mode', 'login');
    return in_array($mode, array('public', 'login'), true) ? $mode : 'login';
}

function labeltool_public_access() {
    return labeltool_access_mode() === 'public';
}

add_action('admin_menu', function () {
    add_options_page('标签工具', '标签工具', 'manage_options', 'labeltool', 'labeltool_settings_page');
});

add_action('admin_init', function () {
    register_setting('labeltool', 'labeltool_options', array(
        'sanitize_callback' => function ($in) {
            $access_mode = sanitize_key($in['access_mode'] ?? 'login');
            return array(
                'decode_base' => esc_url_raw(trim($in['decode_base'] ?? '')),
                'cap'         => sanitize_key($in['cap'] ?? 'read'),
                'access_mode' => in_array($access_mode, array('public', 'login'), true) ? $access_mode : 'login',
            );
        },
    ));
});

function labeltool_settings_page() {
    if (!current_user_can('manage_options')) return;
    $base = labeltool_decode_base();
    // 探活：管理员打开设置页时直接看到解码服务是否在线
    $probe = wp_remote_get($base . '/status', array('timeout' => 4));
    $ok    = !is_wp_error($probe) && wp_remote_retrieve_response_code($probe) === 200;
    $info  = $ok ? wp_remote_retrieve_body($probe) : (is_wp_error($probe) ? $probe->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code($probe));
    ?>
    <div class="wrap">
        <h1>标签工具</h1>
        <p style="max-width:46em">
            解码服务（PDF 解析／条码识别）是纯计算，跑在本服务器上，由本插件反向代理，<strong>无需监听公网端口</strong>。<br>
            打印服务必须运行在插着打印机的那台电脑上 —— 服务器摸不到 USB 打印机，这部分无法集中部署。
        </p>

        <h2>解码服务状态</h2>
        <p>
            <code><?php echo esc_html($base); ?></code>
            <span style="margin-left:8px;padding:2px 8px;border-radius:2px;font-weight:600;color:#fff;background:<?php echo $ok ? '#1a7f37' : '#b32d2e'; ?>">
                <?php echo $ok ? 'ONLINE' : 'OFFLINE'; ?>
            </span>
        </p>
        <?php if (!$ok) : ?>
            <p style="color:#b32d2e">无法连接。SSH 登录服务器执行 <code>systemctl status labeltool-decode</code> 检查服务。</p>
        <?php else : ?>
            <p><code style="font-size:11px"><?php echo esc_html(mb_substr($info, 0, 300)); ?></code></p>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields('labeltool'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="lt_base">解码服务地址</label></th>
                    <td>
                        <input name="labeltool_options[decode_base]" id="lt_base" type="text" class="regular-text code"
                               value="<?php echo esc_attr(labeltool_opt('decode_base', 'http://127.0.0.1:8632')); ?>">
                        <p class="description">保持 <code>http://127.0.0.1:8632</code> 即可。仅当解码服务在另一台内网机器上时才改。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="lt_access_mode">访问方式</label></th>
                    <td>
                        <select name="labeltool_options[access_mode]" id="lt_access_mode">
                            <option value="public" <?php selected(labeltool_access_mode(), 'public'); ?>>公开使用（无需注册或登录）</option>
                            <option value="login" <?php selected(labeltool_access_mode(), 'login'); ?>>需要登录</option>
                        </select>
                        <p class="description">可随时切换；改为“需要登录”后，已经签发给访客的服务 token 会立即失效。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="lt_cap">访问权限</label></th>
                    <td>
                        <select name="labeltool_options[cap]" id="lt_cap">
                            <?php foreach (array(
                                'read'           => '任何已登录用户',
                                'edit_posts'     => '作者及以上 (edit_posts)',
                                'edit_others_posts' => '编辑及以上 (edit_others_posts)',
                                'manage_options' => '仅管理员 (manage_options)',
                            ) as $k => $label) : ?>
                                <option value="<?php echo esc_attr($k); ?>" <?php selected(labeltool_cap(), $k); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">未登录用户一律看不到工具，也拿不到解码代理的 token。</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <h2>用法</h2>
        <p>新建一个页面，放入短代码：<code>[label_tool]</code>。建议该页面使用全宽／空白模板。</p>
        <p>可选参数：<code>[label_tool height="calc(100vh - 32px)"]</code></p>

        <h2>操作员本机打印服务</h2>
        <p>每台接打印机的电脑装一次。把安装包发给操作员：</p>
        <p>
            <a class="button button-primary"
               href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=labeltool_agent'), 'labeltool_agent')); ?>">
                下载打印服务安装包 (.zip)
            </a>
        </p>
    </div>
    <?php
}

/* ────────────────────────────────────────────────
   签名 token
   token 塞在代理地址的路径里，前端所有 fetch 只需拼 base，
   调用点一行都不用改。
   ──────────────────────────────────────────────── */

function labeltool_token($ttl = 28800) {
    $uid = get_current_user_id();
    // 公开页面会被 CDN 缓存；访客 token 在同一发布版本内保持稳定，
    // 新版本会换一条签名路径，切回 login 时则立即拒绝 uid=0。
    $guest_epoch = max(1, (int) preg_replace('/\D+/', '', LABELTOOL_VER));
    $exp = ($uid === 0 && labeltool_public_access()) ? $guest_epoch : time() + $ttl;
    $sig = hash_hmac('sha256', $uid . '.' . $exp, wp_salt('labeltool_svc'));
    return $uid . '.' . $exp . '.' . substr($sig, 0, 32);
}

function labeltool_token_valid($token) {
    $p = explode('.', (string) $token);
    if (count($p) !== 3) return false;
    list($uid, $exp, $sig) = $p;
    if (!ctype_digit($uid) || !ctype_digit($exp)) return false;
    if ((int) $uid !== 0 && time() > (int) $exp) return false;
    $want = substr(hash_hmac('sha256', $uid . '.' . $exp, wp_salt('labeltool_svc')), 0, 32);
    if (!hash_equals($want, $sig)) return false;
    if ((int) $uid === 0) return labeltool_public_access();
    if ((int) $uid < 0) return false;
    return user_can((int) $uid, labeltool_cap());
}

/* ────────────────────────────────────────────────
   解码服务反向代理
   同源 + 同 HTTPS ⇒ 无 CORS、无混合内容拦截
   ──────────────────────────────────────────────── */

add_action('rest_api_init', function () {
    register_rest_route('labeltool/v1', '/svc/(?P<token>[^/]+)/(?P<ep>[A-Za-z0-9_-]+)', array(
        'methods'             => array('GET', 'POST'),
        'permission_callback' => function ($req) {
            return labeltool_token_valid($req['token']);
        },
        'callback'            => 'labeltool_proxy',
    ));
});

function labeltool_proxy(WP_REST_Request $req) {
    $ep    = $req['ep'];
    $allow = array('status', 'pdf-to-label', 'pdf-to-svg', 'decode', 'rebuild');
    if (!in_array($ep, $allow, true)) {
        return new WP_REST_Response(array('ok' => false, 'error' => 'endpoint not allowed'), 404);
    }

    $qs = $req->get_query_params();
    unset($qs['token'], $qs['ep'], $qs['rest_route'], $qs['_locale']);
    $url = labeltool_decode_base() . '/' . $ep . ($qs ? '?' . http_build_query($qs) : '');

    // WordPress 会先解析 multipart/form-data，因此 get_body() 对文件上传可能为空。
    // 用 CURLFile 从 REST 请求的临时上传文件重建 multipart，确保 PDF/图片正文
    // 真正送到 VPS 回环服务；此分支覆盖 pdf-to-label、pdf-to-svg、decode、rebuild。
    $files = $req->get_file_params();
    if ($req->get_method() === 'POST' && $files) {
        if (!function_exists('curl_init') || !class_exists('CURLFile')) {
            return new WP_REST_Response(array('ok' => false, 'error' => '服务器缺少 cURL 文件转发支持'), 500);
        }

        $fields = $req->get_body_params();
        foreach ($files as $field => $file) {
            if (!is_array($file) || empty($file['tmp_name'])) continue;
            if (isset($file['error']) && (int) $file['error'] !== UPLOAD_ERR_OK) {
                return new WP_REST_Response(array('ok' => false, 'error' => '上传文件无效'), 400);
            }
            $fields[$field] = new CURLFile(
                $file['tmp_name'],
                !empty($file['type']) ? $file['type'] : 'application/octet-stream',
                !empty($file['name']) ? $file['name'] : 'upload.bin'
            );
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_HTTPHEADER     => array('Accept: application/json'),
        ));
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return new WP_REST_Response(array('ok' => false, 'error' => '解码服务不可达: ' . $err), 502);
        }

        status_header($code > 0 ? $code : 502);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo $body;
        exit;
    }

    $args = array(
        'method'  => $req->get_method(),
        'timeout' => 120,
        'headers' => array('Content-Type' => $req->get_header('content-type') ?: 'application/json'),
    );
    if ($req->get_method() === 'POST') {
        $args['body'] = $req->get_body();
    }

    $r = wp_remote_request($url, $args);
    if (is_wp_error($r)) {
        return new WP_REST_Response(array('ok' => false, 'error' => '解码服务不可达: ' . $r->get_error_message()), 502);
    }

    // 原样透传，避免二次 JSON 编解码放大内存
    status_header(wp_remote_retrieve_response_code($r));
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo wp_remote_retrieve_body($r);
    exit;
}

/* ────────────────────────────────────────────────
   短代码：全幅 iframe
   用 iframe 隔离，主题的全局 CSS 碰不到工具，
   打印样式也不会被污染（这是标签工具的硬要求）。
   ──────────────────────────────────────────────── */

add_shortcode('label_tool', function ($atts) {
    $a = shortcode_atts(array(
        'height' => 'calc(100vh - 32px)',
        'min'    => '720px',
    ), $atts, 'label_tool');

    if (!labeltool_public_access() && !is_user_logged_in()) {
        return '<div style="border:1px solid #23304a;background:#0b0e14;color:#7c96a4;font:12px/1.7 ui-monospace,monospace;padding:16px">'
             . '// 需要登录后使用 &nbsp;<a href="' . esc_url(wp_login_url(get_permalink())) . '" style="color:#00e5ff">登录</a>'
             . '</div>';
    }
    if (!labeltool_public_access() && !current_user_can(labeltool_cap())) {
        return '<div style="border:1px solid #8c6a24;background:#0b0e14;color:#ffb300;font:12px/1.7 ui-monospace,monospace;padding:16px">'
             . '// 当前账号无权使用标签工具' . '</div>';
    }

    $svc = rest_url('labeltool/v1/svc/' . labeltool_token());
    $src = add_query_arg(array('svc' => $svc, 'v' => LABELTOOL_VER), LABELTOOL_URL . 'app/index.html');

    return sprintf(
        '<div style="width:100%%;height:%s;min-height:%s;background:#060a0e">'
      . '<iframe src="%s" title="标签打印工具" allow="serial; usb" '
      . 'style="width:100%%;height:100%%;border:0;display:block"></iframe></div>',
        esc_attr($a['height']), esc_attr($a['min']), esc_url($src)
    );
});

/* ────────────────────────────────────────────────
   打印服务安装包下载（按需打 zip，无需仓库里存二进制）
   ──────────────────────────────────────────────── */

add_action('admin_post_labeltool_agent', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('labeltool_agent')) {
        wp_die('无权下载');
    }
    if (!class_exists('ZipArchive')) {
        wp_die('服务器缺少 PHP ZipArchive 扩展，请手工打包 wp-content/plugins/labeltool/agent/ 目录');
    }

    $dir = LABELTOOL_DIR . 'agent';
    if (!is_dir($dir)) wp_die('缺少 agent/ 目录');

    $tmp = wp_tempnam('labeltool-agent') . '.zip';
    $zip = new ZipArchive();
    $zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->isFile()) {
            $zip->addFile($f->getRealPath(), '标签打印服务/' . substr($f->getRealPath(), strlen($dir) + 1));
        }
    }
    $zip->close();

    nocache_headers();
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="labeltool-print-service-' . LABELTOOL_VER . '.zip"');
    header('Content-Length: ' . filesize($tmp));
    readfile($tmp);
    @unlink($tmp);
    exit;
});
