<?php

include ("config/config.php");
include ("config/mysql_connect.php");
$errors     =   array();
$success    =   array();

/*
 *  @params username -> string
 *  @params password -> string
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

            header('Location: '. HOSTNAME . 'dashboard.php');
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

                header('Location: ' . HOSTNAME . 'admin/dashboard.php');
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
                echo "Successfully Registered";
            else
                array_push($errors, "Something went wrong with the register query");

        } else
        {
            array_push($errors, "Mobile phone number has already been registered");
        }

    }

    mysqli_close($dbconnect);
}

/*
 *  @param  user_id     ->  int
 *
 *  @brief
 *  queries the database for associated tickets based on the user id
 *
 *  @return void
 */
function user_booked_ticket($user_id)
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
        echo "<div class=\"col-sm-12 justify-content-center\">
                  <h2>There is currently $user_number_tickets booked.</h2>
              </div>
              <div class=\"col-sm-12\">
                  <table class=\"table\">
                      <thead>
                          <tr>
                              <th scope=\"col\">Booking ID#</th>
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
                      <td>" . $ticket_rows['booking_id'] . "</td>
                      <td>" . $ticket_rows['depart_date'] . "</td>
                      <td>" . $ticket_rows['depart_time'] . "</td>
                      <td>" . $ticket_rows['depart_station'] . "</td>
                      <td>" . $ticket_rows['dest_station'] . "</td>
                      <td>" . $ticket_rows['journey'] . "</td>
                      <td>
                          <button class=\"btn btn-primary\" type=\"submit\" formaction=\"delete_booking.php\">Edit</button>
                          <button class=\"btn btn-danger\" type=\"submit\" formaction=\"delete_booking.php\">Cancel</button>
                      </td>
                  </tr>
                 ";
        }

        echo "        </tbody>
                  </table>
              </div>
             ";

    } else
    {
        echo "<div class=\"col-sm-12 justify-content-center\">
                  <h2>There is currently $user_number_tickets booked.</h2>
              </div>";
    }

    mysqli_close($dbconnect);
}

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
        $depart_time    =   $depart_time . ":00";

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
                header('Location: ' . HOSTNAME . 'dashboard.php');
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

function calculate_journey($depart_station, $dest_station)
{
    $journey    =   [
                    "kuala_lumpur"      =>  [
                                            "kuala_lumpur"      =>  0,
                                            "johor_bahru"       =>  0,
                                            "kuantan"           =>  8,
                                            "kuala_terengganu"  =>  0,
                                            "arau"              =>  0
                                            ],
                    "johor_bahru"       =>  [
                                            "kuala_lumpur"      =>  0,
                                            "johor_bahru"       =>  0,
                                            "kuantan"           =>  0,
                                            "kuala_terengganu"  =>  0,
                                            "arau"              =>  0
                                            ],
                    "kuantan"           =>  [
                                            "kuala_lumpur"      =>  0,
                                            "johor_bahru"       =>  0,
                                            "kuantan"           =>  0,
                                            "kuala_terengganu"  =>  0,
                                            "arau"              =>  0
                                            ],
                    "kuala_terengganu"  =>  [
                                            "kuala_lumpur"      =>  0,
                                            "johor_bahru"       =>  0,
                                            "kuantan"           =>  0,
                                            "kuala_terengganu"  =>  0,
                                            "arau"              =>  0
                                            ],
                    "arau"              =>  [
                                            "kuala_lumpur"      =>  0,
                                            "johor_bahru"       =>  0,
                                            "kuantan"           =>  0,
                                            "kuala_terengganu"  =>  0,
                                            "arau"              =>  0
                                            ]
                    ];

    return $journey[$depart_station][$dest_station];
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
        echo "<ul>";
        foreach ($errors as $error)
        {
            echo "<li>$error</li>";
        }
        echo "</ul>";
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
