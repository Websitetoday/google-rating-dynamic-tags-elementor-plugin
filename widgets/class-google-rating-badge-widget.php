<?php
namespace GRE\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH')) exit;

class Google_Rating_Badge_Widget extends Widget_Base {

    public function get_name() {
        return 'gre-rating-badge';
    }

    public function get_title() {
        return 'Google Rating Badge';
    }

    public function get_icon() {
        return 'eicon-rating';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['google', 'rating', 'reviews', 'badge', 'stars', 'beoordeling'];
    }

    /**
     * Haal beschikbare bedrijven op voor dropdown
     */
    private function get_business_options() {
        $businesses = gre_get_businesses();
        $options = [];

        if (empty($businesses)) {
            $options['default'] = 'Standaard bedrijf';
        } else {
            foreach ($businesses as $id => $business) {
                $name = $business['name'] ?? 'Onbekend';
                $rating = isset($business['data']['rating']) ? ' (' . $business['data']['rating'] . '★)' : '';
                $options[$id] = $name . $rating;
            }
        }

        return $options;
    }

    protected function register_controls() {

        // ══════════════════════════════════════════
        // CONTENT SECTION
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Content',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Bedrijf selectie
        $this->add_control(
            'business_id',
            [
                'label' => 'Bedrijf',
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_business_options(),
                'default' => 'default',
                'description' => 'Selecteer welk bedrijf je wilt tonen.',
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'show_google_logo',
            [
                'label' => 'Toon Google Logo',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_stars',
            [
                'label' => 'Toon Sterren',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_score',
            [
                'label' => 'Toon Score',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_reviews_count',
            [
                'label' => 'Toon Aantal Reviews',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'reviews_text',
            [
                'label' => 'Reviews Tekst',
                'type' => Controls_Manager::TEXT,
                'default' => 'reviews',
                'placeholder' => 'reviews',
                'condition' => [
                    'show_reviews_count' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link_to_google',
            [
                'label' => 'Link naar Google Reviews',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - BADGE
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => 'Badge',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_background_color',
            [
                'label' => 'Achtergrondkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gre-rating-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '8',
                    'right' => '16',
                    'bottom' => '8',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-rating-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_gap',
            [
                'label' => 'Ruimte tussen elementen',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-rating-badge' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'badge_border_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-rating-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'badge_border',
                'selector' => '{{WRAPPER}} .gre-rating-badge',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .gre-rating-badge',
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 1,
                            'blur' => 3,
                            'spread' => 0,
                            'color' => 'rgba(0,0,0,0.12)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_alignment',
            [
                'label' => 'Uitlijning',
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => 'Links',
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Midden',
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => 'Rechts',
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .gre-badge-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - GOOGLE LOGO
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_logo',
            [
                'label' => 'Google Logo',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_google_logo' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_width',
            [
                'label' => 'Breedte',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 52,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-badge-google-logo' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - STARS
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_stars',
            [
                'label' => 'Sterren',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_stars' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stars_color',
            [
                'label' => 'Sterren Kleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#FBBC04',
                'selectors' => [
                    '{{WRAPPER}} .gre-star-full path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'stars_empty_color',
            [
                'label' => 'Lege Sterren Kleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .gre-star-empty path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'stars_size',
            [
                'label' => 'Grootte',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-star' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'stars_gap',
            [
                'label' => 'Ruimte tussen sterren',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-badge-stars' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - SCORE
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_score',
            [
                'label' => 'Score',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_score' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'score_color',
            [
                'label' => 'Tekstkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .gre-badge-score' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'score_typography',
                'selector' => '{{WRAPPER}} .gre-badge-score',
                'fields_options' => [
                    'font_weight' => [
                        'default' => '600',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 15,
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - SEPARATOR
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_separator',
            [
                'label' => 'Scheiding',
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'show_score',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'show_reviews_count',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => 'Kleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#dadce0',
                'selectors' => [
                    '{{WRAPPER}} .gre-badge-separator' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'separator_style',
            [
                'label' => 'Stijl',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '|' => 'Verticale lijn |',
                    '•' => 'Bullet •',
                    '-' => 'Streepje -',
                    '/' => 'Slash /',
                    'none' => 'Geen',
                ],
                'default' => '|',
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - REVIEWS COUNT
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_reviews',
            [
                'label' => 'Reviews',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_reviews_count' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'reviews_color',
            [
                'label' => 'Tekstkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#5f6368',
                'selectors' => [
                    '{{WRAPPER}} .gre-badge-reviews' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_typography',
                'selector' => '{{WRAPPER}} .gre-badge-reviews',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 13,
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $business_id = $settings['business_id'] ?? 'default';

        // Haal Google data op voor geselecteerd bedrijf
        $data = gre_fetch_google_place_data($business_id);
        if (!$data) {
            echo '<p>Geen Google Rating data beschikbaar. Configureer de plugin instellingen.</p>';
            return;
        }

        $rating = floatval($data['rating'] ?? 0);
        $count = intval($data['user_ratings_total'] ?? 0);

        // Haal place_id op voor dit bedrijf
        $business = gre_get_business($business_id);
        $place_id = $business['place_id'] ?? get_option('gre_place_id', '');

        // Google logo SVG
        $google_logo = '<svg class="gre-badge-google-logo" viewBox="0 0 74 24" xmlns="http://www.w3.org/2000/svg"><path d="M9.24 8.19v2.46h5.88c-.18 1.38-.64 2.39-1.34 3.1-.86.86-2.2 1.8-4.54 1.8-3.62 0-6.45-2.92-6.45-6.54s2.83-6.54 6.45-6.54c1.95 0 3.38.77 4.43 1.76L15.4 2.5C13.94 1.08 11.98 0 9.24 0 4.28 0 .11 4.04.11 9s4.17 9 9.13 9c2.68 0 4.7-.88 6.28-2.52 1.62-1.62 2.13-3.91 2.13-5.75 0-.57-.04-1.1-.13-1.54H9.24z" fill="#4285F4"/><path d="M25 6.19c-3.21 0-5.83 2.44-5.83 5.81 0 3.34 2.62 5.81 5.83 5.81s5.83-2.46 5.83-5.81c0-3.37-2.62-5.81-5.83-5.81zm0 9.33c-1.76 0-3.28-1.45-3.28-3.52 0-2.09 1.52-3.52 3.28-3.52s3.28 1.43 3.28 3.52c0 2.07-1.52 3.52-3.28 3.52z" fill="#EA4335"/><path d="M53.58 7.49h-.09c-.57-.68-1.67-1.3-3.06-1.3C47.53 6.19 45 8.72 45 12c0 3.26 2.53 5.81 5.43 5.81 1.39 0 2.49-.62 3.06-1.32h.09v.81c0 2.22-1.19 3.41-3.1 3.41-1.56 0-2.53-1.12-2.93-2.07l-2.22.92c.64 1.54 2.33 3.43 5.15 3.43 2.99 0 5.52-1.76 5.52-6.05V6.49h-2.42v1zm-2.93 8.03c-1.76 0-3.1-1.5-3.1-3.52 0-2.05 1.34-3.52 3.1-3.52 1.74 0 3.1 1.5 3.1 3.54 0 2.02-1.36 3.5-3.1 3.5z" fill="#4285F4"/><path d="M38 6.19c-3.21 0-5.83 2.44-5.83 5.81 0 3.34 2.62 5.81 5.83 5.81s5.83-2.46 5.83-5.81c0-3.37-2.62-5.81-5.83-5.81zm0 9.33c-1.76 0-3.28-1.45-3.28-3.52 0-2.09 1.52-3.52 3.28-3.52s3.28 1.43 3.28 3.52c0 2.07-1.52 3.52-3.28 3.52z" fill="#FBBC05"/><path d="M58 .24h2.51v17.57H58z" fill="#34A853"/><path d="M68.26 15.52c-1.3 0-2.22-.59-2.82-1.76l7.77-3.21-.26-.66c-.48-1.3-1.96-3.7-4.97-3.7-2.99 0-5.48 2.35-5.48 5.81 0 3.26 2.46 5.81 5.76 5.81 2.66 0 4.2-1.63 4.84-2.57l-1.98-1.32c-.66.96-1.56 1.6-2.86 1.6zm-.18-7.15c1.03 0 1.91.53 2.2 1.28l-5.25 2.17c0-2.44 1.73-3.45 3.05-3.45z" fill="#EA4335"/></svg>';

        // Sterren genereren
        $stars_html = '';
        if ($settings['show_stars'] === 'yes') {
            $full_stars = floor($rating);
            $half_star = ($rating - $full_stars) >= 0.5;
            $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

            $stars_html = '<span class="gre-badge-stars">';

            for ($i = 0; $i < $full_stars; $i++) {
                $stars_html .= '<svg class="gre-star gre-star-full" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
            }

            if ($half_star) {
                $stars_html .= '<svg class="gre-star gre-star-half" viewBox="0 0 24 24"><defs><linearGradient id="halfGrad-' . $this->get_id() . '"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#E8E8E8"/></linearGradient></defs><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="url(#halfGrad-' . $this->get_id() . ')"/></svg>';
            }

            for ($i = 0; $i < $empty_stars; $i++) {
                $stars_html .= '<svg class="gre-star gre-star-empty" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
            }

            $stars_html .= '</span>';
        }

        // Separator
        $separator = '';
        if ($settings['show_score'] === 'yes' && $settings['show_reviews_count'] === 'yes') {
            $sep_char = $settings['separator_style'] ?? '|';
            if ($sep_char !== 'none') {
                $separator = '<span class="gre-badge-separator">' . esc_html($sep_char) . '</span>';
            }
        }

        // Reviews text
        $reviews_text = $settings['reviews_text'] ?: 'reviews';

        // Build link URL
        $link_url = '';
        if ($settings['link_to_google'] === 'yes' && $place_id) {
            $link_url = 'https://search.google.com/local/reviews?placeid=' . urlencode($place_id);
        }

        // Output
        echo '<div class="gre-badge-wrapper" style="display:flex;">';

        if ($link_url) {
            echo '<a href="' . esc_url($link_url) . '" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">';
        }

        echo '<div class="gre-rating-badge">';

        if ($settings['show_google_logo'] === 'yes') {
            echo $google_logo;
        }

        echo $stars_html;

        if ($settings['show_score'] === 'yes') {
            echo '<span class="gre-badge-score">' . number_format($rating, 1, ',', '.') . '</span>';
        }

        echo $separator;

        if ($settings['show_reviews_count'] === 'yes') {
            echo '<span class="gre-badge-reviews">' . number_format($count, 0, ',', '.') . ' ' . esc_html($reviews_text) . '</span>';
        }

        echo '</div>';

        if ($link_url) {
            echo '</a>';
        }

        echo '</div>';
    }
}
