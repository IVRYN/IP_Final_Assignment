<?php

include ("config/mysql_connect.php");
$errors     =   array();
$success    =   array();

/*
 *  @params username -> string
 *  @params password -> string
 *
 *  @brief
 *  Logins in the customer and admin accounts
 *
 *  @return void
 */
function login_user($username, $password)
{
    global $errors;
    global $dbconnect;

    if (!empty($username))
    {
        $username   =   evaluate($username);
    } else
    {
        array_push($errors, "Username is empty");
    }

    if (!empty($password))
    {
        $password   =   evaluate($password);
    } else
    {
        array_push($errors, "Password is empty");
    }

    if (count($errors) == 0)
    {
        $password       =   md5($password);

        $user_query     =   sprintf("SELECT * FROM customer
                                 WHERE username='%s'
                                 AND password='%s'",
                                 $username,
                                 $password
                               );


        $user_result    =   mysqli_query ($dbconnect, $user_query) or die (mysql_error());

        $user_row       =   mysqli_num_rows($user_result);

        if ($user_row == 1)
        {
            $user_id    =   mysqli_fetch_row($user_result);

            $_SESSION['username']       =   $username;
            $_SESSION['user_id']        =   $user_id[0];
            $_SESSION['authorization']  =   'user';
            $_SESSION['login']          =   true;

            header('Location: dashboard.php');
        } else
        {
            $admin_query    =   sprintf("SELECT * FROM admin
                                         WHERE username='%s'
                                         AND password='%s'",
                                         $username,
                                         $password
                                       );

            $admin_result   =   mysqli_query($dbconnect, $admin_query);

            $admin_row      =   mysqli_num_rows($admin_result);

            if ($admin_row  ==  1)
            {
                $_SESSION['username']        =   $username;
                $_SESSION['authorization']   =   'admin';
                $_SESSION['login']           =   true;

                header('Location: dashboard.php');
            } else
            {
                array_push($errors, "Username/Password is incorrect or does not exist");
            }
        }
    }

    mysqli_close($dbconnect);
}

/*
 *  @param  f_name              ->  string
 *  @param  l_name              ->  string
 *  @param  mobilehp            ->  string
 *  @param  username            ->  string
 *  @param  password            ->  string
 *  @param  confirm_password    ->  string
 *
 *  @brief
 *  The function checks the list of registered phone numbers and a list of special usernames,
 *  before registering the user.
 *
 *  @return void
 *
 */
function register_user($f_name, $l_name, $mobilehp, $username, $password, $confirm_password)
{
    global $errors;
    global $success;
    global $dbconnect;

    if (!empty($f_name))
        $f_name     =   evaluate($f_name);
    else
        array_push($errors, "The first name is empty");

    if (!empty($l_name))
        $l_name     =   evaluate($l_name);
    else
        array_push($errors, "The last name is empty");

    if (!empty($mobilehp))
        $mobilehp   =   evaluate($mobilehp);
    else
        array_push($errors, "The mobile phone is empty");

    if (!empty($username))
        $username   =   evaluate($username);
    else
        array_push($errors, "The username is empty");

    if (!empty($password))
    {
        if ($password != $confirm_password)
            array_push($errors, "The password does not match");
        else
            $password   =   evaluate($password);
    } else
    {
        array_push($errors, "The password is empty");
    }

    if (count($errors) == 0)
    {
        $username_exist     =   registration_check($username);

        if (!$username_exist)
        {
            //  Check if phone number already exist
            $user_phone_check   =   sprintf("SELECT customer_id
                                             FROM customer
                                             WHERE mobilehp='%s'",
                                             $mobilehp
                                           );

            $user_phone_result  =   mysqli_query($dbconnect, $user_phone_check);

            if (mysqli_num_rows($user_phone_result) == 0)
            {
                $password       =   md5($password);

                $register_user  =   sprintf("INSERT INTO customer
                                             (f_name, l_name, mobilehp, username, password)
                                             VALUES
                                             ('%s', '%s', '%s', '%s', '%s')",
                                             $f_name,
                                             $l_name,
                                             $mobilehp,
                                             $username,
                                             $password
                                           );

                $register_user_result   =   mysqli_query($dbconnect, $register_user);

                //  Check if users has been successfully registered
                if ($register_user_result)
                    array_push($success, "Successfully Registered");
                else
                    array_push($errors, "Something went wrong with the register query");

            } else
            {
                array_push($errors, "Mobile phone number has already been registered");
            }
        } else
        {
            array_push($errors, "The username has already been registered");
        }
    }

    mysqli_close($dbconnect);
}

