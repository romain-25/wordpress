<?php

namespace LivemeshAddons\Blocks;

class LAE_Block_Twitter_Grid_3 extends LAE_Twitter_Block {

    function inner($items, $settings) {

        $output = '';

        $item_count = 1;

        $num_of_columns = $settings['per_line'];

        $block_layout = new LAE_Block_Layout();

        $column_class = $this->get_column_class(intval($num_of_columns));

        if (!empty($items)) {

            foreach ($items as $item) {

                $additional_classes = $this->get_grid_item_classes($item, $settings);

                $source = new \LivemeshAddons\Modules\Source\LAE_Twitter_Grid_Source($item, $settings);

                $output .= $block_layout->open_column($column_class, $additional_classes);

                $module2 = new \LivemeshAddons\Modules\LAE_Module_19($source);

                $output .= $module2->render();

                $output .= $block_layout->close_column($column_class);

                $item_count++;

            };

            $output .= $block_layout->close_all_tags();

        };

        return $output;

    }

    protected function get_block_class() {

        return 'lae-block-twitter-grid lae-block-twitter-grid-3 lae-gapless-grid';

    }
}