<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $rows array
 */
?>
<?php
foreach ( $rows as $row ):
	$membership = $row['membership'];
	$membership_notes = $row['membership_notes'];
	$start_date = mz_europe_data_from_crm_date( $membership['start_date'] );
	$end_date = mz_europe_data_from_crm_date( $membership['end_date'] );
	$duration = '';
	if ( $start_date && $end_date ) {
		$duration = $start_date . ' - ' . $end_date;
	}
	$category = easl_mz_get_membership_category_name( $membership['category'] );
	foreach ( $membership_notes as $note ):
		?>
        <div class="mzmd-docs-table-row">
            <div class="mzmd-docs-table-col mzmd-docs-table-col-duration"><?php echo $duration; ?></div>
            <div class="mzmd-docs-table-col mzmd-docs-table-col-type"><?php echo $category; ?></div>
            <div class="mzmd-docs-table-col mzmd-docs-table-col-download">
                <a class="mzmd-download-link" href="<?php echo easl_mz_get_note_download_url( $note ); ?>" target="_blank">Download Invoice</a>
            </div>
        </div>
	<?php endforeach; ?>
<?php endforeach; ?>