/*
 *  @param  username    ->  string
 *
 *  @brief
 *  check if username is matched with any registered users or admin.
 *
 *  @return void
 */
function registration_check($username)
{
    global $dbconnect;

    // check admin users if any matching user exist
    $admin_username_query   =   sprintf("SELECT username
                                         FROM admin
                                         WHERE username = '%s'",
                                         $username
                                        );

    $admin_username_result  =   mysqli_query($dbconnect, $admin_username_query);

    //  Check if username exist in admin database
    $admin_username_exist;

    if(mysqli_num_rows($admin_username_result) == 0)
        $admin_username_exist   =   false;
    else
        $admin_username_exist   =   true;

    // check user if any matching user exist
    $user_username_query    =   sprintf("SELECT username
                                         FROM customer
                                         WHERE username = '%s'",
                                         $username
                                        );

    $user_username_result   =   mysqli_query($dbconnect, $user_username_query);

    $user_username_exist;

    if (mysqli_num_rows($user_username_result) == 0)
        $user_username_exist    =   false;
    else
        $user_username_exist    =   true;

    //  Check whether both exist in both
    if (!($admin_username_exist) && !($user_username_exist))
        return false;
    else
        return true;
}

/*
 *  @param  user_id     ->  integer
 *
 *  @brief
 *  queries the database for associated tickets based on the user id
 *
 *  @return void
 */
