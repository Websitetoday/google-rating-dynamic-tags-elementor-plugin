<?php
namespace GRE\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Google_Rating_Tag extends Tag {
    public function get_name() {
        return 'google-rating';
    }

    public function get_title() {
        return __( 'Google Rating', 'gre' );
    }

    public function get_group() {
        return [ 'site' ];
    }

    public function get_categories() {
        return [ 'text', 'number' ];
    }

    protected function register_controls() {
        $this->add_control( 'field', array(
            'label'   => __( 'Weergaveveld', 'gre' ),
            'type'    => Controls_Manager::SELECT,
            'options' => array(
                'rating_number' => __( 'Gemiddelde score (nummer)', 'gre' ),
                'rating_star'   => __( 'Gemiddelde score + ster (tekst)', 'gre' ),
                'count_number'  => __( 'Aantal reviews (nummer)', 'gre' ),
                'both'          => __( 'Score + Aantal (tekst)', 'gre' ),
            ),
            'default' => 'rating_star',
        ) );
    }

    public function render() {
        $data   = gre_fetch_google_place_data();
        if ( ! $data ) {
            echo esc_html__( 'Geen data beschikbaar.', 'gre' );
            return;
        }
        $rating = floatval( $data['rating'] );
        $count  = intval( $data['user_ratings_total'] );

        switch ( $this->get_settings( 'field' ) ) {
            case 'rating_number':
                echo $rating;
                break;
            case 'rating_star':
                echo sprintf( '%.1f ★', $rating );
                break;
            case 'count_number':
                echo $count;
                break;
            case 'both':
            default:
                echo sprintf( '<strong>%.1f</strong> ★ %d reviews', $rating, $count );
                break;
        }
    }
}