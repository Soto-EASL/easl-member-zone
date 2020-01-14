<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $countries array
 * @var $top_countries array
 * @var $membership_categories array
 * @var $specialities array
 */
$flag_url_root             = easl_mz_get_asset_url( '/images/flags/' );
$country_member_count_html = '';
?>
    <div class="mz-stat-filters">
        <div class="mz-country-filter">
            <div class="mzms-field-wrap">
                <select name="mzstat_country" id="mzstat_country" class="easl-mz-select2" data-placeholder="Select a country" style="width: 100%;">
                    <option value="">Select your country</option>
					<?php foreach ( $countries as $country_key => $country_data ): ?>
                        <option value="<?php echo $country_key; ?>"><?php echo $country_data['country_label']; ?></option>
						<?php
						$country_member_count_html .= '<span id="mzcstat-' . $country_key . '" class="mz-country-stat-count">' . number_format( $country_data['member_count'] ) . _n( ' Member', ' Members', $country_data['member_count'] ) . '</span>';
						?>
					<?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mz-country-stats">
			<?php echo $country_member_count_html; ?>
        </div>
    </div>
    <div class="mz-wordlwide-stats">
        <h4 class="mz-subheading">Worldwide membership by type</h4>
		<?php if ( $membership_categories ): ?>
            <div class="mz-wordlwide-stats-categories">
				<?php foreach ( $membership_categories as $mc_label => $mc_count ): ?>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span><?php echo str_replace( ' member', '', $mc_label ); ?></span>
                                <strong><?php echo number_format_i18n( $mc_count ); ?></strong>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
		<?php if ( $specialities ): ?>
            <h4 class="mz-subheading">Worldwide membership by speciality</h4>
            <div class="mz-worldwide-stats-speciality">
				<?php
				$specialities = array_splice($specialities, 0, 6, true);
				foreach ( $specialities as $sp_label => $sp_count ):

					$sp_slug = str_replace( array( ' / ', '/', ' ' ), '_', $sp_label );
					$sp_slug = sanitize_html_class( strtolower( $sp_slug ) );
					?>
                    <div class="mz-stat-block ms-speciality-<?php echo $sp_slug; ?>">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong><?php echo number_format_i18n( $sp_count ); ?></strong>
                                <span><?php echo $sp_label; ?></span>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
    </div>
<?php if ( $top_countries ): ?>
    <div class="mz-stat-top-countries-wrap">
        <h4>Top member countries</h4>
        <div class="mz-stat-top-countries-map">
            <div id="easl-mz-stat-map"></div>
        </div>
        <div class="mz-stat-top-countries easl-row easl-row-col-3">
			<?php
			$items_per_column = ceil( count( $top_countries ) / 3 );
			$item_count       = 0;
			echo '<div class="easl-col"><div class="easl-col-inner">';
			foreach ( $top_countries as $cc => $top_country ) {
				if ( ( $item_count > 1 ) && ( $item_count % $items_per_column == 0 ) ) {
					echo '</div></div><div class="easl-col"><div class="easl-col-inner">';
				}
				switch ( $cc ) {
					case 'USA':
						$top_country['country_label'] = 'USA';
						break;
					case 'GBR':
						$top_country['country_label'] = 'UNITED KINGDOM';
						break;
				}
				?>
                <div class="mz-stat-country">
                    <div class="mz-country-flag">
                        <img src="<?php echo $flag_url_root . $cc . '.svg'; ?>" alt="<?php echo esc_attr( $top_countries['country_label'] ); ?>">
                    </div>
                    <div class="mz-country-name"><?php echo $top_country['country_label']; ?>
                        <span>(<?php echo number_format_i18n( $top_country['member_count'] ); ?>)</span></div>
                </div>
				<?php
				$item_count ++;
			}
			echo '</div></div>';
			?>
        </div>
    </div>
<?php endif; ?>