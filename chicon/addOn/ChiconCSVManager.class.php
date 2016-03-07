<?php
	
	class ChiconCSVManager{
		private $csv_data;
		
		public function __construct($csvURL,$delimiter=";",$has_headings=true){
			$this->csv_data = file_get_contents($csvURL);
			$this->csv_array =  $this->csv_to_array($this->csv_data,$has_headings,$delimiter);
		}
		private function csv_to_array($csv,$has_headings=false,$delimiter=',',$enclosure='"',$escape="\\",$eol="\n")
	{
			$headings = null;
			$data     = array();
			$csv      = str_getcsv($csv, $eol); // Array containing each line
			if(empty($csv[0]))
			{
				return array();
			}
			if($has_headings)
			{
				$headings = str_getcsv($csv[0], $delimiter, $enclosure, $escape);
				
				array_shift($csv); // Remove the heading row and reset keys
			}
	
			$count = count($csv);
	
			for($i = 0; $i < $count; $i++)
				{
					$data[$i] = str_getcsv($csv[$i], $delimiter, $enclosure, $escape);
		
					if(isset($headings))
					{
						if(count($data[$i]) == count($headings)){ //check if data is compatible
						$data[$i] = array_combine($headings, $data[$i]);
						}						
					}
				}
			return $data; 
	}

		public function getCSVArray(){
			return $this->csv_array;
		
		}
		
		public function getCSVRow($column,$searchString){		
			$key = array_search($searchString, array_column($this->csv_array, $column));
			if($key !=null){
				return $this->csv_array[$key];
			}
			return null;
		}
	
	}

?>