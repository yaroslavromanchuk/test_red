<?php

class BrandsController extends controllerAbstract
{

    public function init()
    {
        parent::init();

    }

    public function  indexAction()
    {
       // var_dump($this->get);

        if ((int)$this->get->id) {
            $brand = new Brand((int)$this->get->id);
            if ($brand->getId()) {
                $this->showOne($brand);
            } else {
                $this->_redirect('/brands/');
            }
        } else {
            $this->showList();
        }

    }

    public function showList()
    {
        $brands = Brand::findAllActive();

		$brandsFilter = array(
                        "See All" => array(),
                        "123" => array(),
                        "A" => array(),
			"B" => array(),
			"C" => array(),
                        "D" => array(),
			"E" => array(),
			"F" => array(),
			"G" => array(),
			"H" => array(),
			"I" => array(),
			"J" => array(),
			"K" => array(),
			"L" => array(),
			"M" => array(),
			"N" => array(),
			"O" => array(),
			"P" => array(),
			"R" => array(),
			"S" => array(),
			"T" => array(),
			"Q" => array(),
			"U" => array(),
			"V" => array(),
			"W" => array(),
			"X" => array(),
			"Y" => array(),
			"Z" => array()
        );

        foreach ($brands as $brand) {

            $name_trim = trim($brand["name"]);
            $name = mb_strtolower(substr($name_trim, 0, 1));

            $brandsFilter["See All"][$name_trim] = $brand;
			
            if ($name === "0" || (int)$name > 0 || $name === "&") {
                $brandsFilter["123"][$name_trim] = $brand;
            } elseif ($name === 'a'){
                $brandsFilter["A"][$name_trim] = $brand;
            }elseif ($name === 'b'){
                $brandsFilter["B"][$name_trim] = $brand;
            }elseif ($name === 'c'){
                $brandsFilter["C"][$name_trim] = $brand;
            }elseif ($name === 'd'){
                $brandsFilter["D"][$name_trim] = $brand;
            }elseif ($name === 'e'){
                $brandsFilter["E"][$name_trim] = $brand;
            }elseif ($name === 'f'){
                $brandsFilter["F"][$name_trim] = $brand;
            }elseif ($name === 'g'){
                $brandsFilter["G"][$name_trim] = $brand;
            }elseif ($name === 'h'){
                $brandsFilter["H"][$name_trim] = $brand;
            }elseif ($name === 'i'){
                $brandsFilter["I"][$name_trim] = $brand;
            }elseif ($name === 'j'){
                $brandsFilter["J"][$name_trim] = $brand;
            }elseif ($name === 'k'){
                $brandsFilter["K"][$name_trim] = $brand;
            }elseif ($name === 'l'){
                $brandsFilter["L"][$name_trim] = $brand;
            }elseif ($name === 'm'){
                $brandsFilter["M"][$name_trim] = $brand;
            }elseif ($name === 'n'){
                $brandsFilter["N"][$name_trim] = $brand;
            }elseif ($name === 'o'){
                $brandsFilter["O"][$name_trim] = $brand;
            }elseif ($name === 'p'){
                $brandsFilter["P"][$name_trim] = $brand;
            }elseif ($name === 'r'){
                $brandsFilter["R"][$name_trim] = $brand;
            }elseif ($name === 's'){
                $brandsFilter["S"][$name_trim] = $brand;
            }elseif ($name === 't'){
                $brandsFilter["T"][$name_trim] = $brand;
            }elseif ($name === 'q'){
                $brandsFilter["Q"][$name_trim] = $brand;
            }elseif ($name === 'u'){
                $brandsFilter["U"][$name_trim] = $brand;
            }elseif ($name === 'v'){
                $brandsFilter["V"][$name_trim] = $brand;
            }elseif ($name === 'w'){
                $brandsFilter["W"][$name_trim] = $brand;
            }elseif ($name === 'x'){
                $brandsFilter["X"][$name_trim] = $brand;
            }elseif ($name === 'y'){
                $brandsFilter["Y"][$name_trim] = $brand;
            }elseif ($name === 'z'){
                $brandsFilter["Z"][$name_trim] = $brand;
            }
        }

		foreach ($brandsFilter as $key => $value) {
			if (count($value) == 0) {
				unset($brandsFilter[$key]);
			}
		}
		
        $this->view->brands = $brandsFilter;
       // $this->cur_menu->url = '';
        //$this->cur_menu->setName($this->trans->get('breands'));
        
        //$this->cur_menu->setPageTitle($this->trans->get('breands').' '.Config::findByCode('website_name')->getValue());
        
        //$this->cur_menu->setMetatagDescription($category->getDescription());
        
        //$this->cur_menu->setPageFooter($category->getFooter());

        echo $this->render('brands/list.tpl.php');

    }

    public function showOne($brand)
    {
        
        $this->cur_menu->setPageTitle($brand->name.' - '.$this->cur_menu->getName().' '.$this->trans->get('в интернет магазине RED'));
        $this->cur_menu->setName($brand->name);
        $this->cur_menu->setMetatagDescription($this->trans->get('info_brand').' '.$brand->name.' ✓ '.$this->trans->get('товары бренда').' '.$brand->name.' '.$this->trans->get('exit_brand'));
        $this->view->brand = $brand;
        $this->view->articles = $brand->findActiveArticles();
        $this->view->articlesHtml = $this->render('brands/helper.tpl.php');

        echo $this->render('brands/item.tpl.php');

    }
}