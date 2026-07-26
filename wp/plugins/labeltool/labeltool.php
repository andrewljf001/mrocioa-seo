<?php
/**
 * Plugin Name: 标签打印工具 Label Tool
 * Description: 嵌入标签设计与打印工具；把 PDF 解析／条码解码服务反向代理到 VPS 回环端口 8632（不暴露公网），支持公开或登录访问。打印仍走操作员本机的打印服务。
 * Version:     1.0.39
 * Author:      mrocioa
 * Text Domain: labeltool
 */

if (!defined('ABSPATH')) exit;

define('LABELTOOL_VER', '1.0.39');
define('LABELTOOL_DIR', plugin_dir_path(__FILE__));
define('LABELTOOL_URL', plugin_dir_url(__FILE__));
define('LABELTOOL_TEMPLATE_POST_TYPE', 'labeltool_template');

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

/* ────────────────────────────────────────────────
   在线模板
   管理员在编辑器内发布，访客只读并载入浏览器副本。
   ──────────────────────────────────────────────── */

add_action('init', function () {
    $caps = array(
        'edit_post'              => 'manage_options',
        'read_post'              => 'manage_options',
        'delete_post'            => 'manage_options',
        'edit_posts'             => 'manage_options',
        'edit_others_posts'      => 'manage_options',
        'publish_posts'          => 'manage_options',
        'read_private_posts'     => 'manage_options',
        'delete_posts'           => 'manage_options',
        'delete_private_posts'   => 'manage_options',
        'delete_published_posts' => 'manage_options',
        'delete_others_posts'    => 'manage_options',
        'edit_private_posts'     => 'manage_options',
        'edit_published_posts'   => 'manage_options',
        'create_posts'           => 'do_not_allow',
    );

    register_post_type(LABELTOOL_TEMPLATE_POST_TYPE, array(
        'labels' => array(
            'name'          => '在线标签模板',
            'singular_name' => '在线标签模板',
            'menu_name'     => '在线标签模板',
            'add_new_item'  => '新增在线标签模板',
            'edit_item'     => '编辑在线标签模板',
            'search_items'  => '搜索在线标签模板',
            'not_found'     => '尚无在线标签模板',
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'tools.php',
        'show_in_rest'        => false,
        'exclude_from_search' => true,
        'query_var'           => false,
        'rewrite'             => false,
        'supports'            => array('title'),
        'capabilities'        => $caps,
        'map_meta_cap'        => false,
    ));
});

add_filter('manage_' . LABELTOOL_TEMPLATE_POST_TYPE . '_posts_columns', function ($columns) {
    return array(
        'cb'             => $columns['cb'],
        'title'          => '模板名称',
        'labeltool_size' => '标签尺寸',
        'labeltool_els'  => '元素',
        'labeltool_link' => '分享链接',
        'date'           => $columns['date'],
    );
});

add_action('manage_' . LABELTOOL_TEMPLATE_POST_TYPE . '_posts_custom_column', function ($column, $post_id) {
    if ($column === 'labeltool_size') {
        echo esc_html(get_post_meta($post_id, '_labeltool_size', true));
    } elseif ($column === 'labeltool_els') {
        echo esc_html((string) get_post_meta($post_id, '_labeltool_elements', true));
    } elseif ($column === 'labeltool_link') {
        $page = get_page_by_path('label-printing-tool');
        if ($page) {
            $url = add_query_arg('template', $post_id, get_permalink($page));
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">打开模板</a>';
        } else {
            echo '<span style="color:#777">缺少 label-printing-tool 页面</span>';
        }
    }
}, 10, 2);

function labeltool_template_error($message, $status = 400) {
    return new WP_Error('labeltool_template_invalid', $message, array('status' => $status));
}

function labeltool_template_validate($template) {
    if (!is_array($template)) {
        return labeltool_template_error('模板正文必须是 JSON 对象');
    }

    $name = sanitize_text_field((string) ($template['name'] ?? ''));
    $name = trim(mb_substr($name, 0, 120));
    $width = isset($template['w']) && is_numeric($template['w']) ? (float) $template['w'] : 0;
    $height = isset($template['h']) && is_numeric($template['h']) ? (float) $template['h'] : 0;
    $elements = $template['els'] ?? null;

    if ($name === '') return labeltool_template_error('请先为模板命名');
    if ($width <= 0 || $width > 500 || $height <= 0 || $height > 500) {
        return labeltool_template_error('标签尺寸无效');
    }
    if (!is_array($elements) || count($elements) > 500) {
        return labeltool_template_error('模板元素无效或超过 500 个');
    }

    foreach ($elements as $element) {
        if (!is_array($element) || empty($element['type']) || !is_string($element['type'])) {
            return labeltool_template_error('模板包含无效元素');
        }
        if (isset($element['_img'])) {
            if (!is_array($element['_img']) || empty($element['_img']['uri']) || !is_string($element['_img']['uri'])) {
                return labeltool_template_error('模板图片数据无效');
            }
            $uri = $element['_img']['uri'];
            if (strlen($uri) > 3 * 1024 * 1024 ||
                !preg_match('#^data:image/(?:png|jpe?g|webp|gif|svg\+xml);base64,#i', $uri)) {
                return labeltool_template_error('在线模板图片必须是 3 MB 以内的内嵌图片');
            }
        }
    }

    $template['version'] = max(1, (int) ($template['version'] ?? 1));
    $template['name'] = $name;
    $template['w'] = round($width, 2);
    $template['h'] = round($height, 2);
    $template['qty'] = max(1, min(500, (int) ($template['qty'] ?? 1)));
    $template['savedAt'] = current_time('Y-m-d');

    $encoded = wp_json_encode($template, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($encoded === false || strlen($encoded) > 4 * 1024 * 1024) {
        return labeltool_template_error('在线模板不能超过 4 MB');
    }

    return array('template' => $template, 'json' => $encoded);
}

function labeltool_template_summary($post) {
    return array(
        'id'       => (int) $post->ID,
        'name'     => get_the_title($post),
        'size'     => (string) get_post_meta($post->ID, '_labeltool_size', true),
        'elements' => (int) get_post_meta($post->ID, '_labeltool_elements', true),
        'modified' => mysql_to_rfc3339($post->post_modified_gmt ?: $post->post_date_gmt),
        'url'      => rest_url('labeltool/v1/templates/' . $post->ID),
    );
}

function labeltool_template_list() {
    $posts = get_posts(array(
        'post_type'      => LABELTOOL_TEMPLATE_POST_TYPE,
        'post_status'    => 'publish',
        'posts_per_page' => 100,
        'orderby'        => array('menu_order' => 'ASC', 'modified' => 'DESC'),
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ));
    $response = new WP_REST_Response(array(
        'ok'        => true,
        'templates' => array_map('labeltool_template_summary', $posts),
    ));
    $response->header('Cache-Control', 'no-store, max-age=0');
    return $response;
}

function labeltool_template_session_ajax() {
    $can_manage = current_user_can('manage_options');
    nocache_headers();
    header('Vary: Cookie');
    wp_send_json(array(
        'ok'         => true,
        'can_manage' => $can_manage,
        'nonce'      => $can_manage ? wp_create_nonce('wp_rest') : '',
    ));
}

add_action('wp_ajax_labeltool_template_session', 'labeltool_template_session_ajax');
add_action('wp_ajax_nopriv_labeltool_template_session', 'labeltool_template_session_ajax');

function labeltool_template_get(WP_REST_Request $req) {
    $post = get_post((int) $req['id']);
    if (!$post || $post->post_type !== LABELTOOL_TEMPLATE_POST_TYPE || $post->post_status !== 'publish') {
        return labeltool_template_error('在线模板不存在', 404);
    }

    $template = json_decode($post->post_content, true);
    if (!is_array($template)) {
        return labeltool_template_error('在线模板数据损坏', 500);
    }
    $template['name'] = get_the_title($post);

    $response = new WP_REST_Response(array(
        'ok'       => true,
        'item'     => labeltool_template_summary($post),
        'template' => $template,
    ));
    $response->header('Cache-Control', 'no-store, max-age=0');
    return $response;
}

function labeltool_template_save(WP_REST_Request $req) {
    $body = $req->get_json_params();
    $valid = labeltool_template_validate($body['template'] ?? null);
    if (is_wp_error($valid)) return $valid;

    $template = $valid['template'];
    $post_id = isset($body['id']) ? absint($body['id']) : 0;
    $existing = $post_id ? get_post($post_id) : null;
    if (!$existing || $existing->post_type !== LABELTOOL_TEMPLATE_POST_TYPE) {
        $post_id = 0;
        $slug = sanitize_title($template['name']);
        $by_slug = $slug ? get_page_by_path($slug, OBJECT, LABELTOOL_TEMPLATE_POST_TYPE) : null;
        if ($by_slug) $post_id = (int) $by_slug->ID;
    }

    $post_data = array(
        'post_type'    => LABELTOOL_TEMPLATE_POST_TYPE,
        'post_status'  => 'publish',
        'post_title'   => $template['name'],
        'post_name'    => sanitize_title($template['name']),
        'post_content' => wp_slash($valid['json']),
    );
    if ($post_id) $post_data['ID'] = $post_id;

    $saved_id = wp_insert_post($post_data, true);
    if (is_wp_error($saved_id)) {
        return labeltool_template_error('在线模板保存失败：' . $saved_id->get_error_message(), 500);
    }

    update_post_meta($saved_id, '_labeltool_size', $template['w'] . '×' . $template['h'] . 'mm');
    update_post_meta($saved_id, '_labeltool_elements', count($template['els']));

    $post = get_post($saved_id);
    $response = new WP_REST_Response(array(
        'ok'      => true,
        'created' => !$post_id,
        'item'    => labeltool_template_summary($post),
    ), $post_id ? 200 : 201);
    $response->header('Cache-Control', 'no-store, max-age=0');
    return $response;
}

function labeltool_template_delete(WP_REST_Request $req) {
    $post = get_post((int) $req['id']);
    if (!$post || $post->post_type !== LABELTOOL_TEMPLATE_POST_TYPE) {
        return labeltool_template_error('在线模板不存在', 404);
    }
    $deleted = wp_trash_post($post->ID);
    if (!$deleted) return labeltool_template_error('在线模板删除失败', 500);
    return new WP_REST_Response(array('ok' => true, 'id' => (int) $post->ID));
}

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

        <h2>在线模板</h2>
        <p>管理员登录后，标签编辑器会额外显示“保存在线模板”。访客可在左侧载入已发布模板的副本，但不能修改服务器原版。</p>
        <p><a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=' . LABELTOOL_TEMPLATE_POST_TYPE)); ?>">管理在线标签模板</a></p>

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

    register_rest_route('labeltool/v1', '/templates', array(
        array(
            'methods'             => 'GET',
            'permission_callback' => '__return_true',
            'callback'            => 'labeltool_template_list',
        ),
        array(
            'methods'             => 'POST',
            'permission_callback' => function () {
                return current_user_can('manage_options');
            },
            'callback'            => 'labeltool_template_save',
        ),
    ));

    register_rest_route('labeltool/v1', '/templates/(?P<id>\d+)', array(
        array(
            'methods'             => 'GET',
            'permission_callback' => '__return_true',
            'callback'            => 'labeltool_template_get',
        ),
        array(
            'methods'             => 'DELETE',
            'permission_callback' => function () {
                return current_user_can('manage_options');
            },
            'callback'            => 'labeltool_template_delete',
        ),
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
    $src_args = array(
        'svc'         => $svc,
        'tpl_api'     => rest_url('labeltool/v1/templates'),
        'tpl_session' => admin_url('admin-ajax.php?action=labeltool_template_session'),
        'tpl_share'   => get_permalink(),
        'v'           => LABELTOOL_VER,
    );
    if (isset($_GET['template']) && ctype_digit((string) $_GET['template'])) {
        $src_args['template'] = (string) absint($_GET['template']);
    }
    $src = add_query_arg($src_args, LABELTOOL_URL . 'app/index.html');

    return sprintf(
        '<div style="width:100%%;height:%s;min-height:%s;background:#060a0e">'
      . '<iframe src="%s" title="标签打印工具" allow="serial; usb; clipboard-write" '
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
