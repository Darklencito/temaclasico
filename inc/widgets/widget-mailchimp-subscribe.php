<?php
/**
 * Mailchimp Subscribe Widget
 *
 * There are two hidden inputs, where the account ID and the selected list ID are saved.
 * These two fields get populated via JS, because we get the email lists from the MC API.
 *
 * @package BuildPress
 */

if ( ! class_exists( 'PT_Mailchimp_Subscribe' ) ) {
	class PT_Mailchimp_Subscribe extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				false, // ID, auto generate when false.
				esc_html__( 'ProteusThemes: MailChimp Subscribe', 'buildpress_wp'),
				array(
					'description' => esc_html__( 'Display a subscribe form for collecting emails with MailChimp.', 'buildpress_wp'),
					'classname'   => 'widget-mailchimp-subscribe',
				)
			);

			// AJAX callback.
			add_action( 'wp_ajax_pt_mailchimp_subscribe_get_lists', array( $this, 'mailchimp_get_lists' ) );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			$api_key       = empty( $instance['api_key'] ) ? '' : $instance['api_key'];
			$account_id    = empty( $instance['account_id'] ) ? '' : $instance['account_id'];
			$selected_list = empty( $instance['selected_list'] ) ? '' : $instance['selected_list'];

			$mc_datacenter = ( ! empty( $api_key ) && 3 < strlen( $api_key ) ) ? substr( $api_key , -3 ) : '';

			$form_action = sprintf(
				'//github.%1$s.list-manage.com/subscribe/post?u=%2$s&amp;id=%3$s',
				esc_attr( $mc_datacenter ),
				esc_attr( $account_id ),
				esc_attr( $selected_list )
			);

			$mc_securty_string = sprintf( 'b_%1$s_%2$s', esc_attr( $account_id ), esc_attr( $selected_list ) );

			echo $args['before_widget'];
			?>
				<div class="mailchimp-subscribe">
					<div id="mc_embed_signup">
						<form action="<?php echo esc_url( $form_action ); ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<div id="mc_embed_signup_scroll">
								<input type="email" value="" name="EMAIL" class="email  form-control" id="mce-EMAIL" placeholder="Email address" required>
								<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
								<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="<?php echo esc_attr( $mc_securty_string ); ?>" tabindex="-1" value=""></div>
								<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button  btn  btn-primary  btn-block"></div>
							</div>
						</form>
					</div>
				</div>
			<?php
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['api_key']       = sanitize_text_field( $new_instance['api_key'] );
			$instance['account_id']    = sanitize_text_field( $new_instance['account_id'] );
			$instance['selected_list'] = sanitize_text_field( $new_instance['selected_list'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			$api_key       = empty( $instance['api_key'] ) ? '' : $instance['api_key'];
			$account_id    = empty( $instance['account_id'] ) ? '' : $instance['account_id'];
			$selected_list = empty( $instance['selected_list'] ) ? '' : $instance['selected_list'];

			?>
			<p>
				<?php esc_html_e( 'In order to use this widget, you have to: ', 'buildpress_wp' ); ?>
			</p>

			<ol>
				<li><?php printf( esc_html__( '%1$sVisit this URL and login with your mailchimp account%2$s,', 'buildpress_wp' ), '<a href="https://admin.mailchimp.com/account/api" target="_blank">', '</a>' ); ?></li>
				<li><?php esc_html_e( 'Create an API key and paste it in the input field below,', 'buildpress_wp' ); ?></li>
				<li><?php esc_html_e( 'Click on the Connect button, so that your existing MailChimp lists can be retrieved,', 'buildpress_wp' ); ?></li>
				<li><?php esc_html_e( 'Select which list you want your visitors to subscribe to, from the dropdown menu below.', 'buildpress_wp' ); ?></li>
			</ol>

			<p>
				<label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php esc_html_e( 'MailChimp API key:', 'buildpress_wp'); ?>
				</label>
				<input class="js-mailchimp-api-key" id="<?php echo $this->get_field_id( 'api_key' ); ?>" size="50" name="<?php echo $this->get_field_name( 'api_key' ); ?>" type="text" value="<?php echo esc_html( $api_key ); ?>" />
				<input class="js-connect-mailchimp-api-key  button" type="button" value="<?php esc_html_e( 'Connect', 'buildpress_wp' ); ?>">
				<input class="js-mailchimp-account-id" id="<?php echo $this->get_field_id( 'account_id' ); ?>" name="<?php echo $this->get_field_name( 'account_id' ); ?>" type="hidden" value="<?php echo esc_attr( $account_id ); ?>">
			</p>

			<p class="js-mailchimp-loader" style="display: none;">
				<span class="spinner" style="display: inline-block; float: none; visibility: visible; margin-bottom: 6px;" ></span> <?php esc_html_e( 'Loading ...', 'buildpress_wp' ); ?>
			</p>

			<div class="js-mailchimp-notice"></div>

			<p class="js-mailchimp-list-container" style="display: none;">
				<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php esc_html_e( 'MailChimp list:', 'buildpress_wp' ); ?></label> <br>
				<select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>"></select>
				<input class="js-mailchimp-selected-list" id="<?php echo $this->get_field_id( 'selected_list' ); ?>" name="<?php echo $this->get_field_name( 'selected_list' ); ?>" type="hidden" value="<?php echo esc_attr( $selected_list ); ?>">
			</p>

			<?php if ( ! empty( $api_key ) ) : ?>
				<script type="text/javascript">
					jQuery( '.js-connect-mailchimp-api-key' ).trigger( 'click' );
				</script>
			<?php endif; ?>

			<?php
		}

		/**
		 * AJAX callback function to retrieve the MailChimp lists.
		 */
		public function mailchimp_get_lists() {
			check_ajax_referer( 'pt-buildpress-ajax-verification', 'security' );

			$response = array();

			$api_key        = sanitize_text_field( $_GET['api_key'] );
			$mc_datacenter = sanitize_text_field( $_GET['mc_dc'] );

			$args = array(
				'headers' => array(
					'Authorization' => sprintf( 'apikey %1$s', $api_key ),
				),
			);

			$mc_lists_endpoint = sprintf( 'https://%1$s.api.mailchimp.com/3.0/lists', $mc_datacenter );

			$request = wp_remote_get( $mc_lists_endpoint, $args );

			// Error while connecting to the MailChimp server.
			if ( is_wp_error( $request ) ) {
				$response['message'] = esc_html__( 'There was an error connecting to the MailChimp servers.', 'buildpress_wp' );

				wp_send_json_error( $response );
			}

			// Retrieve the response code and body.
			$response_code = wp_remote_retrieve_response_code( $request );
			$response_body = json_decode( wp_remote_retrieve_body( $request ), true );

			// The request was not successful.
			if ( 200 !== $response_code ) {
				$response['message'] = sprintf( esc_html__( 'Error: %1$s (error code: %2$s)', 'buildpress_wp' ), $response_body['title'], $response_body['status'] );

				wp_send_json_error( $response );
			}

			// There are no lists in this MailChimp account.
			if ( empty( $response_body['lists'] ) ) {
				$response['message'] = esc_html__( 'There are no email lists with this API key! Please create an email list in the MailChimp dashboard and try again.', 'buildpress_wp' );

				wp_send_json_error( $response );
			}

			$mc_account_id = $this->get_mailchimp_account_id( $api_key, $mc_datacenter );

			if ( empty( $mc_account_id ) ) {
				$response['message'] = esc_html__( 'There was an error connecting to the MailChimp servers.', 'buildpress_wp' );

				wp_send_json_error( $response );
			}

			$lists = array();

			// Parse through the retrieved lists and collect the info we need.
			foreach ( $response_body['lists'] as $list ) {
				$lists[ $list['id'] ] = $list['name'];
			}

			$response['message']    = esc_html__( 'MailChimp lists were successfully retrieved!', 'buildpress_wp' );
			$response['lists']      = $lists;
			$response['account_id'] = $mc_account_id;

			wp_send_json_success( $response );
		}


		/**
		 * Get the mailchimp account ID from the API key.
		 *
		 * @return string API key
		 */
		private function get_mailchimp_account_id( $api_key, $mc_datacenter ) {
			$args = array(
				'headers' => array(
					'Authorization' => sprintf( 'apikey %1$s', $api_key ),
				),
			);

			$mc_account_endpoint = sprintf( 'https://%1$s.api.mailchimp.com/3.0/', $mc_datacenter );

			$request = wp_remote_get( $mc_account_endpoint, $args );

			if ( is_wp_error( $request ) || 200 !== wp_remote_retrieve_response_code( $request ) ) {
				return false;
			}

			$body = json_decode( wp_remote_retrieve_body( $request ), true );

			return $body['account_id'];
		}

	}

	add_action( 'widgets_init', create_function( '', 'register_widget( "PT_Mailchimp_Subscribe" );' ) );
}
