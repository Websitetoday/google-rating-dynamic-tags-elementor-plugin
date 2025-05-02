<?php
namespace GRE\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

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
        $this->add_control( 'place', [
            'label'   => __( 'Bedrijf', 'gre' ),
            'type'    => Controls_Manager::SELECT,
            'options' => gre_get_places_for_select(),
            'default' => '',
        ] );

        $this->add_control( 'field', [
            'label'   => __( 'Weergaveveld', 'gre' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'rating_number' => __( 'Gemiddelde score (nummer)', 'gre' ),
                'rating_star'   => __( 'Gemiddelde score + ster', 'gre' ),
                'count_number'  => __( 'Aantal reviews (nummer)', 'gre' ),
                'both'          => __( 'Score + Aantal (tekst)', 'gre' ),
            ],
            'default' => 'rating_star',
        ] );
    }

    public function render() {
        $place = $this->get_settings( 'place' );
        $field = $this->get_settings( 'field' );

        if ( $place === 'all' ) {
            $output = '';
            $places = get_option( GRE_OPT_PLACES, [] );
            foreach ( $places as $p ) {
                $data = gre_fetch_google_place_data( $p['place_id'] );
                if ( ! $data ) continue;
                $r = floatval( $data['rating'] );
                $c = intval( $data['user_ratings_total'] );
                switch ( $field ) {
                    case 'rating_number':  $output .= $r; break;
                    case 'rating_star':    $output .= sprintf( '%.1f ★', $r ); break;
                    case 'count_number':   $output .= $c; break;
                    default:               $output .= sprintf( '%.1f ★ %d reviews', $r, $c );
                }
                $output .= '<br>';
            }
            echo $output;
            return;
        }

        $data = gre_fetch_google_place_data( $place );
        if ( ! $data ) {
            echo esc_html__( 'Geen data beschikbaar.', 'gre' );
            return;
        }

        $r = floatval( $data['rating'] );
        $c = intval( $data['user_ratings_total'] );
        switch ( $field ) {
            case 'rating_number':  echo $r; break;
            case 'rating_star':    echo sprintf( '%.1f ★', $r ); break;
            case 'count_number':   echo $c; break;
            default:               echo sprintf( '<strong>%.1f</strong> ★ %d reviews', $r, $c );
        }
    }
}
