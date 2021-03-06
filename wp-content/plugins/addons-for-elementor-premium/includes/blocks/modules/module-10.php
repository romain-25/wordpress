<?php

namespace LivemeshAddons\Modules;

class LAE_Module_10 extends LAE_Module {

    function render() {

        $source = $this->source;

        ob_start();
        ?>

        <div class="lae-module-10 <?php echo $this->get_module_classes(); ?>">

            <div class="lae-entry-details">

                <?php echo $source->get_taxonomies_info(); ?>

                <?php echo $source->get_title(); ?>

                <div class="lae-module-meta">
                    <?php echo $source->get_author(); ?>
                    <?php echo $source->get_date(); ?>
                    <?php echo $source->get_comments(); ?>
                    <?php echo $source->get_taxonomies_info(); ?>
                </div>

            </div>

            <div class="lae-module-image">

                <?php echo $source->get_media(); ?>

                <?php echo $source->get_lightbox(); ?>

            </div>

            <div class="lae-excerpt">

                <?php echo $source->get_excerpt(); ?>

            </div>

            <?php echo $source->get_read_more_link(); ?>

        </div>

        <?php $output = ob_get_clean();

        return apply_filters('lae_block_module_10', $output, $source);
    }
}