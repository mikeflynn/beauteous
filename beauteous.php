<?php
class beauteous
{
	protected $buffer = array();
	protected $horz_border_char = '-';
	protected $vert_border_char = '|';
	protected $max_width = 0;
	
	public function __construct()
	{
		
	}
	
	public function draw()
	{
		if(!empty($this->buffer))
		{
			foreach($this->buffer as $line)
			{
				echo "{$line}\n";
			}
		}
	}
	
	public function column($string, $width = 'auto')
	{
		
	}
	
	public function row($string, $width = 'auto')
	{
		
	}
	
	protected function set_width($width)
	{
	
	}
	
	protected function pad($string, $width)
	{
		
	}
	
	public function colorize($string, $foreground, $background)
	{
		$colorized = '';
	
		$fg_colors = array(
			'black'			=> '0;30',
			'dark_grey'		=> '1;30',
			'blue'			=> '0;34',
			'light_blue'	=> '1;34',
			'green'			=> '0;32',
			'light_green'	=> '1;32',
			'cyan'			=> '0;36',
			'light_cyan'	=> '1;36',
			'red'			=> '0;31',
			'light_red' 	=> '1;31',
			'purple'		=> '0;35',
			'light_purple'	=> '1;35',
			'brown'			=> '0;33',
			'yellow'		=> '1;33',
			'light_grey'	=> '0;37',
			'white'			=> '1;37'
		);
		
		$bg_colors = array(
			'black' 		=> '40',
			'red'			=> '41',
			'green' 		=> '42',
			'yellow'		=> '43',
			'blue'			=> '44',
			'magenta'		=> '45',
			'cyan'			=> '46',
			'light_gray'	=> '47'
		);
		
		if(isset($bg_colors[$background]))
		{
			$colorized .= "\033[{$bg_colors[$background]}m"; 
		}
		
		if(isset($fg_colors[$foreground]))
		{
			$colorized .= "\033[{$fg_colors[$foreground]}m"; 
		}
		
		return $colorized .= $string."\033[0m";
	}
}
?>