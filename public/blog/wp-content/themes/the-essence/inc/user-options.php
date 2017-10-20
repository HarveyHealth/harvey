<?php

function the_essence_show_extra_profile_fields( $user ) {
	
	?>

	<h3><?php esc_html_e(  'Social Profiles', 'the-essence' ); ?></h3>

	<table class="form-table">

		<tr>
			<th><label for="the_essence_twitter"><?php esc_html_e(  'Twitter', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_twitter" id="the_essence_twitter" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="the_essence_facebook"><?php esc_html_e(  'Facebook', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_facebook" id="the_essence_facebook" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="the_essence_instagram"><?php esc_html_e(  'Instagram', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_instagram" id="the_essence_instagram" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_instagram', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="the_essence_behance"><?php esc_html_e(  'Behance', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_behance" id="the_essence_behance" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_behance', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="the_essence_dribbble"><?php esc_html_e(  'Dribbble', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_dribbble" id="the_essence_dribbble" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_dribbble', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="the_essence_vine"><?php esc_html_e(  'Vine', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_vine" id="the_essence_vine" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_vine', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="the_essence_linkedin"><?php esc_html_e(  'LinkedIn', 'the-essence' ); ?></label></th>
			<td>
				<input type="text" name="the_essence_linkedin" id="the_essence_linkedin" value="<?php echo esc_attr( get_the_author_meta( 'the_essence_linkedin', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php esc_html_e(  'The full URL to your profile.', 'the-essence' ); ?></span>
			</td>
		</tr>

	</table>

	<?php

} add_action( 'show_user_profile', 'the_essence_show_extra_profile_fields' ); add_action( 'edit_user_profile', 'the_essence_show_extra_profile_fields' );

function the_essence_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta( $user_id, 'the_essence_twitter', $_POST['the_essence_twitter'] );
	update_user_meta( $user_id, 'the_essence_facebook', $_POST['the_essence_facebook'] );
	update_user_meta( $user_id, 'the_essence_instagram', $_POST['the_essence_instagram'] );
	update_user_meta( $user_id, 'the_essence_behance', $_POST['the_essence_behance'] );
	update_user_meta( $user_id, 'the_essence_dribbble', $_POST['the_essence_dribbble'] );
	update_user_meta( $user_id, 'the_essence_vine', $_POST['the_essence_vine'] );
	update_user_meta( $user_id, 'the_essence_linkedin', $_POST['the_essence_linkedin'] );

} add_action( 'personal_options_update', 'the_essence_save_extra_profile_fields' ); add_action( 'edit_user_profile_update', 'the_essence_save_extra_profile_fields' );