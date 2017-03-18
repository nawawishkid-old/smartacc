<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports | SMARTACC</title>
  <?php
    $con = mysqli_connect("localhost", "root", "", "smartacc")
        or die ("Error: connection failed, " . mysqli_connect_error());
    mysqli_query($con, "SET character_set_results=utf8")
        or die ("Error: Cannot set character set result.");
    mysqli_query($con, "SET character_set_client=utf8")  
        or die ("Error: Cannot set character set client.");
    mysqli_query($con, "SET character_set_connection=utf8")
        or die ("Error: Cannot set character set connection.");

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $date = explode("/", $_POST['date']);
      // Set default date to SQL Wildcard
      $year = (!empty($date[0]) ? $date[0] : "%");
      $month = (!empty($date[1]) ? $date[1] : "%");
      $day = (!empty($date[2]) ? $date[2] : "%");
    } else {
      $year = $month = $day = "%";
    }

    // Retrieved date value
    $transaction_type = "EXPENSE";
    //$year = "2016";

    // Send query and fetch data from database
    function fetch($query, $array_format = null) {
      if($array_format !== MYSQLI_ASSOC
          && $array_format !== MYSQLI_NUM
          && $array_format !== MYSQLI_BOTH
        ) {$array_format = null;}
      global $con;
      $result = mysqli_query($con, $query) or die ("Error: could not send query, " . mysqli_error($con));
      if(is_null($array_format)) {
        $rows = mysqli_fetch_all($result);
      } else {
        $rows = mysqli_fetch_all($result, $array_format);
      }
      //print_r($rows);
      return $rows;
    }
    // Show numeric data array
    function data_table($show_type, $thead, $col_head_arr, $data_arr) {
      // Validate arguments
      if(gettype($thead) !== 'string'
         || gettype($col_head_arr) !== 'array'
         || gettype($data_arr) !== "array"
      ) {return false;}
      $colhead = $col_head_arr;
      $table = "<table><thead><td>{$thead}</td></thead><tbody><tr>";
      // Set column header of table
      for($i = 0; $i < count($colhead); $i++) {
        $table .= "<td>{$colhead[$i]}</td>";
      }
      $total_amount = 0;
      if($show_type === "num") {
        for($i = 0; $i < count($data_arr); $i++) {
          $table .= "</tr><tr>";
          for($j = 0; $j < count($data_arr[$i]); $j++) {
            $value = $data_arr[$i][$j];
            if(count($data_arr[$i]) - $j === 1) {
              $total_amount += $value;
              $value = number_format($value, 2);
            } else {
              $value = ucfirst($value);
            }
            $table .= "<td>{$value}</td>";
          }
          $table .= "</tr>";
        }
      } else if($show_type === "assoc") {
        for($i = 0; $i < count($data_arr); $i++) {
          foreach ($data_arr[$i] as $k => $v) {
            $key = ucfirst($k);
            $value = number_format($v, 2);
            $total_amount += $v;
            $table .= "<tr><td>{$key}</td><td>{$value}</td></tr>";
          }
        }
      } else {return false;}
      $table .= "<tr><td>Total</td><td>" . number_format($total_amount, 2) . "</td></tr>";
      $table .= "</tbody></table><hr>";
      echo $table;
    }

    // Fetch total amount based on category
    function totalAmount_category($transaction_type) {
      if($transaction_type !== "in"
          && $transaction_type !== "ex"
        ) {return false;}
      $tr_type = $transaction_type;
      global $year, $month, $day;
      $query = "SELECT DISTINCT {$tr_type}_categories.{$tr_type}_cats AS category, 
                  (
                    SELECT SUM(record.amount)
                    FROM record
                    WHERE transaction_type = '{$tr_type}'
                    AND categories = {$tr_type}_categories.{$tr_type}_cats
                    AND date LIKE '{$year}-{$month}-{$day}'
                  ) AS Amount
                FROM {$tr_type}_categories
                INNER JOIN record
                ON {$tr_type}_categories.{$tr_type}_cats = record.categories
                ORDER BY Amount DESC;";
      return fetch($query);
    }
    // Fetch total amount based on necessity
    function totalAmount_necessity() {
      global $year, $month, $day;
      $query = "SELECT 
                (
                    SELECT SUM(amount)
                    FROM record
                    WHERE transaction_type = 'ex'
                    AND necessity = 0
                    AND date LIKE '{$year}-{$month}-{$day}'
                ) AS unneccessary,
                (
                    SELECT SUM(amount)
                    FROM record
                    WHERE transaction_type = 'ex'
                    AND necessity = 1
                    AND date LIKE '{$year}-{$month}-{$day}'
                ) AS necessary;";
      //echo $query;
      return fetch($query, MYSQLI_ASSOC);
    }
    // Fetch total amount based on income type
    function totalAmount_incomeType() {
      global $year, $month, $day;
      $query = "SELECT 
                (
                    SELECT SUM(amount)
                    FROM record
                    WHERE transaction_type = 'in'
                    AND in_type = 'act'
                    AND date LIKE '{$year}-{$month}-{$day}'
                ) AS active,
                (
                    SELECT SUM(amount)
                    FROM record
                    WHERE transaction_type = 'in'
                    AND in_type = 'pas'
                    AND date LIKE '{$year}-{$month}-{$day}'
                ) AS passive;";
      return fetch($query, MYSQLI_ASSOC);
    }
    function totalAmount_person($person) {
      if($person !== 'payer'
          && $person !== 'payee')
        {return false;}
      $tr_type = ($person === 'payer' ? 'in' : 'ex');
      global $year, $month, $day;
      $query = "SELECT DISTINCT p.{$person} AS person,
                (
                    SELECT SUM(amount)
                    FROM record a
                    WHERE a.{$person} = p.{$person}
                    AND transaction_type = '{$tr_type}'
                    AND date LIKE '{$year}-{$month}-{$day}'
                ) AS total_amount
                FROM record p
                INNER JOIN record a
                ON p.{$person} = a.{$person}
                ORDER BY total_amount DESC
                LIMIT 10;";
      return fetch($query);
    }
    function totalAmount_subcat($transaction_type) {
      if($transaction_type !== "in"
          && $transaction_type !== "ex"
      ) {return false;}
      $tr_type = $transaction_type;
      global $year, $month, $day;
      $query = "SELECT DISTINCT
                  a.{$tr_type}_subcats AS Sub_category,
                  b.{$tr_type}_cats AS Category,
                  (
                    SELECT SUM(amount)
                    FROM record
                    WHERE transaction_type = '{$tr_type}'
                    AND subcategories = Sub_category
                    AND categories = Category
                    AND date LIKE '{$year}-{$month}-{$day}'
                  ) AS Amount
                FROM {$tr_type}_categories a
                INNER JOIN {$tr_type}_categories b
                ON a.{$tr_type}_subcats = b.{$tr_type}_subcats
                ORDER BY Amount DESC;";
      return fetch($query);
    }
    // Fetch total amount based on account
    function totalAmount_account() {
      global $year, $month, $day;
      $query = "SELECT DISTINCT
                  account AS Account,
                  (
                    (
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   acc = Account
                          AND   transaction_type = 'in'
                          AND   date BETWEEN
                              (
                                SELECT MIN(date)
                                FROM record
                              ) AND '{$year}-{$month}-{$day}'
                      )
                      +
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   to_acc = Account
                          AND   transaction_type = 'tr'
                          AND   date BETWEEN
                              (
                                SELECT MIN(date)
                                FROM record
                              ) AND '{$year}-{$month}-{$day}'
                      )
                    )
                    -
                    (
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   acc = Account
                          AND   transaction_type = 'ex'
                          AND   date BETWEEN
                              (
                                    SELECT MIN(date)
                                    FROM record
                                ) AND '{$year}-{$month}-{$day}'
                      )
                      +
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   from_acc = Account
                          AND   transaction_type = 'tr'
                          AND   date BETWEEN
                              (
                                SELECT MIN(date)
                                FROM record
                              ) AND '{$year}-{$month}-{$day}'
                      )
                    )
                  ) AS Remain
                FROM account;";
      return fetch($query);
    }
    // Fetch total amount based on sub account
    function totalAmount_subaccount() {
      $query = "SELECT DISTINCT
                  s.sub_account AS Sub_account,
                  a.account AS Account,
                  (
                    (
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   subacc = s.sub_account
                          AND   transaction_type = 'in'
                      )
                      +
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   to_subacc = s.sub_account
                          AND   transaction_type = 'tr'
                      )
                  )
                  -
                  (
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   subacc = s.sub_account
                          AND   transaction_type = 'ex'
                      )
                      +
                      (
                        SELECT  IFNULL(SUM(amount), 0)
                        FROM    record
                        WHERE   from_subacc = s.sub_account
                          AND   transaction_type = 'tr'
                      )
                    )
                  ) AS Remain
                FROM account s
                INNER JOIN account a
                ON s.account = a.account;";
    }
  ?>
</head>
<body>
  <form method="POST">
    <input type="text" name="date" id="date" placeholder="yyyy/mm/dd">
    <button>Submit</button>
  </form>
  <p>Date: <?php echo "{$year}-{$month}-{$day}";?></p>
  <?php 
    data_table("num", "Account", ["Account", "Remain"], totalAmount_account());
    data_table("num", "Expense", ["Category", "Amount"], totalAmount_category("ex"));
    data_table("assoc", "Necessity", ["Necessity", "Amount"], totalAmount_necessity());
    data_table("num", "Subcategory", ["Subcategory", "Category", "Amount"], totalAmount_subcat('ex'));
    data_table("num", "Payee", ["Category", "Amount"], totalAmount_person('payee'));
    data_table("num", "Income", ["Category", "Amount"], totalAmount_category("in"));
    data_table("assoc", "Income type", ["Income type", "Amount"], totalAmount_incomeType());
    data_table("num", "Subcategory", ["Subcategory", "Category", "Amount"], totalAmount_subcat('in'));
    //data_table("num", "Payer", ["Category", "Amount"], totalAmount_person('payer'));

    mysqli_close($con);
  ?>
</body>
</html>