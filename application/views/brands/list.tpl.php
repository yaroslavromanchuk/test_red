<div id="filterAlphabet">
    <div id="filterButton">
        <ul type="none">
            <?php
            $i = 0;
            foreach ($this->getBrands() as $k => $brand) {
                $i++; ?>
                <li id="brand_tab_<?php echo $i; ?>" class="brand_tab">
                    <button <?php if ($k == "See All") { ?> class="filterButton_classSelect" <?php } ?>>
                <?php echo $k == "See All" ? $this->trans->get('Показать все') : $k; ?></button>
                </li>
            <?php } ?>
        </ul>
		<div class="clear"></div>
    </div>

    <div id="brandList">
        <?php
        $j = 0;
        foreach ($this->getBrands() as $k => $brand) { $j++; ?>

            <div id="brand_tab_<?php echo $j; ?>_select" class="brand_tab_select" >
                <?php
            if ($k !== "See All"){
                echo "<h3>{$k}</h3>";
                ?>
                    <ul type="none">
                        <?php foreach($brand as $item){ ?>
                            <li>
                                <a class="brand_img" href="<?php echo $item["path"]; ?>">
                                    <?php if (!$item["image"]) {
                                        echo "<span></span>";
                                    } else {
                                        ?>
                                        <div class="absolute-aligned"><img src="<?php echo $item["image"]; ?>"/>
                                        </div> <?php } ?>
                                </a>

                                <a class="brand_name" href="<?php echo $item["path"]; ?>">
                                    <?php echo $item["name"]; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <div class="clear"></div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

