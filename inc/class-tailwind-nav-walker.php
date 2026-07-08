<?php
class Tailwind_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = (array) $item->classes;
        $is_current = in_array('current-menu-item', $classes);

        // 1. Get base classes from layout (or use fallback defaults)
        $layout_classes = isset($args->link_class) ? $args->link_class : 'font-label-caps text-label-caps text-on-surface';
        
        // 2. Get active state classes from layout (or use fallback defaults)
        $layout_active_classes = isset($args->active_link_class) ? $args->active_link_class : 'text-refined-gold';

        // 3. Combine classes based on whether the item is active
        if ($is_current) {
            $link_classes = $layout_classes . ' ' . $layout_active_classes . ' lsw-active-link';
        } else {
            $link_classes = $layout_classes;
        }

        // Output the link cleanly
        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($link_classes) . '">' . esc_html($item->title) . '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= ""; 
    }
}
