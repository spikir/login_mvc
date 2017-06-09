<?php include ('header.php')?>
<div class="boxcontent">
	<?php
		/*echo '<table>';
		while($row = mysqli_fetch_assoc($result)) {
			echo '<tr>';
				echo '<td>'.$row['product_id'].'</td>';
				echo '<td>'.$row['product_name'].'</td>';
				echo '<td>'.$row['product_desc'].'</td>';
				echo '<td>'.$row['product_price'].'</td>';
			echo '</tr>';
		}
		echo '</table>';*/
		echo '<table>';
			echo '<tbody>';
				foreach($products as $key => $value) {
					echo '<tr>';
						echo '<td>'.$value['product_id'].'</td>';
						echo '<td><img src="'.$value['product_image'].'" /></td>';
						echo '<td>'.$value['product_title'].'</td>';
						echo '<td>'.$value['product_desc'].'</td>';
						echo '<td>'.$value['product_price'].'</td>';
						/*foreach($value as $key2 => $value2) {
							if($key2 == 'product_image') {
								echo '<td><img src="'.$value2.'" /></td>';
							} else {
								echo '<td>'.$value2.'</td>';
							}
						}*/
					echo '</tr>';
				}
			echo '</tbody>';
		echo '</table>';
	?>
</div>
<?php include ('footer.php')?>