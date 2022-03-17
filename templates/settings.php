<?php
/**
 * Settings Page
 *
 * @package FarmFactory
 */

?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form action="#" method="post" class="wp-farmfactory-widget-form">
		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Info', 'farmfactory' ); ?></label>
					</th>
					<td>
						<p class="description">
							<?php esc_html_e( 'First of all please','farmfactory' ); ?> <a href="update-core.php?force-check=1"><?php esc_html_e(' check for updates', 'farmfactory' ); ?></a>.<br> <?php esc_html_e( 'How to use? Just enter [farmfactory] shortcode in your page or post.', 'farmfactory' ); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="blogname"><?php echo esc_html_e( 'Infura ID', 'farmfacotry' ); ?></label>
					</th>
					<td>
						<input name="farmfactory_infura_id" type="text" value="<?php echo esc_attr( get_option( 'farmfactory_infura_id', farmfactory_default_infura_id() ) ); ?>" placeholder="<?php echo esc_attr( farmfactory_default_infura_id() ); ?>" class="regular-text">
					</td>
				</tr>

				<?php
				/*
				<tr>
					<th scope="row">
						<label for="blogname"><?php echo esc_html_e( 'Fortmatic Key', 'farmfacotry' ); ?></label>
					</th>
					<td>
						<input name="farmfactory_fortmatic_key" type="text" value="<?php echo esc_attr( get_option( 'farmfactory_fortmatic_key' ) ); ?>" class="regular-text">
					</td>
				</tr>
				*/
				?>

				<tr>
					<th scope="row">
						<label><?php esc_html_e('Newtwork ', 'farmfactory'); ?></label>
					</th>
					<td>
						<?php
							$farm_factory_network = get_option( 'farmfactory_networkName','ropsten' );
							$networks = array(
								'ropsten',
								'mainnet',
								'rinkeby',
								'bsc',
								'bsc_test',
								'matic',
								'mumbai',
								'xdai',
								'aurora'
							);
						?>
						<select name="farmfactory_networkName">
							<?php foreach ( $networks as $network ) { ?>
								<option value="<?php echo esc_attr( $network ); ?>" <?php selected( $farm_factory_network, $network ); ?>><?php echo esc_html( $network ); ?></option>
							<?php } ?>
						</select>
						<p class="description">
							<?php esc_html_e('Ropsten or Mainnet. We recommend to test on testnet with testnet tokens before launch', 'farmfactory'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row"></th>
					<td>
						<?php submit_button( esc_html__( 'Save settings', 'farmfactory' ) ); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</form>

</div>

