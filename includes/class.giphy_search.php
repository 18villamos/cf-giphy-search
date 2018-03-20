<?php
class GiphySearch {
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_giphy_search' ) );
            add_action( 'load-post-new.php', array( $this, 'init_giphy_search' ) );
        }
    }

    function init_giphy_search() {
        add_action( 'admin_enqueue_scripts',    array( $this, 'giphy_admin_enqueue')        );
        add_action( 'admin_head',               array( $this, 'giphy_admin_css')            );
        add_action( 'add_meta_boxes',           array( $this, 'giphy_add_meta_box')         );
        add_action( 'add_meta_boxes',           array( $this, 'giphy_search_add_meta_box')  );
        add_action( 'save_post',                array( $this, 'giphy_search_save')          );
    }

    //add necessary Javascript for Giphy search feature
    function giphy_admin_enqueue($hook) {
        wp_enqueue_script( 'giphy_config', plugins_url() . '/giphy-search/js/config.js' );
        wp_enqueue_script( 'giphy_admin', plugins_url() . '/giphy-search/js/giphy-search.js' );
    }

    //add css for Giphy search feature
    function giphy_admin_css() {
        echo '<link rel="stylesheet" href="' . plugins_url() . '/giphy-search/css/admin-style.css" type="text/css" media="all" />';
    }

    //Metabox showing GIFs associated with currently edited post
    function giphy_add_meta_box() {
        add_meta_box(
            'mk-show-images',
            __( 'Chosen GIFs', 'chosen_gifs' ),
            array($this,'giphy_render_metabox'),
            'post',
            'normal',
            'high'
        );
    }

    //Contents of Metabox showing associated GIFs.  It will show GIFs already associated and on the fly, as new ones are chosen in the search
    function giphy_render_metabox( $post) {
        $giphy_images = "";
        if (get_post_meta($post->ID,'giphy_images')) {
            //"giphy_images" is an array of Giphy image IDs.  The actual URLs need to be provided by the API.
            if (get_post_meta($post->ID,'giphy_images',true)) {
                foreach(get_post_meta($post->ID,'giphy_images',true) as $this_image) {
                    $image_data 	= "http://api.giphy.com/v1/gifs/" . $this_image . "?api_key=mBz9b0j9ikkHXYIpjPcKxdjAiDYaBM17";
                    $image_object 	= file_get_contents($image_data);
                    $image_decoded	= json_decode($image_object, true);
                    $image_url		= $image_decoded['data']['images']['original']['url'];

                    //the chosen_images[] hidden field accumulates the images into an array to populate the 'giphy_images' meta field
                    $giphy_images 	.= '<img src="' . $image_url . '" class="giphy-chosen-image" data_image_id="' . $this_image . '"/> ';
                    $giphy_images 	.= '<input type="hidden" name="chosen_images[]" value="' . $this_image . '"/>';
                }
            }
        }
    	?>

        <div id="giphy_chosen_images">
            <?php if($giphy_images != "" ) {?>
                <p>These are the images already associated with this post. <strong>To Delete</strong>, click an image.  The association will be removed when you update.</p>
            <?php } else { ?>
                <p>This is where you keep track of images you have chosen. Click an image to delete.</p>
            <?php }  ?>
            <?php echo $giphy_images; ?>
        </div>
        <?php
    }

    //Metabox containing search field and results for Giphy search
    function giphy_search_add_meta_box() {
        add_meta_box(
            'mk-find-images',
            __( 'Find GIFs', 'find_gifs' ),
             array($this,'giphy_search_html'),
            'post',
            'normal',
            'high'
        );
    }

    //Contents of Metabox for search field and results
    function giphy_search_html( $post) {
    	wp_nonce_field( '_giphy_search_nonce', 'giphy_search_nonce' );
    	 ?>


        <div>
            <input type="text" name="giphy_search_find_images" id="giphy_search_find_images" placeholder="Type a search term here." ></input>

            <a class="button button-primary button-large giphy-search-button" id="giphy_search_button" data_offset="0">Search</a>
        </div>

        <div id="giphy_results"></div>

        <?php
    }

    //When the post is updated save chosen GIFs as well
    function giphy_search_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['mk_giphy_search_nonce'] ) || ! wp_verify_nonce( $_POST['giphy_search_nonce'], '_giphy_search_nonce' ) ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        update_post_meta( $post_id, 'giphy_images', $_POST['chosen_images']);
    }

}

new GiphySearch;
