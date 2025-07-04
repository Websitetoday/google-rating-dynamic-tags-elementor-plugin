<?php
namespace GRE\DynamicTags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Google_Opening_Hours_Tag extends Tag {
    public function get_name() {
        return 'google-opening-hours';
    }

    public function get_title() {
        return __( 'Google Openingstijden', 'gre' );
    }

    public function get_group() {
        return [ 'site' ];
    }

    public function get_categories() {
        return [ 'text' ];
    }

    protected function register_controls() {
        // Keuze weergave-modus
        $this->add_control(
            'display_mode',
            [
                'label'   => __( 'Weergave', 'gre' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'monday'    => __( 'Maandag', 'gre' ),
                    'tuesday'   => __( 'Dinsdag', 'gre' ),
                    'wednesday' => __( 'Woensdag', 'gre' ),
                    'thursday'  => __( 'Donderdag', 'gre' ),
                    'friday'    => __( 'Vrijdag', 'gre' ),
                    'saturday'  => __( 'Zaterdag', 'gre' ),
                    'sunday'    => __( 'Zondag', 'gre' ),
                    'today'     => __( 'Vandaag', 'gre' ),
                    'full_week' => __( 'Volledige week', 'gre' ),
                ],
                'default' => 'monday',
            ]
        );

        // Toon dagnaam bij dagweergave
        $this->add_control(
            'show_day_label',
            [
                'label'        => __( 'Toon dagnaam', 'gre' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Ja', 'gre' ),
                'label_off'    => __( 'Nee', 'gre' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        // Kleur van de dagnaam
        $this->add_control(
            'label_color',
            [
                'label'   => __( 'Kleur dagnaam', 'gre' ),
                'type'    => Controls_Manager::COLOR,
                'default' => '#333333',
            ]
        );

        // Kleur van de uren
        $this->add_control(
            'hours_color',
            [
                'label'   => __( 'Kleur tijd', 'gre' ),
                'type'    => Controls_Manager::COLOR,
                'default' => '#555555',
            ]
        );

        // Kleur voor gesloten tekst
        $this->add_control(
            'closed_color',
            [
                'label'   => __( 'Kleur "Gesloten"', 'gre' ),
                'type'    => Controls_Manager::COLOR,
                'default' => '#ff0000',
            ]
        );
    }

    public function render() {
        $data     = gre_fetch_google_place_data();
        $settings = $this->get_settings_for_display();

        if ( ! $data || empty( $data['opening_hours']['weekday_text'] ) ) {
            echo esc_html__( 'Geen openingstijden beschikbaar.', 'gre' );
            return;
        }

        $lines         = $data['opening_hours']['weekday_text'];
        $mode          = $settings['display_mode'];
        $show_day      = ( 'yes' === $settings['show_day_label'] );
        $label_color   = esc_attr( $settings['label_color'] );
        $hours_color   = esc_attr( $settings['hours_color'] );
        $closed_color  = esc_attr( $settings['closed_color'] );

        // Volledige week
        if ( 'full_week' === $mode ) {
            echo '<div style="font-family: Lato, sans-serif; font-size: 16px; line-height: 1.6; max-width: 250px;">';
            foreach ( $lines as $line ) {
                list( $day, $hours ) = array_map( 'trim', explode( ':', $line, 2 ) + [ '', '' ] );
                echo '<p style="margin: 4px 0;">';
                echo '<span style="display:inline-block;width:100px;color:' . $label_color . ';">' . esc_html( $day ) . '</span>';
                $hours_lower = mb_strtolower( $hours, 'UTF-8' );
                if ( false !== strpos( $hours_lower, 'gesloten' ) ) {
                    echo '<span style="color:' . $closed_color . ';">' . esc_html( $hours ) . '</span>';
                } else {
                    echo '<span style="color:' . $hours_color . ';">' . esc_html( $hours ) . '</span>';
                }
                echo '</p>';
            }
            echo '</div>';
            return;
        }

        // Vandaag
        if ( 'today' === $mode ) {
            $today_name = mb_strtolower( date_i18n( 'l' ), 'UTF-8' );
            foreach ( $lines as $line ) {
                if ( stripos( mb_strtolower( $line, 'UTF-8' ), $today_name ) === 0 ) {
                    if ( stripos( mb_strtolower( $line, 'UTF-8' ), 'gesloten' ) !== false ) {
                        echo '<div><span style="color:' . $closed_color . ';">' . esc_html__( 'Vandaag zijn we gesloten.', 'gre' ) . '</span></div>';
                    } elseif ( preg_match( '/(\d{1,2}:\d{2})\s*[\x{2013}\x{2014}-]\s*(\d{1,2}:\d{2})/u', $line, $m ) ) {
                        $open  = $m[1];
                        $close = $m[2];
                        echo '<div>';
                        echo '<span style="color:' . $label_color . ';">' . esc_html__( 'Vandaag zijn we open van', 'gre' ) . '</span> ';
                        echo '<span style="color:' . $hours_color . ';">' . esc_html( $open ) . ' tot ' . esc_html( $close ) . '</span>';
                        echo '</div>';
                    }
                    break;
                }
            }
            return;
        }

        // Specifieke dag
        $map = [
            'monday'    => 0,
            'tuesday'   => 1,
            'wednesday' => 2,
            'thursday'  => 3,
            'friday'    => 4,
            'saturday'  => 5,
            'sunday'    => 6,
        ];
        $day_index = $map[ $mode ] ?? 0;
        $line      = $lines[ $day_index ] ?? '';
        list( $day, $hours ) = array_map( 'trim', explode( ':', $line, 2 ) + [ '', '' ] );

        echo '<div>';
        if ( $show_day ) {
            echo '<span style="display:inline-block;width:100px;color:' . $label_color . ';">' . esc_html( $day ) . '</span>';
        }
        $hours_lower = mb_strtolower( $hours, 'UTF-8' );
        if ( false !== strpos( $hours_lower, 'gesloten' ) ) {
            echo '<span style="color:' . $closed_color . ';">' . esc_html( $hours ) . '</span>';
        } else {
            echo '<span style="color:' . $hours_color . ';">' . esc_html( $hours ) . '</span>';
        }
        echo '</div>';
    }
}
