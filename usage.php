<?php
require_once('beauteous.php');

$tabular_data = array(
	array(
		'last' => 'flynn',
		'first'=> 'mike',
		'twitter'=>'mikeflynn_',
		'website'=>'punchingkitty.com'
	),
	array(
		'last' => 'gruber',
		'first'=> 'john',
		'twitter'=>'gruber',
		'website'=>'daringfireball.com'
	),
	array(
		'last' => 'hilton',
		'first'=> 'perez',
		'twitter'=>'perezhilton',
		'website'=>'perezhilton.com'
	)
);

$table = new beauteous();

$table->row(array_keys($tabular_data[0]));

$colors = array('blue', 'red', 'yellow');
foreach($tabular_data as $chunk)
{
	$row = $table->row($chunk);
	$row->background($colors[array_rand($colors)]);
}

echo $table->draw();
?>