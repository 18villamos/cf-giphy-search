<?php
class BartokWorks {
    public function __construct() {
        add_action( 'init', array( $this, 'register_work_post_type' ), 0 );
        add_action( 'init', array( $this, 'register_work_taxonomy'), 0 );
        add_action( 'admin_menu', array( $this, 'remove_custom_meta'), 0 );

        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }
    }

    function register_work_post_type() {

      $labels = array(
          'name'                => _x( 'Works', 'Post Type General Name', 'text_domain' ),
          'singular_name'       => _x( 'Work', 'Post Type Singular Name', 'text_domain' ),
          'menu_name'           => __( 'Works', 'text_domain' ),
          'name_admin_bar'      => __( 'Work', 'text_domain' ),
          'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
          'all_items'           => __( 'All Works', 'text_domain' ),
          'add_new_item'        => __( 'Add New Work', 'text_domain' ),
          'add_new'             => __( 'Add New', 'text_domain' ),
          'new_item'            => __( 'New work', 'text_domain' ),
          'edit_item'           => __( 'Edit Work', 'text_domain' ),
          'update_item'         => __( 'Update Work', 'text_domain' ),
          'view_item'           => __( 'View Work', 'text_domain' ),
          'search_items'        => __( 'Search Works', 'text_domain' ),
          'not_found'           => __( 'Not found', 'text_domain' ),
          'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
      );
      $rewrite = array(
          'slug'                => 'work',
          'with_front'          => true,
          'pages'               => true,
          'feeds'               => true,
      );
      $args = array(
          'label'               => __( 'works', 'text_domain' ),
          'description'         => __( 'Your compositions', 'text_domain' ),
          'labels'              => $labels,
          'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes'),
          'hierarchical'        => false,
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'menu_position'       => 5,
          'menu_icon'           => 'dashicons-format-audio',
          'show_in_admin_bar'   => true,
          'show_in_nav_menus'   => true,
          'can_export'          => true,
          'has_archive'         => true,
          'exclude_from_search' => false,
          'publicly_queryable'  => true,
          'rewrite'             => $rewrite,
          'capability_type'     => 'page',
      );
      register_post_type( 'works', $args );
    }

    function register_work_taxonomy() {

      $labels = array(
        'name'                       => _x( 'Work Categories', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Work Category', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Work Categories', 'text_domain' ),
        'all_items'                  => __( 'All categories', 'text_domain' ),
        'parent_item'                => __( 'Parent category', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent category:', 'text_domain' ),
        'new_item_name'              => __( 'New Category Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Category', 'text_domain' ),
        'edit_item'                  => __( 'Edit Category', 'text_domain' ),
        'update_item'                => __( 'Update Category', 'text_domain' ),
        'view_item'                  => __( 'View Category', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
        'popular_items'              => __( 'Popular categories', 'text_domain' ),
        'search_items'               => __( 'Search categories', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
      );

      $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
      );
      register_taxonomy( 'work_category', array( 'works' ), $args );

      //create these few starter categories
      wp_insert_term( "Choral", "work_category");
      wp_insert_term( "Chamber Music", "work_category");
      wp_insert_term( "Orchestra", "work_category");
      wp_insert_term( "Solo Voice", "work_category");
    }

    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }

    public function add_metabox() {
        add_meta_box(
            'wp-bartok-work-about-this-piece',
            __( 'About this Piece', 'wp-bartok-work' ),
            array($this,'render_metabox_html'),
            'works',
            'normal',
            'high'
        );
    }

    private function render_fields() {

            $fields = array(
                array('Instrumentation',    'instrumentation',    'text'),
                array('Duration',           'duration',           'text'),
                array('Premiere Year',      'premiere_date',      'text'),
                array('Premiere Location',  'premiere_location',  'text'),
                array('SoundCloud URL',     'soundcloud_url',     'text'),
                array('Issuu URL',          'issuu_url',          'text'),
                array('Buy Recording URL',  'recording_url',      'text'),
                array('Recording Thumbnail','recording_thumbnail','text'),
                array('Buy Score URL',      'buy_score_url',      'text'),
                array('Buy Score Thumbnail','buy_score_thumbnail','text'),
                array('Video URL',          'video_url',          'text'),
                array('Press Quote',        'press_quote',        'textarea'),
                array('Quote Author',       'quote_author',       'text')
            );

            return $fields;
    }

    public function render_metabox_html( $post) {
        wp_nonce_field( '_wp_bartok_work_nonce', 'wp_bartok_work_nonce' );

        $fields_html    = "";
        $fields_array   = $this->render_fields();

        foreach($fields_array as $i=>$this_field) {

            $label          = $fields_array[$i][0];
            $slug           = $fields_array[$i][1];
            $type           = $fields_array[$i][2];

            $name_id        = 'wp_bartok_work_' . $slug;
            $value          = $this->get_meta( 'wp_bartok_work_' . $slug );
            $editor_content = html_entity_decode(stripcslashes($this->get_meta('wp_bartok_work_program_note')));

            $fields_html    .=  '<div class="bartok-admin-grid ">';
            $fields_html    .=  '    <label for ="wp_bartok_work_' . $slug . '">' . $label . '</label><br/>';
            $fields_html    .=  '    <input type="' . $type . '" name="' . $name_id . '" id="' . $name_id . '" value="' . $value . '" />';
            $fields_html    .=  '</div>';
        }

        echo '<h3>Supply Details About This Work</h3>';
        echo '<div class="bartok-admin-wrapper">';
        echo $fields_html;
        wp_editor($editor_content, "wp_bartok_work_program_note", array('textarea_name'=>'wp_bartok_work_program_note', 'wpautop'=>'false'));
        echo '<div style="clear: both;"></div>';
        echo '</div>';
    }


    public function save_metabox( $post_id ) {
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['wp_bartok_work_nonce'] ) || ! wp_verify_nonce( $_POST['wp_bartok_work_nonce'], '_wp_bartok_work_nonce' ) ) return;
        if ( ! current_user_can( 'edit_post' ) ) return;

        foreach($this->render_fields as $i=>$this_field) {
            $slug = $render_fields[$i][1];

            if ( isset( $_POST['wp_bartok_work_' . $slug] ) )

                update_post_meta( $post_id, 'wp_bartok_work_' . $slug, esc_attr( $_POST['wp_bartok_work_' . $slug] ) );

        }

        // if ( isset( $_POST['wp_bartok_work_instrumentation'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_instrumentation', esc_attr( $_POST['wp_bartok_work_instrumentation'] ) );
        // if ( isset( $_POST['wp_bartok_work_duration'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_duration', esc_attr( $_POST['wp_bartok_work_duration'] ) );
        // if ( isset( $_POST['wp_bartok_work_premiere_date'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_premiere_date', esc_attr( $_POST['wp_bartok_work_premiere_date'] ) );
        // if ( isset( $_POST['wp_bartok_work_premiere_location'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_premiere_location', esc_attr( $_POST['wp_bartok_work_premiere_location'] ) );
        // if ( isset( $_POST['wp_bartok_work_soundcloud_url'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_soundcloud_url', esc_attr( $_POST['wp_bartok_work_soundcloud_url'] ) );
        // if ( isset( $_POST['wp_bartok_work_issuu_url'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_issuu_url', esc_attr( $_POST['wp_bartok_work_issuu_url'] ) );
        // if ( isset( $_POST['wp_bartok_work_recording_url'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_recording_url', esc_attr( $_POST['wp_bartok_work_recording_url'] ) );
        // if ( isset( $_POST['wp_bartok_work_recording_thumbnail'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_recording_thumbnail', esc_attr( $_POST['wp_bartok_work_recording_thumbnail'] ) );
        // if ( isset( $_POST['wp_bartok_work_score_url'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_score_url', esc_attr( $_POST['wp_bartok_work_score_url'] ) );
        // if ( isset( $_POST['wp_bartok_work_score_thumbnail'] ) )
        //     update_post_meta( $post_id, 'wp_bartok_work_score_thumbnail', esc_attr( $_POST['wp_bartok_work_score_thumbnail'] ) );
        // if ( isset( $_POST['wp_bartok_work_press_quote'] ) )
        //     update_post_meta($post_id, 'wp_bartok_work_press_quote', esc_attr( $_POST['wp_bartok_work_press_quote']) );
        // if ( isset( $_POST['wp_bartok_work_quote_author'] ) )
        //     update_post_meta($post_id, 'wp_bartok_work_quote_author', esc_attr( $_POST['wp_bartok_work_quote_author']) );
        // if ( isset( $_POST['wp_bartok_work_video_url'] ) )
        //     update_post_meta($post_id, 'wp_bartok_work_video_url', esc_attr( $_POST['wp_bartok_work_video_url']) );
        // if ( isset( $_POST['wp_bartok_work_program_note'] ) )
        //     update_post_meta($post_id, 'wp_bartok_work_program_note', esc_attr( $_POST['wp_bartok_work_program_note']) );
    }

    private function get_meta( $value, $whichwork = null) {
          global $post;

          if ($whichwork) {
              $idToUse = $whichwork;
          } else {
              $idToUse = $post->ID;
          }
          $field = get_post_meta( $idToUse, $value, true );
          if ( ! empty( $field ) ) {
              return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
          } else {
              return false;
          }
    }

    public function remove_custom_meta() {
      remove_meta_box( 'postcustom' , 'works' , 'normal' );
    }
}

$Bartok = new BartokWorks;
