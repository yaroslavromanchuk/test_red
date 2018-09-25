<?php 

class Apriori
{
	//return ItemSet
	function start(ItemSet $data, $support, $confidence)
    {
        $result = new ItemSet();
        $li = new ItemSet();
        $conList = new ItemSet();
        $subConList = new ItemSet();
        $subDataList = new ItemSet();
        $CurList = null;
        $subList = null;
        $k = 2;
        $li->Add(new ItemSet());
        $li->Add($this->FindOneColSet($data, $support, $confidence));
 
        while ($li->arr[$k - 1]->Count != 0)
        {
            echo "K = " . ($k - 1);
            $conList = $this->AprioriGenerate($li->arr[$k - 1], $k - 1, $support); //генерация кандидатов
            for ( $i = 0; $i < $data->Count; $i++)
            {
                $subDataList = $this->SubSet($data->arr[$i], $k); //удаление избыточных правил
                for ( $j = 0; $j < $subDataList->Count; $j++)
                {
                    $subList = $subDataList->arr[$j];
                    for ( $n = 0; $n < $conList->Count; $n++)
                    {
                        $subDataList->arr[$j]->Sort();
                        $conList->arr[$n]->Sort();
                        $CurList = $conList->arr[$n];
                        if ($subList->Equals($CurList))
                        {
                            $conList->arr[$n]->ICount++;
                        }
                    }
 
                }
 
            }
 
            $li->Add(new ItemSet());
            //отбор кандидатов
            for ( $i = 0; $i < $conList->Count; $i++)
            {
                $con = $conList->arr[$i];
                if ($con->ICount >= $support)
                {
                    $li->arr[$k]->Add($con);
                }
            }
 
            $k++;
        }
        
        /* ??
        for ($j = 0; $j < $li->Count; $j++)
        {
            for ($h = 0; $h < $li->Count; $h++)
            {
                if ($li->arr[$j].Equals($li->arr[$h]))
                {
                    li.arr.RemoveAt(j);
                    li.Count = li.arr.Count;
                }
            }
        }
        */
        
        for ( $i = 0; $i < $li->Count; $i++)
        {
            $result->Add($li->arr[$i]);
        }
        return $result;
 
  
 
    }
 	
