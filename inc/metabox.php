<?php
/**
 * MetaBoxes
 *
 * @package FarmFactory
 */

/**
 * Adds a meta box to post type farmfactory
 */
class FarmFactory_Meta_Box {

	/**
	 * Construct
	 */
	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	/**
	 * Init Metabox
	 */
	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );

	}

	/**
	 * Add Metabox
	 */
	public function add_metabox() {

		add_meta_box(
			'farmfactory_meta',
			esc_html__( 'Farm Factory Details', 'farmfactory' ),
			array( $this, 'render_metabox' ),
			'farmfactory',
			'normal',
			'high'
		);

		add_meta_box(
			'farmimagediv',
			esc_html__( 'Staking Token Icon', 'farmfactory' ),
			array( $this, 'render_thumbnail' ),
			'farmfactory',
			'side',
			'low'
		);

	}

	/**
	 * Render Metabox
	 *
	 * @param object $post Post.
	 */
	public function render_metabox( $post ) {

		/* Add nonce for security and authentication */
		wp_nonce_field( 'farmfactory_meta_action', 'farmfactory_meta_nonce' );

		// Retrieve an existing value from the database.
		$staking_address = get_post_meta( $post->ID, 'staking_address', true );
		$reward_address  = get_post_meta( $post->ID, 'reward_address', true );
		$reward_decimals = get_post_meta( $post->ID, 'reward_decimals', true );
		$reward_duration = get_post_meta( $post->ID, 'reward_duration', true );
		$withdrawLockPeriod = get_post_meta( $post->ID, 'withdrawLockPeriod', true );
		$farm_apy        = get_post_meta( $post->ID, 'farm_apy', true );
		$farm_apy_label  = get_post_meta( $post->ID, 'farm_apy_label', true );
		$network_name    = get_post_meta( $post->ID, 'network_name', true );
		$farm_address    = get_post_meta( $post->ID, 'farm_address', true );

		// Set default values.
		if ( empty( $staking_address ) ) $staking_address = ''; // phpcs:ignore
		if ( empty( $reward_address ) ) $reward_address   = ''; // phpcs:ignore
		if ( empty( $reward_decimals ) ) $reward_decimals = ''; // phpcs:ignore
		if ( empty( $reward_duration ) ) $reward_duration = ''; // phpcs:ignore
		if ( empty( $withdrawLockPeriod ) ) $withdrawLockPeriod = ''; // phpcs:ignore
		if ( empty( $farm_apy ) ) $farm_apy               = ''; // phpcs:ignore
		if ( empty( $farm_apy_label ) ) $farm_apy_label   = 'APY'; // phpcs:ignore
		if ( empty( $network_name ) ) $network_name       = ''; // phpcs:ignore
		if ( empty( $farm_address ) ) $farm_address       = ''; // phpcs:ignore

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Staking erc20 Address', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="staking_address" id="stakingAddress" class="large-text" value="' . esc_attr( $staking_address ) . '">
						<p class="description">' . sprintf( esc_html__( 'ERC20 address of token&#039;s contract which users will stake (deposit). Free test tokens %s.', 'farmfactory' ), '<a href="https://github.com/bokkypoobah/WeenusTokenFaucet" target="_blank">https://github.com/bokkypoobah/WeenusTokenFaucet</a>' ) . '</p>
					';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Reward erc20 address', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '			<div class="farmfactory-form-inline">
						<input type="text" name="reward_address" id="rewardsAddress" class="large-text" value="' . esc_attr( $reward_address ) . '">
						<span>' . esc_html__( '.Decimals', 'farmfactory' ) . '</span>
						<input type="number" name="reward_decimals" id="farmfactory_reward_decimals" class="small-text" value="' . esc_attr( $reward_decimals ) . '">
						</div>
						<p class="description">' . esc_html__( 'ERC20 address of reward token which users will earn. You can use the same as "Staking address".', 'farmfactory' ) . '</p>
					';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Reward Duration', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="reward_duration" id="farmfactory_duration" class="large-text" value="' . esc_attr( $reward_duration ) . '">
						<p class="description">' . sprintf( esc_html__( 'Enter _rewardsDuration - duration of staking round in seconds.%s 86400 - 1 day, 2592000 - 30 day, 31536000 - 1 year', 'farmfactory' ), '<br>' ) . '</p>
						';
		echo '		</td>';
		echo '	</tr>';
		
		// withdrawLockPeriod
		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Withdraw Lock Period', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="withdrawLockPeriod" id="farmfactory_withdrawLockPeriod" class="large-text" value="' . esc_attr( $withdrawLockPeriod ) . '">
						<p class="description">' . sprintf( esc_html__( 'Enter withdrawLockPeriod - in seconds.%s 86400 - 1 day, 2592000 - 30 day, 31536000 - 1 year', 'farmfactory' ), '<br>' ) . '</p>
						';
		echo '		</td>';
		echo '	</tr>';


		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Annual Percentage Yield (APY)', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="farm_apy" id="farmfactory_apy" class="large-text" value="' . esc_attr( $farm_apy ) . '">
						<p class="description">' . sprintf( esc_html__( 'APY you write in the admin doesn\'t affect the contract logic. It\'s just a value to display in the widget', 'farmfactory' ), '<br>' ) . '</p>
						';
		echo '		</td>';
		echo '	</tr>';

			echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'APY label', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="farm_apy_label" id="farmfactory_apy_label" class="large-text" value="' . esc_attr( $farm_apy_label ) . '">
						';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Newtwork', 'farmfactory' ) . '</label></th>';
		echo '		<td>';
		echo '		<strong>' . get_option( 'farmfactory_networkName','ropsten' ) . '</strong>';

		echo '			<p class="desctiption">' . esc_html__( 'Ropsten or Mainnet. We recommend to test on testnet with testnet tokens before launch', 'farmfactory' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Farming Address', 'farmfactory' ) . '</label></th>';
		echo '		<td>
						<div class="farmfactory-form-inline">
							<input name="farm_address" id="farmfactory_farmAddress" type="text" class="large-text" value="' . esc_attr( $farm_address ) . '">
							<a class="button button-secondary" id="farmfactory_deploy_button">' . esc_html__( 'Deploy', 'farmfactory' ) . '</a>
						</div>
						<p class="desctiption">' . esc_html__( 'Use "Deploy" button next to the field to create new Farm contract. After deployment address will be automatically placed in the field above', 'farmfactory' ) . '</p>
						<p class="desctiption" style="color: red;">' . esc_html__( 'If you have Farm contract address then you can pass it to this field. NOTE: please be sure that you fill Farm contract address! If you are not sure PLEASE PRESS "Deploy" button!!! Otherwise, this may lead to incorrect operations of the program and you may lose your tokens!"', 'farmfactory' ) . '</p>
				</td>';
		echo '	</tr>';

		if ( get_post_meta( get_the_ID(), 'farm_address', true ) ) {

			echo '	<tr>';
			echo '		<th><label>' . esc_html__( 'Start/Stop farming period', 'farmfactory' ) . '</label></th>';
			echo '		<td>
							<p class="desctiption">
								<strong>1.</strong> ' . esc_html__( 'Enter the amount of tokens which you want to distribute across all users who will deposit tokens to the cotract.', 'farmfactory') . '
								<input value="" type="number" id="amount" class="medium-text js-farmfactory-load-icon">
							</p>
							<p class="desctiption">
								<strong>2.</strong>' . esc_html__('Transfer required amount of tokens to the farm contract: (' . get_post_meta( get_the_ID(), 'farm_address', true ) . ')', 'farmfactory') . '
							</p>
							<p class="desctiption">
								<strong>3.</strong> Click <input type="button" id="farmfactory_startFarmingButton" class="button button-primary mcwallet-add-token" value="' . esc_attr__('Start Farming Period', 'farmfactory') . '">
								<span class="spinner"></span>

							</p>


					</td>';
			echo '	</tr>';

			echo '	<tr>';
			echo '		<th><label>' . esc_html__( 'Reward distributing', 'farmfactory' ) . '</label></th>';
			echo '		<td>
							<p class="desctiption">' . esc_html__('All distrubutes are autamatically. Put "[farmfactory id="' . get_the_ID() . '"]" shortcode to any post or page in your site and try to deposit, withdraw and harvest some tokens in the frontend ', 'farmfactory') . '
							</p>
							<p class="desctiption">
							' . sprintf( esc_html__('Need help? Contact our team using %s.', 'farmfactory'), '<a href="https://t.me/farmsupportbot" target="_blank">https://t.me/farmsupportbot</a>' ) . '</p>

					</td>';
			echo '	</tr>';

		} else {

			echo '	<tr>';
			echo '		<th><label>' . esc_html__( 'Start/Stop farming period', 'farmfactory' ) . '</label></th>';
			echo '		<td>
							<p class="desctiption">
							' . esc_html__('Please enter farm address contract and press publish before perform this instructions.', 'farmfactory') . '
							</p>
						</td>';
			echo '	</tr>';
		}

		echo '</table>';

		echo '<div id="farmfactory_loaderOverlay" class="farmfactory-overlay">
			<div class="farmfactory-loader"></div>
		</div>';

	}

	/**
	 * Render Thumbnail
	 *
	 * @param object $post Post.
	 */
	public function render_thumbnail( $post ) {

		/* Add nonce for security and authentication */
		wp_nonce_field( 'farmfactory_meta_action', 'farmfactory_meta_nonce' );

		$post             = get_post( $post );
		$post_type_object = get_post_type_object( $post->post_type );

		$thumbnail_id = get_post_meta( $post->ID, '_farm_thumbnail_id', true );

		$content = '<div class="farmfactory-thumbnail-container">';

		if ( $thumbnail_id && get_post( $thumbnail_id ) ) {

			$thumbnail_html = wp_get_attachment_image( $thumbnail_id, 'large' );

			if ( ! empty( $thumbnail_html ) ) {

				$content .= '<p><a href="#" id="set-farm-thumbnail">' . $thumbnail_html . '</a></p>';

				$content .= '<p class="howto" id="set-farm-thumbnail-desc">' . esc_html__( 'Click the image to edit or update', 'farmfactory' ) . '</p>';
				$content .= '<p><a href="#" id="remove-farm-thumbnail">' . $post_type_object->labels->remove_featured_image . '</a></p>';
			}
		} else {
			$content .= '<p><a href="#" id="set-farm-thumbnail">' . $post_type_object->labels->set_featured_image . '</a></p>';
		}

		$content .= '</div>';

		$content .= '<input type="hidden" id="_farm_thumbnail_id" name="_farm_thumbnail_id" value="' . esc_attr( $thumbnail_id ? $thumbnail_id : '-1' ) . '">';

		echo $content;

	}

	public function save_metabox( $post_id, $post ) {

		/* Add nonce for security and authentication */
		$nonce_name   = isset( $_POST['farmfactory_meta_nonce'] ) ? $_POST['farmfactory_meta_nonce'] : '';
		$nonce_action = 'farmfactory_meta_action';

		/* Check if a nonce is set */
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		/* Check if a nonce is valid */
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		/* Check if the user has permissions to save data */
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		/* Check if it's not an autosave */
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		/* Sanitize user input */
		$staking_address = isset( $_POST['staking_address'] ) ? sanitize_text_field( $_POST['staking_address'] ) : '';
		$reward_address  = isset( $_POST['reward_address'] ) ? sanitize_text_field( $_POST['reward_address'] ) : '';
		$reward_decimals = isset( $_POST['reward_decimals'] ) ? sanitize_text_field( $_POST['reward_decimals'] ) : '';
		$reward_duration = isset( $_POST['reward_duration'] ) ? sanitize_text_field( $_POST['reward_duration'] ) : '';
		$withdrawLockPeriod = isset( $_POST['withdrawLockPeriod'] ) ? sanitize_text_field( $_POST['withdrawLockPeriod'] ) : '';
		$farm_apy        = isset( $_POST['farm_apy'] ) ? sanitize_text_field( $_POST['farm_apy'] ) : '';
		$farm_apy_label  = isset( $_POST['farm_apy_label'] ) ? sanitize_text_field( $_POST['farm_apy_label'] ) : '';
		$network_name    = isset( $_POST['network_name'] ) ? sanitize_text_field( $_POST['network_name'] ) : '';
		$farm_address    = isset( $_POST['farm_address'] ) ? sanitize_text_field( $_POST['farm_address'] ) : '';
		$farm_thumbnail  = isset( $_POST['_farm_thumbnail_id'] ) ? sanitize_text_field( $_POST['_farm_thumbnail_id'] ) : '-1';

		/* Update the meta field in the database */
		update_post_meta( $post_id, 'staking_address', $staking_address );
		update_post_meta( $post_id, 'reward_address', $reward_address );
		update_post_meta( $post_id, 'reward_decimals', $reward_decimals );
		update_post_meta( $post_id, 'farm_apy', $farm_apy );
		update_post_meta( $post_id, 'farm_apy_label', $farm_apy_label );
		update_post_meta( $post_id, 'reward_duration', $reward_duration );
		update_post_meta( $post_id, 'withdrawLockPeriod', $withdrawLockPeriod );
		update_post_meta( $post_id, 'network_name', $network_name );
		update_post_meta( $post_id, 'farm_address', $farm_address );
		update_post_meta( $post_id, '_farm_thumbnail_id', $farm_thumbnail );

	}

}

new FarmFactory_Meta_Box;

/**
 * Shortcode
 */
function farmfactory_post_submitbox( $post ) {
	if ( 'farmfactory' === $post->post_type ) {
		?>
		<div class="misc-pub-section">
			<p class="description"><strong><?php esc_html_e( 'Shortcode', 'farmfactory' ); ?><strong></p>
			<input type="text" class="large-text farmfactory-schortcode-copy" value='[farmfactory id="<?php echo esc_attr( $post->ID ); ?>"]' readonly>
			<div class="copy-to-clipboard-container">
				<button type="button" class="button button-small copy-farm-shortcode" data-clipboard-target=".farmfactory-schortcode-copy"><?php esc_html_e( 'Copy Shortcode to clipboard', 'farmfactory' ); ?></button>
				<span class="success hidden" aria-hidden="true"><?php esc_html_e( 'Copied!', 'farmfactory' ); ?></span>
			</div>
		</div>
		<?php
	}
}
add_action( 'post_submitbox_misc_actions', 'farmfactory_post_submitbox' );
