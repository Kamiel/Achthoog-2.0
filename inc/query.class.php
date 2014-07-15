<?php
	class query {
		public function getResult($select, $from, $join, $where, $order) {
			if (isset($query)) echo $query;
			$query = "SELECT ";
			foreach ($select as $s) {
				$query .= $s;
				if ($s !== end($select)) $query .= ", "; // add “, ” if it’s not the last element of the array
			}
			(isset($from[1])) ? ($as = " AS " . $from[1]) : ($as = ""); // AS-statement defined?
			$query .= " FROM " . $from[0] . $as;
			if ($join) {
				foreach ($join as $j) {
					($j[1]) ? ($as = " AS " . $j[1]) : ($as = ""); // AS-statement defined?
					$query .= " JOIN " . $j[0] . $as . " ON " . $j[2]; // combine statements of JOIN, AS and ON
				}
			}
			if ($where) $query .= " WHERE " . $where;
			if ($order) {
				$query .= " ORDER BY ";
				if (is_array($order)) {
					foreach ($order as $o) {
						$query .= $o;
						if ($o !== end($order)) $query .= ", "; // add “, ” if it’s not the last element of the array
					}
				} else {
					$query .= $order;
				}
			}
			//echo $query . '<br><br><br>';
		
			require "db_connect.php";
			$result = $dbconn->query($query);
			require "db_close.php";
			$x = 0;	
			$return_result = NULL;
			while ($fetch = $result->fetch_assoc()) {
				$return_result[$x] = $fetch;
				$x++;
			}
			return $return_result;
		}
	}
?>