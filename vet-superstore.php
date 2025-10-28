<?php
/*
Plugin Name: Vet Superstore 
Description: Custom functionality for Vet Superstore (order meta, admin UI, SVG uploads, etc.)
Version: 1.0.0
Author: Vet Superstore Team
*/

// Only: Admin page with buttons to run custom PHP functions
add_action('admin_menu', function () {
    add_menu_page(
        'Vet Superstore Tools',
        'Vet Tools',
        'manage_options',
        'vet-superstore-tools',
        'vet_superstore_tools_page',
        'dashicons-admin-tools',
        56
    );
});

// Enqueue custom styles for the admin page
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_vet-superstore-tools') {
        wp_enqueue_style('vet-superstore-tools-style', plugin_dir_url(__FILE__) . 'css/vet-superstore-tools.css');
    }
});

function vet_superstore_tools_page()
{
?>
    <div class="wrap vet-superstore-tools-wrap">
        <h1><span class="dashicons dashicons-admin-tools"></span> Vet Superstore Tools</h1>
        <form method="post" class="vet-superstore-tools-form">
            <div class="vet-superstore-tools-buttons">
                <button name="run_health_check" class="button button-primary vet-btn-price">
                    <span class="dashicons dashicons-search"></span> Run Health Check
                </button>
                <button name="run_split_products" class="button button-primary vet-btn-price">
                    <span class="dashicons dashicons-products"></span> Split Products
                </button>
            </div>
        </form>
        <div class="vet-superstore-tools-output">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['run_health_check'])) {
                    vet_superstore_health_check();
                }

                if (isset($_POST['run_split_products'])) {
                    vet_superstore_split_products();
                }
            }
            ?>
        </div>
    </div>
<?php
}


function vet_superstore_health_check()
{
    $output = '';
    $base = dirname(__FILE__, 4);
    $path = $base . '/tasks/mwi/health-check.php';
    if (file_exists($path)) {
        ob_start();
        include $path;
        $output = ob_get_clean();
        echo '<div class="notice notice-success"><pre>' . esc_html($output) . '</pre></div>';
    } else {
        echo '<div class="notice notice-error"><p>health-check.php not found.</p></div>';
    }
}

function vet_superstore_split_products()
{
    $output = '';
    $base = dirname(__FILE__, 4);
    $path = $base . '/tasks/mwi/split-products.php';

    echo $path;

    if (file_exists($path)) {
        ob_start();
        require_once($path);
        $output = ob_get_clean();
        echo '<div class="notice notice-success"><pre>' . esc_html($output) . '</pre></div>';
    } else {
        echo '<div class="notice notice-error"><p>split-products.php not found.</p></div>';
    }
}
