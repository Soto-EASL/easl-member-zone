<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_MZ_Publications extends EASL_MZ_Shortcode {
	public function get_publications_data() {
		global $wpdb;
		$sql = "";
		$sql .= "SELECT terms.term_id, terms.name, SUBSTRING_INDEX(GROUP_CONCAT({$wpdb->posts}.ID ORDER BY pd.meta_value DESC SEPARATOR ','), ',', 1) AS pub_ID";
		$sql .= " FROM {$wpdb->posts}";
		$sql .= " INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
		$sql .= " INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )";
		$sql .= " INNER JOIN {$wpdb->terms} AS terms USING( term_id )";
		$sql .= " INNER JOIN {$wpdb->postmeta} AS pd ON ( {$wpdb->posts}.ID = pd.post_id ) ";
		$sql .= " WHERE {$wpdb->posts}.post_type IN ( 'publication' )";
		$sql .= " AND {$wpdb->posts}.post_status = 'publish'";
		$sql .= " AND term_taxonomy.taxonomy = 'publication_category'";
		$sql .= " AND pd.meta_key = 'publication_raw_date'";
		$sql .= " GROUP BY terms.term_id";
		$sql .= " ORDER BY MAX(pd.meta_value) DESC";
		$sql .= " LIMIT 3";

		$results = $wpdb->get_results( $sql );

		$data = array();
		if ( ! $results ) {
			return array();
		}
		foreach ( $results as $item ) {
			$data[] = array(
				'ID'        => $item->pub_ID,
				'cat_title' => $item->name,
				'image'     => $this->get_publication_image( $item->pub_ID ),
				'link'      => get_permalink( $item->pub_ID ),
			);
		}

		return $data;
	}

	public function get_publication_image( $pub_id ) {
		if ( ! has_post_thumbnail( $pub_id ) ) {
			return '';
		}

		return wp_get_attachment_image_url( get_post_thumbnail_id( $pub_id ), 'single-post-thumbnail' );
	}
}