function user_booked_ticket_view($user_id)
{
    global $dbconnect;
    $user_id    =   $user_id;

    $user_booked_ticket_query   =   sprintf("SELECT b.booking_id,
                                                    depart_date,
                                                    depart_time,
                                                    journey,
                                                    depart_station,
                                                    dest_station
                                             FROM busbooking AS b, customer_busbooking AS cb
                                             WHERE cb.customer_id='%d'
                                             AND cb.booking_id=b.booking_id
                                             ORDER BY b.booking_id ASC", $user_id);

    $user_booking_ticket_result =   mysqli_query($dbconnect, $user_booked_ticket_query);

    $user_number_tickets        =   mysqli_num_rows($user_booking_ticket_result);

    if ($user_number_tickets > 0)
    {
        echo "<div class=\"col-10 col-sm-10\">
                  <h2>There is currently $user_number_tickets booked.</h2>
              </div>
              <div class=\"col-2 col-sm-2\">
                  <a class=\"float-end btn btn-success\" href=\"add_booking.php\">
                      <span style=\"vertical-align: middle;\" class=\"material-icons\">add</span>
                  </a>
              </div>
              <div class=\"table-responsive\">
                  <table class=\"table table-sm\">
                      <thead>
                          <tr>
                              <th scope=\"col\">Booking #</th>
                              <th scope=\"col\">Date of Departure</th>
                              <th scope=\"col\">Time of Departure</th>
                              <th scope=\"col\">Station of Departure</th>
                              <th scope=\"col\">Destined Station</th>
                              <th scope=\"col\">Journey</th>
                              <th scope=\"col\">User Control</th>
                          </tr>
                      </thead>
                      <tbody>
             ";

        //  Print ticket data per row
        while ($ticket_rows     =   mysqli_fetch_assoc($user_booking_ticket_result))
        {
            echo "<tr>
                      <td class=\"align-middle\">" . $ticket_rows['booking_id'] . "</td>
                      <td class=\"align-middle\">" . $ticket_rows['depart_date'] . "</td>
                      <td class=\"align-middle\">" . $ticket_rows['depart_time'] . "</td>
                      <td class=\"align-middle\">" . station_format($ticket_rows['depart_station']) . "</td>
                      <td class=\"align-middle\">" . station_format($ticket_rows['dest_station']) . "</td>
                      <td class=\"align-middle\">" . $ticket_rows['journey'] . "</td>
                      <td>
                          <form action=\"dashboard.php\" method=\"get\">
                              <button class=\"btn btn-primary\" type=\"submit\" name=\"edit_booking\" value=" . $ticket_rows['booking_id'] . " formaction=\"edit_booking.php\">
                                  <span style=\"vertical-align: middle;\" class=\"material-icons\">edit</span>
                              </button>
                              <button class=\"btn btn-danger\" type=\"submit\" name=\"cancel_booking\" value=" . $ticket_rows['booking_id'] . ">
                                  <span style=\"vertical-align: middle;\" class=\"material-icons\">delete</span>
                              </button>
                          </form>
                      </td>
                  </tr>
                 ";
        }

        echo "        </tbody>
                  </table>
              </div>
             ";

        mysqli_free_result($user_booking_ticket_result);
    } else
    {
        echo "<div class=\"col-sm-10\">
                  <h2>There is currently $user_number_tickets booked.</h2>
              </div>
              <div class=\"col-sm-2\">
                  <a class=\"float-end btn btn-primary\" href=\"add_booking.php\">
                      <span style=\"vertical-align: middle;\" class=\"material-icons\">add</span>
                  </a>
              </div>";
    }

    mysqli_close($dbconnect);
}

/*
 *  @param  username    ->  string
 *
 *  @brief
 *  This function is for the admin to view the available user bookings
 *  It uses the username input to search for the matching user booking based
 *  on the username.
 *
 *  @return void
 */
function admin_user_booking_view($username = "")
{
    global $dbconnect;

    $admin_view_ticket_query;

    if (!empty($username))
    {
        $admin_view_ticket_query    =   sprintf("SELECT b.booking_id,
                                                        c.customer_id,
                                                        c.username,
                                                        c.f_name,
                                                        c.l_name,
                                                        b.depart_date,
                                                        b.depart_time,
                                                        b.depart_station,
                                                        b.dest_station
                                                 FROM customer AS c, busbooking AS b, customer_busbooking AS cb
                                                 WHERE cb.customer_id = c.customer_id
                                                 AND cb.booking_id = b.booking_id
                                                 AND c.username = '%s'
                                                 ORDER BY booking_id DESC",
                                                 $username
                                                );
    } else
    {
        $admin_view_ticket_query    =   "SELECT b.booking_id,
                                                c.customer_id,
                                                c.username,
                                                c.f_name,
                                                c.l_name,
                                                b.depart_date,
                                                b.depart_time,
                                                b.depart_station,
                                                b.dest_station
                                         FROM customer AS c, busbooking AS b, customer_busbooking AS cb
                                         WHERE cb.customer_id = c.customer_id
                                         AND cb.booking_id = b.booking_id
                                         ORDER BY booking_id DESC";
    }

    $admin_view_ticket_result   =   mysqli_query($dbconnect, $admin_view_ticket_query);

    $number_of_tickets              =   mysqli_num_rows($admin_view_ticket_result);

    if ($number_of_tickets > 0)
    {
        echo "<div class=\"col-sm-8 justify-content-center\">
                  <h2>There is currently $number_of_tickets booked.</h2>
              </div>
              <div class=\"col-sm-4\">
                  <form action=\"dashboard.php\" method=\"post\">
                      <div class=\"input-group\">
                          <input class=\"form-control\" type=\"text\" name=\"search\" />
                          <button class=\"btn btn-outline-primary\" type=\"submit\" name=\"search_user\">
                              <span style=\"vertical-align: middle;\" class=\"material-icons\">search</span>
                          </button>
                      </div>
                  </form>
              </div>
              <div class=\"table-responsive\">
                  <table class=\"table\">
                      <thead>
                          <tr>
                              <th scope=\"col\">#</th>
                              <th scope=\"col\">Username</th>
                              <th scope=\"col\">First Name</th>
                              <th scope=\"col\">Last Name</th>
                              <th scope=\"col\">Date of Departure</th>
                              <th scope=\"col\">Time of Departure</th>
                              <th scope=\"col\">Station of Departure</th>
                              <th scope=\"col\">Destined Station</th>
                              <th scope=\"col\">Admin Control</th>
                          </tr>
                      </thead>
                      <tbody>
             ";

        while ($ticket_rows     =   mysqli_fetch_assoc($admin_view_ticket_result))
        {
            echo "<tr>
                      <td>" . $ticket_rows['booking_id'] . "</td>
                      <td>" . $ticket_rows['username'] . "</td>
                      <td>" . $ticket_rows['f_name'] . "</td>
                      <td>" . $ticket_rows['l_name'] . "</td>
                      <td>" . $ticket_rows['depart_date'] . "</td>
                      <td>" . $ticket_rows['depart_time'] . "</td>
                      <td>" . station_format($ticket_rows['depart_station']) . "</td>
                      <td>" . station_format($ticket_rows['dest_station']) . "</td>
                      <td>
                          <form action=\"dashboard.php\" method=\"post\">
                              <button class=\"btn btn-danger\" type=\"submit\" name=\"cancel_booking\" value=" . $ticket_rows['booking_id'] . ">
                                  <span style=\"vertical-align: middle;\" class=\"material-icons\">delete</span>
                              </button>
                          </form>
                      </td>
                  </tr>
                 ";
        }

        echo "        </tbody>
                  </table>
              </div>
             ";

        mysqli_free_result($admin_view_ticket_result);
    } else
    {
        echo "<div class=\"col-sm-12 justify-content-center\">
                  <h2>There is currently $number_of_tickets booked.</h2>
              </div>";
    }

    mysqli_close($dbconnect);
}

/*
 *  @param  user_id         ->  integer
 *  @param  depart_date     ->  string
 *  @param  depart_time     ->  string
 *  @param  depart_station  ->  string
 *  @param  dest_station    ->  string
 *
 *  @brief
 *
 *  @return
 *
 */
function user_add_booking($user_id, $depart_date, $depart_time, $depart_station, $dest_station)
{
    global $errors;
    global $dbconnect;

    if (!empty($depart_date))
        $depart_date    =   evaluate($depart_date);
    else
        array_push($errors, "The date of departure has not been set");

    if (!empty($depart_time))
        $depart_time    =   evaluate($depart_time);
    else
        array_push($errors, "The time of departure has not been set");

    if (!empty($depart_station))
        $depart_station =   evaluate($depart_station);
    else
        array_push($errors, "The station of departure has not been set");

    if (!empty($dest_station))
    {
        $dest_station =   evaluate($dest_station);

        if ($depart_station == $dest_station)
            array_push($errors, "The station of departure and the destined station cannot be the same");

    } else
    {
        array_push($errors, "The destined station has not been set");
    }

    if (count($errors) == 0)
    {
        $journey        =   calculate_journey($depart_station, $dest_station);

        $user_add_booking_query     =   sprintf("INSERT INTO busbooking
                                                 (depart_date, depart_time, depart_station, dest_station, journey)
                                                 VALUES
                                                 ('%s', '%s', '%s', '%s', '%s')",
                                                 $depart_date,
                                                 $depart_time,
                                                 $depart_station,
                                                 $dest_station,
                                                 $journey
                                                );

        $user_add_booking_result    =   mysqli_query($dbconnect, $user_add_booking_query);

        if ($user_add_booking_result)
        {
            $user_booking_id        =   mysqli_insert_id($dbconnect);

            $user_assoc_booking_query   =   sprintf("INSERT INTO customer_busbooking
                                                    (customer_id, booking_id)
                                                    VALUES
                                                    ('%d','%d')",
                                                    $user_id,
                                                    $user_booking_id
                                                  );

            $user_assoc_booking_result  =   mysqli_query($dbconnect, $user_assoc_booking_query);

            if ($user_assoc_booking_result)
            {
                header('Location: dashboard.php');
            } else
            {
                array_push($errors, "An error has occured during booking redirecting");
            }

        } else
        {
            array_push($errors, "An error has occured during booking the ticket.");
        }
    }

    mysqli_close($dbconnect);
}

/*
 *  @param  user_id     ->  integer
 *  @param  booking_id  ->  integer
 *
 *  @brief
 *  Delete the user booking based on the user id and booking id
 *  check for the user id through the sessions before deleting the booking
 *  practically, needs user permissions.
 *
 *  @return void
 */
function user_delete_booking($user_id, $booking_id)
{
    global $dbconnect;
    global $errors;
    global $success;

    //  delete connected booking row based on the customer id and the booking id
    $user_delete_booking_query  =   sprintf("DELETE customer_busbooking, busbooking
                                             FROM customer_busbooking
                                             INNER JOIN busbooking ON busbooking.booking_id = customer_busbooking.booking_id
                                             WHERE customer_busbooking.customer_id = '%d'
                                             AND customer_busbooking.booking_id = '%d'",
                                             $user_id,
                                             $booking_id
                                            );

    $user_delete_booking_result =   mysqli_query($dbconnect, $user_delete_booking_query);

    if ($user_delete_booking_result)
        array_push($success, "Booking ID $booking_id has been successfully cancelled");
    else
        array_push($errors, "Something went wrong during cancellation");

    mysqli_close($dbconnect);
}

/*
 *  @param  booking_id  ->  interger
 *
 *  @brief
 *  delete the user booking based on the booking_id
 *  No user permissions needed or user id based checking
 *
 *  @return void
 */
function admin_delete_booking($booking_id)
{
    global $dbconnect;
    global $errors;
    global $success;

    $admin_delete_booking_query     =   sprintf("DELETE customer_busbooking, busbooking
                                                 FROM customer_busbooking
                                                 INNER JOIN busbooking ON busbooking.booking_id = customer_busbooking.booking_id
                                                 WHERE customer_busbooking.booking_id = '%d'",
                                                 $booking_id
                                               );

    $admin_delete_booking_result    =   mysqli_query($dbconnect, $admin_delete_booking_query);

    if ($admin_delete_booking_result)
        array_push($success, "Booking ID $booking_id has been successfully cancelled");
    else
        array_push($errors, "Something went wrong during cancellation");

    mysqli_close($dbconnect);
}

/*
 *  @param  booking_id      ->  integer
 *  @param  depart_date     ->  string
 *  @param  depart_time     ->  string
 *  @param  depart_station  ->  string
 *  @param  dest_station    ->  string
 *
 *  @brief
 *  The function takes in the request from the edit_booking page.
 *  validates the user ID and modifys the data in the database
 *
 *  @return void
 */
function user_edit_confirm($booking_id, $depart_date, $depart_time, $depart_station, $dest_station)
{
    global $dbconnect;
    global $errors;

    if (!empty($depart_date))
        $depart_date    =   evaluate($depart_date);
    else
        array_push($errors, "The date of departure is set to the wrong format");

    if (!empty($depart_time))
        $depart_time    =   evaluate($depart_time);
    else
        array_push($errors, "The departure time is set to the wrong format");

    if (!empty($depart_station))
        $depart_station =   evaluate($depart_station);
    else
        array_push($errors, "The station of departure has the wrong value");

    if (!empty($dest_station))
    {
        $dest_station   =   evaluate($dest_station);

        if ($depart_station == $dest_station)
            array_push($errors, "The station of departure cannot be the same as the destination station");
    } else
    {
        array_push($errors, "The destination station has the wrong value");
    }

    if (count($errors) == 0)
    {
        $journey        =   calculate_journey($depart_station, $dest_station);

        $user_edit_confirm_query    =   sprintf("UPDATE busbooking
                                                 SET depart_date = '%s',
                                                     depart_time = '%s',
                                                     depart_station = '%s',
                                                     dest_station = '%s',
                                                     journey = '%s'
                                                 WHERE booking_id = '%d'
                                                 LIMIT 1",
                                                 $depart_date,
                                                 $depart_time,
                                                 $depart_station,
                                                 $dest_station,
                                                 $journey,
                                                 $booking_id
                                                );

        $user_edit_confirm_result   =   mysqli_query($dbconnect, $user_edit_confirm_query);

        if (mysqli_affected_rows($dbconnect))
            header('Location: dashboard.php');
        else
            array_push($errors, "No changes has been made to the booking information, please contact an administrator");
    }

    mysqli_close($dbconnect);
}

/*
 *  @param  depart_station  ->  string
 *  @param  dest_station    ->  string
 *
 *  @brief
 *  Takes in the key string associated with station and matches it with a value.
 *
 *  @return journey         ->  string
 */
function calculate_journey($depart_station, $dest_station)
{
    $journey    =   [
                    "kl_sentral"        =>  [
                                            "kl_sentral"        =>  "0hr",
                                            "bt_pahat"          =>  "4hr 45mins",
                                            "kuantan_sentral"   =>  "3hr 58mins",
                                            "kt_terminal"       =>  "4hr 59mins",
                                            "jengka_sentral"    =>  "9hr 8mins"
                                            ],
                    "bt_pahat"          =>  [
                                            "kl_sentral"        =>  "4hr 45mins",
                                            "bt_pahat"          =>  "0hr",
                                            "kuantan_sentral"   =>  "7hr 33mins",
                                            "kt_terminal"       =>  "6hr 20mins",
                                            "jengka_sentral"    =>  "4hr 36mins"
                                            ],
                    "kuantan_sentral"   =>  [
                                            "kl_sentral"        =>  "3hr 58mins",
                                            "bt_pahat"          =>  "7hr 33mins",
                                            "kuantan_sentral"   =>  "0hr",
                                            "kt_terminal"       =>  "4hr 10mins",
                                            "jengka_sentral"    =>  "2hr 30mins"
                                            ],
                    "kt_terminal"       =>  [
                                            "kl_sentral"        =>  "4hr 59mins",
                                            "bt_pahat"          =>  "6hr 20mins",
                                            "kuantan_sentral"   =>  "4hr 10mins",
                                            "kt_terminal"       =>  "0hr",
                                            "jengka_sentral"    =>  "3hr 49mins"
                                            ],
                    "jengka_sentral"    =>  [
                                            "kl_sentral"        =>  "9hr 8mins",
                                            "bt_pahat"          =>  "4hr 36mins",
                                            "kuantan_sentral"   =>  "2hr 30mins",
                                            "kt_terminal"       =>  "3hr 49mins",
                                            "jengka_sentral"    =>  "0hr"
                                            ]
                    ];

    return $journey[$depart_station][$dest_station];
}

/*
 *  @param  station     ->  string
 *
 *  @brief
 *  Takes a RAW string from station, convert it to a proper formatted string output
 *
 *  @return string
 */
function station_format($station)
{
    switch ($station)
    {
        case "kl_sentral":
            return "KL Sentral Bus Station";
        case "bt_pahat":
            return "Batu Pahat Bus Terminal";
        case "kuantan_sentral":
            return "Kuantan Sentral Bus Station";
        case "kt_terminal":
            return "Kuala Terengganu Bus Terminal";
        case "jengka_sentral":
            return "Jengka Sentral Bus Terminal";
    }
}

/*
 *  @param  value   ->  string
 *
 *  @brief
 *  Takes the name of a post field only if it has been set
 *  then returns the value back to form value parameter.
 *
 *  @return values  ->  string
 */
function sticky_form($value)
{
    if (isset($_POST[$value]))
        return $_POST[$value];
}

/*
 *  @param  request  ->  string
 *  @param  value    ->  string
 *
 *  @brief
 *  Takes a value then compares it to the value of the request
 *  if it matches then sticky a selected tag on the option.
 *
 *  @return  void
 */
function sticky_select($request, $value)
{
    if (isset($_POST['add_booking']))
    {
        if ($_POST[$request] == $value)
            echo "selected";
    } else
    {
        if ($request == $value)
            echo "selected";
    }
}

/*
 *  @param  null
 *
 *  @brief
 *  echo out a list of errors near the field
 *
 *  @return void
 */
function display_errors()
{
    global $errors;

    if (count($errors) > 0)
    {
        foreach ($errors as $error)
        {
            echo "<div class=\"p-2 my-2 bg-danger text-white\">$error</div>";
        }
    }
}

function display_success()
{
    global $success;

    if (count($success) > 0)
    {
        foreach ($success as $suc)
        {
            echo "<div class=\"p-2 my-2 bg-success text-white\">$suc</div>";
        }
    }
}

/*
 *  @param  value   ->  string
 *
 *  @brief
 *  This function evaluates the string that would be passed into the SQL query.
 *  To make sure it doesn't contain any escape characters.
 *
 *  @return value   ->  string
 *
 */
function evaluate($value)
{
    global $dbconnect;

    $value  =   stripslashes($value);
    return mysqli_real_escape_string($dbconnect, trim($value));
}
?>
