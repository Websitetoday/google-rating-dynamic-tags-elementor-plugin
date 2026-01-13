<?php
namespace GRE\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH')) exit;

class Google_Opening_Hours_Widget extends Widget_Base {

    public function get_name() {
        return 'gre-opening-hours';
    }

    public function get_title() {
        return 'Google Openingstijden';
    }

    public function get_icon() {
        return 'eicon-clock-o';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['google', 'opening', 'hours', 'openingstijden', 'tijd', 'uren', 'bedrijf'];
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
                $options[$id] = $name;
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
            'display_mode',
            [
                'label' => 'Weergave',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'full_week' => 'Volledige week',
                    'today' => 'Alleen vandaag',
                    'today_status' => 'Open/Gesloten status',
                    'monday' => 'Maandag',
                    'tuesday' => 'Dinsdag',
                    'wednesday' => 'Woensdag',
                    'thursday' => 'Donderdag',
                    'friday' => 'Vrijdag',
                    'saturday' => 'Zaterdag',
                    'sunday' => 'Zondag',
                ],
                'default' => 'full_week',
            ]
        );

        $this->add_control(
            'time_format',
            [
                'label' => 'Tijdformaat',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '24h' => '24-uurs (14:00)',
                    'ampm' => 'AM/PM (2:00 PM)',
                ],
                'default' => '24h',
            ]
        );

        $this->add_control(
            'highlight_today',
            [
                'label' => 'Markeer vandaag',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'display_mode' => 'full_week',
                ],
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => 'Toon klok icoon',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Ja',
                'label_off' => 'Nee',
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => 'Layout',
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'list' => 'Lijst (verticaal)',
                    'inline' => 'Inline (horizontaal)',
                    'table' => 'Tabel',
                ],
                'default' => 'list',
                'condition' => [
                    'display_mode' => 'full_week',
                ],
            ]
        );

        $this->add_control(
            'closed_text',
            [
                'label' => 'Tekst voor gesloten',
                'type' => Controls_Manager::TEXT,
                'default' => 'Gesloten',
                'placeholder' => 'Gesloten',
            ]
        );

        $this->add_control(
            'open_prefix',
            [
                'label' => 'Prefix voor open',
                'type' => Controls_Manager::TEXT,
                'default' => 'Nu open tot',
                'placeholder' => 'Nu open tot',
                'condition' => [
                    'display_mode' => 'today_status',
                ],
            ]
        );

        $this->add_control(
            'closed_prefix',
            [
                'label' => 'Prefix voor gesloten',
                'type' => Controls_Manager::TEXT,
                'default' => 'Nu gesloten',
                'placeholder' => 'Nu gesloten',
                'condition' => [
                    'display_mode' => 'today_status',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - CONTAINER
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => 'Container',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_background',
            [
                'label' => 'Achtergrondkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .gre-opening-hours' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .gre-opening-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .gre-opening-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .gre-opening-hours',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .gre-opening-hours',
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - DAY NAME
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_day',
            [
                'label' => 'Dagnaam',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'day_color',
            [
                'label' => 'Tekstkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .gre-day-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_typography',
                'selector' => '{{WRAPPER}} .gre-day-name',
            ]
        );

        $this->add_responsive_control(
            'day_width',
            [
                'label' => 'Breedte dagnaam',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-day-name' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - HOURS
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_hours',
            [
                'label' => 'Openingstijden',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hours_color',
            [
                'label' => 'Tekstkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .gre-hours' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hours_typography',
                'selector' => '{{WRAPPER}} .gre-hours',
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - CLOSED
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_closed',
            [
                'label' => 'Gesloten',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'closed_color',
            [
                'label' => 'Tekstkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#e74c3c',
                'selectors' => [
                    '{{WRAPPER}} .gre-closed' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'closed_typography',
                'selector' => '{{WRAPPER}} .gre-closed',
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - TODAY HIGHLIGHT
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_today',
            [
                'label' => 'Vandaag markering',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'highlight_today' => 'yes',
                    'display_mode' => 'full_week',
                ],
            ]
        );

        $this->add_control(
            'today_background',
            [
                'label' => 'Achtergrondkleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#e8f5e9',
                'selectors' => [
                    '{{WRAPPER}} .gre-row.is-today' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'today_day_color',
            [
                'label' => 'Dagnaam kleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#2e7d32',
                'selectors' => [
                    '{{WRAPPER}} .gre-row.is-today .gre-day-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'today_hours_color',
            [
                'label' => 'Uren kleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#2e7d32',
                'selectors' => [
                    '{{WRAPPER}} .gre-row.is-today .gre-hours' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'today_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '4',
                    'right' => '8',
                    'bottom' => '4',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-row.is-today' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'today_border_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '4',
                    'right' => '4',
                    'bottom' => '4',
                    'left' => '4',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-row.is-today' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - ICON
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => 'Icoon',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => 'Kleur',
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .gre-clock-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => 'Grootte',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 48,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gre-clock-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => 'Ruimte na icoon',
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
                    '{{WRAPPER}} .gre-clock-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ══════════════════════════════════════════
        // STYLE SECTION - ROW SPACING
        // ══════════════════════════════════════════
        $this->start_controls_section(
            'section_style_spacing',
            [
                'label' => 'Rij afstand',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_mode' => 'full_week',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_spacing',
            [
                'label' => 'Ruimte tussen rijen',
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
                    '{{WRAPPER}} .gre-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gre-row:last-child' => 'margin-bottom: 0;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Vertaal Engelse dagnaam naar Nederlands
     */
    private function translate_day($day) {
        $translations = [
            'Monday' => 'Maandag',
            'Tuesday' => 'Dinsdag',
            'Wednesday' => 'Woensdag',
            'Thursday' => 'Donderdag',
            'Friday' => 'Vrijdag',
            'Saturday' => 'Zaterdag',
            'Sunday' => 'Zondag',
        ];
        return $translations[$day] ?? $day;
    }

    /**
     * Formatteer tijden op basis van gekozen formaat
     */
    private function format_hours($hours, $format) {
        if ($format === '24h') {
            return gre_convert_to_24h($hours);
        }
        // AM/PM - behoud origineel formaat
        return $hours;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $business_id = $settings['business_id'] ?? 'default';

        $data = gre_fetch_google_place_data($business_id);
        if (!$data || empty($data['opening_hours']['weekday_text'])) {
            echo '<p class="gre-no-data">Geen openingstijden beschikbaar.</p>';
            return;
        }

        $lines = $data['opening_hours']['weekday_text'];
        $mode = $settings['display_mode'];
        $time_format = $settings['time_format'] ?? '24h';
        $highlight_today = $settings['highlight_today'] === 'yes';
        $show_icon = $settings['show_icon'] === 'yes';
        $closed_text = $settings['closed_text'] ?: 'Gesloten';

        // Clock icon SVG
        $clock_icon = '<svg class="gre-clock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>';

        // Get current day (0 = Monday in our array)
        $day_map = ['monday' => 0, 'tuesday' => 1, 'wednesday' => 2, 'thursday' => 3, 'friday' => 4, 'saturday' => 5, 'sunday' => 6];
        $php_day = date('N') - 1; // PHP: 1=Monday, 7=Sunday, we need 0-6

        echo '<div class="gre-opening-hours">';

        if ($show_icon && in_array($mode, ['today', 'today_status'])) {
            echo $clock_icon;
        }

        // Full week view
        if ($mode === 'full_week') {
            $layout = $settings['layout'] ?? 'list';

            if ($layout === 'table') {
                echo '<table class="gre-hours-table">';
            }

            foreach ($lines as $index => $line) {
                $parts = explode(':', $line, 2);
                $day = trim($parts[0] ?? '');
                $day = $this->translate_day($day);
                $hours = trim($parts[1] ?? '');

                $is_closed = stripos($hours, 'gesloten') !== false || stripos($hours, 'closed') !== false;
                $is_today = ($index === $php_day) && $highlight_today;

                $row_class = 'gre-row';
                if ($is_today) $row_class .= ' is-today';

                if ($layout === 'table') {
                    echo '<tr class="' . esc_attr($row_class) . '">';
                    echo '<td class="gre-day-name">' . esc_html($day) . '</td>';
                    echo '<td class="' . ($is_closed ? 'gre-closed' : 'gre-hours') . '">';
                    echo $is_closed ? esc_html($closed_text) : esc_html($this->format_hours($hours, $time_format));
                    echo '</td>';
                    echo '</tr>';
                } else {
                    echo '<div class="' . esc_attr($row_class) . '">';
                    if ($show_icon && $index === 0) echo $clock_icon;
                    echo '<span class="gre-day-name">' . esc_html($day) . '</span>';
                    echo '<span class="' . ($is_closed ? 'gre-closed' : 'gre-hours') . '">';
                    echo $is_closed ? esc_html($closed_text) : esc_html($this->format_hours($hours, $time_format));
                    echo '</span>';
                    echo '</div>';
                }
            }

            if ($layout === 'table') {
                echo '</table>';
            }
        }
        // Today only
        elseif ($mode === 'today') {
            $line = $lines[$php_day] ?? '';
            $parts = explode(':', $line, 2);
            $day = trim($parts[0] ?? '');
            $day = $this->translate_day($day);
            $hours = trim($parts[1] ?? '');
            $is_closed = stripos($hours, 'gesloten') !== false || stripos($hours, 'closed') !== false;

            echo '<div class="gre-row is-today">';
            echo '<span class="gre-day-name">' . esc_html($day) . '</span>';
            echo '<span class="' . ($is_closed ? 'gre-closed' : 'gre-hours') . '">';
            echo $is_closed ? esc_html($closed_text) : esc_html($this->format_hours($hours, $time_format));
            echo '</span>';
            echo '</div>';
        }
        // Today status (open/closed)
        elseif ($mode === 'today_status') {
            $line = $lines[$php_day] ?? '';
            $is_closed = stripos($line, 'gesloten') !== false || stripos($line, 'closed') !== false;

            $open_prefix = $settings['open_prefix'] ?: 'Nu open tot';
            $closed_prefix = $settings['closed_prefix'] ?: 'Nu gesloten';

            echo '<div class="gre-status">';
            if ($is_closed) {
                echo '<span class="gre-closed">' . esc_html($closed_prefix) . '</span>';
            } else {
                // Extract closing time
                if (preg_match('/(\d{1,2}:\d{2})\s*(AM|PM)?\s*[\x{2013}\x{2014}-]\s*(\d{1,2}:\d{2})\s*(AM|PM)?/iu', $line, $matches)) {
                    $closing_time = $matches[3] . (!empty($matches[4]) ? ' ' . $matches[4] : '');
                    $closing_time = $this->format_hours($closing_time, $time_format);
                    echo '<span class="gre-hours">' . esc_html($open_prefix) . ' ' . esc_html($closing_time) . '</span>';
                } else {
                    echo '<span class="gre-hours">' . esc_html($open_prefix) . '</span>';
                }
            }
            echo '</div>';
        }
        // Specific day
        else {
            $day_index = $day_map[$mode] ?? 0;
            $line = $lines[$day_index] ?? '';
            $parts = explode(':', $line, 2);
            $day = trim($parts[0] ?? '');
            $day = $this->translate_day($day);
            $hours = trim($parts[1] ?? '');
            $is_closed = stripos($hours, 'gesloten') !== false || stripos($hours, 'closed') !== false;

            echo '<div class="gre-row">';
            if ($show_icon) echo $clock_icon;
            echo '<span class="gre-day-name">' . esc_html($day) . '</span>';
            echo '<span class="' . ($is_closed ? 'gre-closed' : 'gre-hours') . '">';
            echo $is_closed ? esc_html($closed_text) : esc_html($this->format_hours($hours, $time_format));
            echo '</span>';
            echo '</div>';
        }

        echo '</div>';
    }
}
