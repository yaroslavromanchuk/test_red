<div class="menu-bottom-box">
    <div class="menu-bottom-bg-top"></div>
    <div class="menu-bottom-bg-center">

        <?php
        $menues = wsActiveRecord::useStatic('Menu')->findAll(array('type_id >= 3', 'parent_id is null'));
        foreach ($menues as $menu)
        {
        ?>
        <span class="sep"></span>

        <div class="block">
            <?php
            $sub_cats = wsActiveRecord::useStatic('Menu')->findAll(array('parent_id' => $menu->getId(), 'type_id<999'));
            echo '<a href="' . $menu->getPath() . '" class="top">' . $menu->getName() . '</a>';
            if ($sub_cats->count()) {
                foreach ($sub_cats as $sub_menu) {
                    echo '<a href="' . $sub_menu->getPath() . '">' . $sub_menu->getName() . '</a>';
                }
            }
            echo '</div>';
            }  ?>


            <?php /*
                                    $cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>0));
                                    foreach ($cats as $category)
                                    {
                                ?>
                                    <span class="sep"></span>
									<div class="block">
								<?php
                                        $sub_cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>$category->getId()));
										echo '<a href="'.$category->getPath().'" class="top">'.$category->getName().'</a>';
                                        if($sub_cats->count()){
                                            foreach ($sub_cats as $sub_category)
                                            {
                                                echo '<a href="'.$sub_category->getPath().'">'.$sub_category->getName().'</a>';
                                            }
                                        }
                                     echo '</div>';
                                } */
            ?>

            <span class="sep"></span>

            <div style="clear: both;"></div>
        </div>
        <div class="menu-bottom-bg-bottom"></div>
    </div>