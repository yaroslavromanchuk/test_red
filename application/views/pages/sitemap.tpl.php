<ul>
  <?php
                    $menus=wsActiveRecord::useStatic('Menu')->findAll(array('type_id is not null', 'parent_id' => null), array('sequence' => 'ASC'));
                     foreach($menus as $menu) {

                    echo '<li><a href="' . $menu->getPath() . '">' . $menu->getName() .'</a>';                    
                    
                     $sub_menu=wsActiveRecord::useStatic('Menu')->findAll(array('type_id is not null', 'parent_id' => $menu->getId()), array('sequence' => 'ASC'));
                     if($sub_menu->count())
                     {       
                     ?>
                         <ul>
                         <?php foreach ($sub_menu as $sm)
                         { echo  '<li><a href="'.$sm->getPath().'" class="submenu">'.$sm->getName().'</a></li>'; }
                         ?>
                         </ul>
                       <?php
                     }   
                        
                    
                    echo '</li>';
                } ?>
</ul>

<ul>
  <?php
                    $news=wsActiveRecord::useStatic('News')->findAll(array('status'=>1), array('id' => 'DESC'));
                     foreach($news as $new) {

                    echo '<li><a href="/articles/id/' . $new->getId() .  '/">' . $new->getTitle() .'</a>';                    
                     
                    
                    echo '</li>';
                } ?>
</ul>