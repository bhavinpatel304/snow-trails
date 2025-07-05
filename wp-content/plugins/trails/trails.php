<?php
/**
 * Plugin Name: Trails
 * Description: Creates a custom database table for ski trails and provides a shortcode to display all trail records in a front‑end HTML table.
 * Version:     1.0.0
 * Author:      Bhavin Patel
 * License:     GPL‑2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Trails {

    /**
     * Full table name including WordPress prefix.
     *
     * @var string
     */
    private $table_name;

    /**
     * Constructor – set up hooks.
     */
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'trails';

        // Activation hook to create the table.
        register_activation_hook( __FILE__, [ $this, 'activate_plugin' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate_plugin' ] );

        // Front‑end shortcode for showing map.
        add_shortcode( 'trails_map', [ $this, 'render_trails_map' ] );
        add_action( 'wp_footer', [ $this, 'tooltip_show_js' ], 100  );

        //Edit form
        add_shortcode( 'edit_trails_form', [ $this,'render_trails_edit_form'] );

        //Save form by AJAX
        add_action('wp_ajax_save_trails', [ $this, 'handle_save_trails' ]);
        add_action('wp_ajax_nopriv_save_trails', [ $this, 'handle_save_trails' ]);
    }

    /**
     * Plugin activation 
     * create the DB table safely with dbDelta().
     * insert five trails data initially
     */
    public function activate_plugin() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            status varchar(7) NOT NULL DEFAULT 'closed',
            tooltip_side varchar(6) DEFAULT 'top',
            coordinates text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Insert initial data if table is empty
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        if ( $count == 0 ) {  
            $values = [
                $wpdb->prepare("( %s, %s, %s, %s )", 'Tilton Trail', 'closed', 'top', '910,847 840,866 734,940 730,980 830,910 890,870'),
                $wpdb->prepare("( %s, %s, %s, %s )", 'TinCan Alley', 'closed', 'top', '1177,595 1196,664 1167,765 1015,861 939,926 897,993 960,971 982,920 1057,869 1149,824 1193,784 1224,735 1246,662 1230,629'),
                $wpdb->prepare("( %s, %s, %s, %s )", 'Snowsnake', 'closed', 'top', '224,641 238,678 417,784 445,806 543,847 531,818 470,792 427,767 392,731 313,694 262,650'),
                $wpdb->prepare("( %s, %s, %s, %s )", 'Snowboat', 'closed', 'right', '655,708 661,733 665,774 673,814 661,830 647,802 641,769 640,733'),
                $wpdb->prepare("( %s, %s, %s, %s )", 'Shaken not Stirred', 'right', 'left', '1332,713 1336,751 1240,853 1128,912 977,946 993,923 1124,865 1240,824 1261,790 1303,733'),
            ];

            $sql = "
                INSERT INTO {$this->table_name} (name, status, tooltip_side, coordinates)
                VALUES " . implode(', ', $values);

            $wpdb->query($sql);
        }
    }

    public function deactivate_plugin() {
        global $wpdb;
        $wpdb->query( "DROP TABLE IF EXISTS {$this->table_name}" );
    }

    
    /**
     * Shortcode callback: outputs an Image and map all trails.
     * Usage: [trails_map]
     *
     * @return string HTML markup.
     */
    public function render_trails_map() {
        global $wpdb;

        $img_url = plugins_url( 'assets/sunshinestandish.jpg', __FILE__ );

        $trailsArray = $wpdb->get_results( "SELECT * FROM {$this->table_name} ORDER BY name ASC", ARRAY_A );

        if ( empty( $trailsArray ) ) {
            return '<p>No trails found.</p>';
        }
        
        ob_start();
        ?>
            <div class="container" style="padding-top:2rem">
            <div class="row h-100 justify-content-center align-items-center">
            <div class="col-10" style="max-width: 80%;">
                <div class="trail-wrapper">     
                    <div class="shape">           
                       <svg class="hs-poly-svg" viewBox="0 0 1439 1047" preserveAspectRatio="none">                               
                          
                        <?php foreach($trailsArray as $key => $trail){   ?>                                
                            <polygon id="trail-<?=$key?>"  data-bs-toggle="tooltip" 
                                data-bs-placement="<?=$trail["tooltip_side"]?>" 
                                data-bs-html="true"   
                                data-trail-name="<?=ucfirst($trail["name"])?>"                              
                                data-trail-status="<?=ucfirst($trail["status"])?>"
                                class="trail-poly trail-tooltip" data-index="0" 
                                points="<?=$trail["coordinates"]?>"></polygon>
                        <?php } ?>     
                        </svg></div>                    
                    <img src="<?=$img_url?>" style=" width: 100% !important; height: 100% !important;">
                 </div></div></div></div>
                 <?php
        return ob_get_clean();
    }

    public function tooltip_show_js() {
        

        // Make sure jQuery is enqueued
        wp_enqueue_script( 'jquery' );

        ?>
        <script>
            $(document).ready(function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                document.querySelectorAll('.trail-tooltip').forEach(btn => {

                    const mark = btn.dataset.trailStatus.toLowerCase() == "open" ? '<i class="bi bi-check text-success"></i>' : '<i class="bi bi-x text-danger"></i>';

                    const htmlContent = btn.dataset.trailName+ "<br/>"+mark+" "+btn.dataset.trailStatus;
                    new bootstrap.Tooltip(btn, {
                        title: htmlContent,
                        html: true
                    });
                });

            });
            

            
        </script>
        <?php
    }


    /**
     * Shortcode callback: render HTML Form.
     * Usage: [edit_trails_form]
     */
    public function render_trails_edit_form() {
        global $wpdb;
        $table = $wpdb->prefix . 'trails';
        $rows = $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC", ARRAY_A );

        ob_start();
        ?>
        <div id="alert-wrapper">
            <div class="alert alert-primary alert-dismissible fade" role="alert">
                <div id="trails-form-message"></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <form id="trails-edit-form" method="post">
            <?php wp_nonce_field( 'save_trails_action', 'trails_nonce' ); ?>

            <table class="table table-bordered">
                <thead>
                    <tr><th>Name</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php foreach ( $rows as $row ) : ?>
                    <tr>
                        <td>
                            <?=esc_attr($row['name'])?> 
                        </td>
                        <td>
                            <select name="trail_status[<?php echo $row['id']; ?>]" class="form-select">
                                <option value="open" <?php selected($row['status'], 'open'); ?>>Open</option>
                                <option value="closed" <?php selected($row['status'], 'closed'); ?>>Closed</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save Trails</button>
        </form>

        <script>
            document.getElementById('trails-edit-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(document.getElementById('trails-edit-form'));
                // var inputs = document.querySelectorAll("#trails-edit-form select");
                formData.append('action', 'save_trails');
                formData.append('trails_nonce', document.querySelector('[name="trails_nonce"]').value);
                // console.log(formData, form);
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(resp => resp.json())
                .then(data => {
                    if(document.getElementById('trails-form-message') == null)
                    {
                        const aw = document.getElementById("alert-wrapper");
                        aw.innerHTML = `<div class="alert alert-primary alert-dismissible fade" role="alert">
                            <div id="trails-form-message"></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    }
                    document.getElementById('trails-form-message').innerText = data.data.message ?? '';
                    $('.alert').addClass('show');
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_save_trails() {
        if ( ! isset($_POST['trails_nonce']) || ! wp_verify_nonce($_POST['trails_nonce'], 'save_trails_action') ) {
            wp_send_json_error(['message' => 'Invalid nonce.']);
        }

        global $wpdb;
        $table = $wpdb->prefix . 'trails';
        $statuses = $_POST['trail_status'] ?? [];

        foreach ( $statuses as $k => $v ) {
            $status = $statuses[$k] ?? 'closed';
            $wpdb->update(
                $table,
                [
                    'status' => sanitize_text_field($status),
                ],
                [ 'id' => intval($k) ]
            );
        }
        wp_send_json_success(['message' => 'Trails updated successfully.']);
    }

}

new Trails();