 	//generate one column set with support (count)
 	function FindOneColSet(ItemSet $data, $support, $confidence)   
	{   
		$cur=null;   
		$result = new ItemSet();   

		$set=null;   
		$newset=null;   
		$cd=null;   
		$td=null;   
		$flag = true;   

		for ($i = 0; $i < $data->Count; $i++) {   
			$cur = $data->arr[$i];   
			for ( $j = 0; $j < $cur->Count; $j++) {   
				$cd = $cur->arr[$j];   
				   
				for ($n = 0; $n < $result->Count; $n++) {   
					$set = $result->arr[$n];   
					$td = $set->arr[0];   
					if ($cd->Id == $td->Id)   
					{   
						$set->ICount++;   
						$flag = false;   
						break;   															 
					}   
					$flag = true;   
				}   
				if ($flag) {   
					$newset = new ItemSet();   
					$newset->Add($cd);   
					$result->Add($newset);   
					$newset->ICount = 1;   
				}   
			}   
		}   
		$finalResult = new ItemSet();   
		for ( $i = 0; $i < $result->Count; $i++)   
		{   
			$con = $result->arr[$i];   
			if ($con->ICount >= $support && $con->ICount >= $confidence)   
			{   
				$finalResult->Add($con);   
			}   
		}   
		//$finalResult->Sort();
		//var_dump($finalResult);
		return $finalResult;   
	} 
 
/*
1.    Объединение. Каждый кандидат Ck будет формироваться путем расширения часто встречающегося набора размера (k-1) добавлением элемента из другого (k-1)- элементного набора. Приведем алгоритм этой функции Apriorigen в виде небольшого SQL-подобного запроса:
INSERT
into Ck
SELECT
p.item1, p.item2, …, p.itemk-1, q.itemk-1
FROM
Fk-1 p, Fk-1 q
WHERE
 p.item1= q.item1, p.item2 = q.item2, … , p.itemk-2 = q.itemk-2, p.itemk-1 < q.itemk-1 
*/
    private function AprioriGenerate(ItemSet $li, $k, $support)
    {
        $curList = null;
        $durList = null;
        $candi = null;
        $result = new ItemSet();
        for ($i = 0; $i < $li->Count; $i++)
        {
            for ($j = 0; $j < $li->Count; $j++)
            {
                $flag = true;
                $curList = $li->arr[$i];
                $durList = $li->arr[$j];
                for ($n = 2; $n < $k; $n++)
                {
                    if ($curList->arr[$n - 2]->Id == $durList->arr[$n - 2]->Id)
                    {
                        $flag = true;
                    }
                    else
                    {
                        $flag = false;
                        break;
                    }
                }
 
                if ($flag && $curList->arr[$k - 1]->Id < $durList->arr[$k - 1]->Id)
                {
                    $flag = true;
                }
                else
                {
                    $flag = false;
                }
                
                if ($flag)
                {
                    $candi = new ItemSet();
 
                    for ($m = 0; $m < $k; $m++)
                    {
                        $candi->Add($durList->arr[$m]);
                    }
                    $candi->Add($curList->arr[$k - 1]);
 
 					/*
 					2.    Удаление избыточных правил. На основании свойства анти-монотонности, 
 					следует удалить все наборы c Ck если хотя бы одно из его (k-1) подмножеств не является часто встречающимся.
 					*/
                    if ($this->HasInFrequentSubset($candi, $li, $k))
                    {
                        $candi->Clear();
                    }
                    else
                    {
                        $result->Add($candi);
                    }
                }
            }
        }
        return $result;
    } 
  
 
    private function HasInFrequentSubset(ItemSet $candidate, ItemSet $li, $k)
    {
        $subSet = $this->SubSet($candidate, $k);
        $curList = null;
        $liCurList = null;
 
        for ($i = 0; $i < $subSet->Count; $i++)
        {
            $curList = $subSet->arr[$i];
            for ($j = 0; $j < $li->Count; $j++)
            {
                $liCurList = $li->arr[$j];
                if ($liCurList->Equals($curList))
                {
                    return false;
                }
            }
        }
        return true;
    }
    /*
    //????   
    private ItemSet SubSet(ItemSet set)
    {
        ItemSet subSet = new ItemSet();
 
        ItemSet itemSet = new ItemSet();
        //???2n??   
        int num = 1 << set.Count;
 
        int bit;
        int mask = 0; ;
        for (int i = 0; i < num; i++)
        {
            itemSet = new ItemSet();
            for (int j = 0; j < set.Count; j++)
            {
                //mask?i??????????   
                mask = 1 << j;
                bit = i & mask;
                if (bit > 0)
                {
 
                    itemSet.Add((ItemSet)set.arr[j]);
 
                }
            }
            if (itemSet.Count > 0)
            {
                subSet.Add(itemSet);
            }
 
 
        }
 
  
 
        return subSet;
    }
 	*/
  
 
    //????   
    private function SubSet(ItemSet $set, $t)
    {
        $subSet = new ItemSet();
 
        $itemSet = new ItemSet();
        //???2n??   
        $num = 1 << $set->Count;
 
        $mask = 0; ;
        for ($i = 0; $i < $num; $i++)
        {
            $itemSet = new ItemSet();
            for ($j = 0; $j < $set->Count; $j++)
            {
                //mask?i??????????   
                $mask = 1 << $j;
                $bit = $i & $mask;
                if ($bit > 0)
                {
                    $itemSet->Add($set->arr[$j]);
                }
            }
            if ($itemSet->Count == $t)
            {
                $subSet->Add($itemSet);
            }
        }
        return $subSet;
    }
}


class DataItem
{
    public $Id;
    public $ItemName;
    
    public function DataItem( $item, $id)
    {
        $this->ItemName = $item;
        $this->Id = $id;
    }
    
    public function Equals(DataItem $obj)
    {
    	if($obj->Id == $this->Id)
			return true;
    	else
    		return false;
    }
}

class ItemSet
{
    public $Count=0;
    public $ICount=0;
    public $arr = array();
    
    public function Add($input)
    {
        $this->arr[] = $input;
        $this->Count++;
    }

    public function Sort()
    {
        $temp = null;
        for ( $i = 0; $i < $this->Count-1; $i++)
        {
            for ( $j = $i+1; $j < $this->Count; $j++)
            {
                if ($this->arr[$i]->Id > $this->arr[$j]->Id)
                {
                    $temp = $this->arr[$i];
                    $this->arr[$i] = $this->arr[$j];
                    $this->arr[$j] = $temp;
                }
            }
        }
    }
    public function Equals(ItemSet $input)
    {
        if (($input->arr == null) || !is_array($input->arr))
        {
            return false;
        }
        else if ($input->Count != $this->Count )
        {
            return false;
        }
        else
        {
            for ( $i = 0; $i < $this->Count; $i++)
            {
                if (!$this->arr[$i]->Equals($input->arr[$i]))
                    return false;
            }
            return true;
        }
    }
    public function Clear()
    {
        $this->arr = array();
        $this->Count = 0;
        $this->ICount = 0;
    }
}