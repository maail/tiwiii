<?php 
	$query = new queryDB;
	$sql = " SELECT genre as current_gen from genres";
	if($query->query($sql))
	{
		while($row = $query->get_array())
		{
			extract($row);
			$genre[] = $current_gen;
			
		}
	}
?>
<div id="filter-genre">
	<ul>
        <?php
        echo " <li><a href='#all' id='all' class='selected'>All</a></li>";
        $sql = " SELECT genre as current_gen from genres";
    	if($query->query($sql))
    	{   
            while($row = $query->get_array())
    		{
    			extract($row);
    			$href= str_replace(' ', '-', $current_gen);
				$href = strtolower($href);
				
                echo " <li><a href='#$href' id='$href'>$current_gen</a></li>";  
                				
    		}
    	}    		
    	echo "<li><a class='add-new' href='".URL."show/search/'>Add a Show</a></li>";
        ?>
    </ul>
</div>