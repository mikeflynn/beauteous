<?php
class beauteous
{
	private $rows = array();
	protected $horz_border_char = '-';
	protected $vert_border_char = '|';
	protected $col_width = array();
	
	private static $fg_colors = array();
	private static $bg_colors = array();
	
	public function __construct()
	{
		self::$fg_colors = array(
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
		
		self::$bg_colors = array(
			'black' 		=> '40',
			'red'			=> '41',
			'green' 		=> '42',
			'yellow'		=> '43',
			'blue'			=> '44',
			'magenta'		=> '45',
			'cyan'			=> '46',
			'light_gray'	=> '47'
		);		
	}
	
	public function draw()
	{
		if(count($this->rows) == 0) return false;
			
		// Figure out the max field width
		$max_width = array();
		
		foreach($this->rows as $row)
		{
			$widths = $row->column_widths();
			foreach($widths as $key=>$width)
			{
				if($width > $max_width[$key]) $max_width[$key] = $width;
			}
		}
					
		// Combine the rows
		$row_strings = array();
		foreach($this->rows as $key=>$row)
		{
			$row->draw($max_width, $this->get_vert_border());
		}
		
		// Make the divider
		$divider = '';
		for($i=0;$i<strlen($this->rows[0]->get_output());$i++)
		{
			$divider .= $this->get_horz_border();
		}		
		
		$table_string = $divider."\n";
		foreach($this->rows as $row)
		{
			 $table_string.= $row->get_output()."\n";
			 if($row->is_header()) $table_string .= $divider."\n";
		}
		
		return $table_string.$divider."\n";
	}
	
	public function row($data = null)
	{
		$row = new beauteous_row($data);
		$this->rows[] = $row;
		return $row;
	}
	
	public function get_horz_border()
	{
		return $this->horz_border_char;
	}
	
	public function get_vert_border()
	{
		return $this->vert_border_char;
	}	
	
	public static function colorize($string, $foreground, $background)
	{
		if(empty($foreground) && empty($background)) return $string;
		
		$colorized = '';
	
		if(isset(self::$bg_colors[$background]))
		{
			$colorized .= "\033[".self::$bg_colors[$background]."m"; 
		}
		
		if(isset(self::$fg_colors[$foreground]))
		{
			$colorized .= "\033[".self::$fg_colors[$foreground]."m"; 
		}
		
		return $colorized .= $string."\033[0m";
	}
	
	public static function valid_color($color, $type = 'bg')
	{
		if($type == 'bg' && isset(self::$bg_colors[$color]))
		{
			return true;
		}
		else if($type == 'fg' && isset(self::$fg_colors[$color]))
		{
			return true;
		}

		return false;
	}
}

class beauteous_row
{
	protected $items = array();
	protected $foreground = null;
	protected $background = null;
	protected $header = false;
	protected $output = '';
	
	public function __construct($items = null)
	{
		if(is_array($items))
		{
			foreach($items as $row)
			{
				$this->cell($row);
			}
		}
	}
	
	public function set_output($string)
	{
		if(!empty($string)) $this->output = $string;
	}
	
	public function get_output()
	{
		return $this->output;
	}
	
	public function make_header()
	{
		$this->header = true;
	}
	
	public function is_header()
	{
		return $this->header;
	}
	
	public function foreground($color)
	{
		if(beauteous::valid_color($color, 'fg'))
		{
			$this->foreground = $color;
			return true;
		}
		
		return false;
	}
	
	public function background($color)
	{
		if(beauteous::valid_color($color, 'bg'))
		{
			$this->background = $color;
			return true;
		}

		return false;
	}
	
	protected function pad($string, $width)
	{
		if(strlen($string) >= $width) return $string;
		
		$difference = $width - strlen($string);
		for($i = 0; $i < $difference; $i++)
		{
			$string .= ' ';
		}
		
		return $string;
	}
	
	public function width()
	{
		$max_width = 0;
		foreach($this->items as $item)
		{
			if(strlen($item['data']) > $max_width) $max_width = strlen($item['data']);
		}
		
		return $max_width;
	}
	
	public function cell($data, $bg_color = null, $fg_color = null)
	{
		$this->items[] = array(
			'data'	=> $data,
			'bg'	=> $bg_color,
			'fg'	=> $fg_color
		);
		
		return $this;
	}
	
	public function draw($width, $border)
	{
		$string = '';
		
		foreach($this->items as $key=>$item)
		{	
			$data = $this->pad($item['data'], $width[$key]);
			
			if($this->is_header()) $data = strtoupper($data);
			
			$string .= $border.' '.beauteous::colorize($data, $item['fg'], $item['bg']).' ';
		}
		
		$string .= $border;
		
		$this->set_output(beauteous::colorize($string, $this->foreground, $this->background));
	}
	
	public function column_widths()
	{
		$widths = array();
		foreach($this->items as $key=>$item)
		{
			$widths[$key] = strlen($item['data']);
		}
		
		return $widths;
	}
}
?>