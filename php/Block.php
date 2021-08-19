<?php
/**
 * Block class.
 *
 * @package SiteCounts
 */

namespace XWP\SiteCounts;

use WP_Block;

/**
 * The Site Counts dynamic block.
 *
 * Registers and renders the dynamic block.
 */
class Block {

	/**
	 * The Plugin instance.
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Instantiates the class.
	 *
	 * @param Plugin $plugin The plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Adds the action to register the block.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Registers the block.
	 */
	public function register_block() {
		register_block_type_from_metadata(
			$this->plugin->dir(),
			[
				'render_callback' => [ $this, 'render_callback' ],
			]
		);
	}

	/**
	 * Renders the block.
	 *
	 * @param array    $attributes The attributes for the block.
	 * @param string   $content    The block content, if any.
	 * @param WP_Block $block      The instance of this block.
	 * @return string The markup of the block.
	 */
	public function render_callback( $attributes, $content, $block ) {
		$post_types = get_post_types( [ 'public' => true ] );
		$class_name = isset($attributes['className'])? isset($attributes['className']):'';
		ob_start();

		?>
		<div class="<?php echo $class_name; ?>">
			<h2>Post Counts</h2>
			<?php

			if(is_array($post_types)):
				foreach ( $post_types as $post_type_slug ) :
					$post_type_object = get_post_type_object( $post_type_slug );
					$post_count = wp_count_posts( $post_type_slug);
					?>
					<p><?php echo 'There are ' . $post_count . ' ' . $post_type_object->labels->name . '.'; ?></p>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php

			if(isset($_GET['post_id']) && !empty($_GET['post_id'])){
				$post_id = $_GET['post_id'];	  
			}else{
				global $post;
				$post_id = $post->ID;
			}?>
			<p>
			<?php echo 'The current post ID is ' . $post_id . '.'; ?>
			</p>
		</div>
		<?php

		return ob_get_clean();
	}
}
