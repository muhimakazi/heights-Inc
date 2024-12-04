<?php

/**
 * Class for user login
 */

class User
{
    private $_db,
        $_data,
        $_count = 0,
        $_sessionName,
        $_cookieName,
        $isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('sessions/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->isLoggedIn = true;
                } else {
                    //Logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('Sorry, there was a problem creating your account;');
        }
    }

    public function update($fields = array(), $id = null)
    {

        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }

        if (!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating');
        }
    }

    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function get($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
    {
        $selectQuery = 'SELECT ' . $rows . ' FROM ' . $table;
        if ($join != null) {
            $selectQuery .= ' JOIN ' . $join;
        }
        if ($where != null) {
            $selectQuery .= ' WHERE ' . $where;
        }
        if ($order != null) {
            $selectQuery .= ' ORDER BY ' . $order;
        }
        if ($limit != null) {
            $selectQuery .= ' LIMIT ' . $limit;
        }

        $data = $this->_db->query($selectQuery);

        if ($data->count()) {
            $this->_count = $data->count();
            $this->_data = $data->results();
        }
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);

            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt) && $this->data()->status == "ACTIVE") {
                    Session::put($this->_sessionName, $this->data()->id);

                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                        if (!$hashCheck->count()) {
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }

                    // UPDATING LOGS
                    $operation = "USER LOGIN";
                    $comment = "SUCCESSFUL";
                    Logs::newLog($operation, $comment, $this->data()->id);

                    return true;
                }
            }
        }
        return false;
    }

    public function loginCrew($login_usename, $login_password)
    {

        $status = "APPROVED";

        try {

            $sql = "SELECT id, event_id, email, job_title, participation_type_id, participation_sub_type_id, firstname, lastname, organisation_name, status FROM future_participants WHERE email= ? AND password= ? AND participation_type_id= ? AND status= ?";

            $params = array(
                $login_usename,
                Hash::make(trim($login_password)),
                6,
                $status,
            );

            $users = DB::getInstance()->query($sql, $params);

            if ($users->count()) {
                Session::put($this->_sessionName, $users->first()->id);
                // Session::put("user_id", );
                return true;
            } else {

                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }



    public function hasPermission($key)
    {
        $group = $this->_db->get('groups', array('id', '=', $this->data()->group));

        if ($group->count()) {
            $permissions = json_decode($group->first()->permissions, true);

            return !empty($permissions[$key]);
        }

        return false;
    }

    public function userCategory($key)
    {
        $group = $this->_db->get('groups', array('id', '=', $key));

        if ($group->count()) {
            $category = $group->first()->name;

            return $category;
        }

        return false;
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function logout()
    {
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }

    public function count()
    {
        return $this->_count;
    }

    function send_mail_torus($email,$message,$subject)
    {    
        
        $from  = "no-reply@torusguru.com"; 
        $namefrom = "TORUS";
        $mail = new  PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host   = "torusguru.com";
        $mail->Port       = 465;
        $mail->Username   = 'developer@torusguru.com';
        $mail->Password   = "TORUSDevs#!";
        $mail->SMTPSecure = "ssl";
        $mail->setFrom($from,$namefrom);
        // $mail->addCC($from,$namefrom);
        $mail->Subject  = $subject;
        $mail->isHTML();
        $mail->Body = $message;
        $mail->addAddress($email);
        @$mail->send();
    }


    function send_mail($email,$message,$subject, $attachement="")
    {    
        
        $from  = "inclusivefintech@torusguru.com";
        $namefrom = "Inclusive FinTech Forum";
        $mail = new  PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host   = "torusguru.com";
        $mail->Port       = 465;
        $mail->Username   = 'developer@torusguru.com';
        $mail->Password   = "TORUSDevs#!";
        $mail->SMTPSecure = "ssl";
        $mail->setFrom($from,$namefrom);
        // $mail->addCC($from,$namefrom);
        $mail->Subject  = $subject;
        $mail->isHTML();
        $mail->Body = $message;
        $mail->addAddress($email);

        if(!empty($attachement)){
            $mail->addAttachment($attachement);
        }

        @$mail->send();
    }

    function sendMail($data)
    {    
        $data = (object)$data;
        $email = $data->email;
        $message = $data->message;
        $subject = $data->subject;
        $from = $data->from;
        $namefrom = $data->namefrom;
        $attachement = $data->attachement;

        $mail = new  PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host   = "torusguru.com";
        $mail->Port       = 465;
        $mail->Username   = 'developer@torusguru.com';
        $mail->Password   = "TORUSDevs#!";
        $mail->SMTPSecure = "ssl";
        $mail->setFrom($from,$namefrom);
        // $mail->addCC($from,$namefrom);
        $mail->Subject  = $subject;
        $mail->isHTML();
        $mail->Body = $message;
        $mail->addAddress($email);

        if(!empty($attachement)){
            $mail->addAttachment($attachement);
        }

        @$mail->send();
    }

    // job titles//

    public function jobTitle($errorstate, $value = '', $categ)
    {
?>

<option value=""> [--Select--] </option>
<?php
        if ($categ == "Media") {
        ?>
<option value="Media Manager" <?php if ($errorstate && $value == 'Media Manager') {
                                                echo 'selected="selected"';
                                            } ?>>Media Manager</option>

<option value="Reporter" <?php if ($errorstate && $value == 'Reporter') {
                                            echo 'selected="selected"';
                                        } ?>>Reporter</option>

<option value="Photographer" <?php if ($errorstate && $value == 'Photographer') {
                                                echo 'selected="selected"';
                                            } ?>>Photographer</option>

<option value="Writer/Editor" <?php if ($errorstate && $value == 'Writer/Editor') {
                                                echo 'selected="selected"';
                                            } ?>>Writer/Editor</option>

<option value="Producer/Videographer" <?php if ($errorstate && $value == 'Producer/Videographer') {
                                                        echo 'selected="selected"';
                                                    } ?>>Producer/Videographer</option>
<?php
        } elseif ($categ == "Government") {
        ?>
<option value="Ambassador" <?php if ($errorstate && $value == 'Ambassador') {
                                            echo 'selected="selected"';
                                        } ?>>Ambassador</option>
<option value="Cabinet Secretary" <?php if ($errorstate && $value == 'Cabinet Secretary') {
                                                    echo 'selected="selected"';
                                                } ?>>Cabinet Secretary</option>
<option value="Chairman" <?php if ($errorstate && $value == 'Chairman') {
                                            echo 'selected="selected"';
                                        } ?>>Chairman</option>
<option value="Chairperson" <?php if ($errorstate && $value == 'Chairperson') {
                                            echo 'selected="selected"';
                                        } ?>>Chairperson</option>
<option value="Counsellors" <?php if ($errorstate && $value == 'Counsellors') {
                                            echo 'selected="selected"';
                                        } ?>>Counsellors</option>
<option value="Country Manager" <?php if ($errorstate && $value == 'Country Manager') {
                                                echo 'selected="selected"';
                                            } ?>>Country Manager</option>
<option value="Director General" <?php if ($errorstate && $value == 'Director General') {
                                                    echo 'selected="selected"';
                                                } ?>>Director General</option>
<option value="Executive Secretary" <?php if ($errorstate && $value == 'Executive Secretar') {
                                                    echo 'selected="selected"';
                                                } ?>>Executive Secretar</option>
<option value="Governor" <?php if ($errorstate && $value == 'Governor') {
                                            echo 'selected="selected"';
                                        } ?>>Governor</option>
<option value="High Commissioner" <?php if ($errorstate && $value == 'High Commissioner') {
                                                    echo 'selected="selected"';
                                                } ?>>High Commissioner</option>
<option value="Hon. Consul" <?php if ($errorstate && $value == 'Hon. Consul') {
                                            echo 'selected="selected"';
                                        } ?>>Hon. Consul</option>
<option value="Mayor" <?php if ($errorstate && $value == 'Mayor') {
                                        echo 'selected="selected"';
                                    } ?>>Mayor</option>
<option value="Member of Parliament" <?php if ($errorstate && $value == 'Member of Parliament') {
                                                        echo 'selected="selected"';
                                                    } ?>>Member of Parliament</option>
<option value="Minister" <?php if ($errorstate && $value == 'Minister') {
                                            echo 'selected="selected"';
                                        } ?>>Minister</option>
<option value="Minister of state" <?php if ($errorstate && $value == 'Minister of state') {
                                                    echo 'selected="selected"';
                                                } ?>>Minister of state</option>
<option value="National Coordinator" <?php if ($errorstate && $value == 'National Coordinator') {
                                                        echo 'selected="selected"';
                                                    } ?>>National Coordinator</option>
<option value="Permanent Secretary" <?php if ($errorstate && $value == 'Permanent Secretary') {
                                                    echo 'selected="selected"';
                                                } ?>>Permanent Secretary</option>
<option value="President" <?php if ($errorstate && $value == 'President') {
                                            echo 'selected="selected"';
                                        } ?>>President</option>
<option value="Senator" <?php if ($errorstate && $value == 'Senator') {
                                        echo 'selected="selected"';
                                    } ?>>Senator</option>
<option value="Speaker of an Assembly" <?php if ($errorstate && $value == 'Speaker of an Assembly') {
                                                        echo 'selected="selected"';
                                                    } ?>>Speaker of an Assembly</option>
<?php
        } else { ?>

<option value="Associate Director" <?php if ($errorstate && $value == 'Associate Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Associate Director</option>

<option value="Board Chairman" <?php if ($errorstate && $value == 'Board Chairman') {
                                                echo 'selected="selected"';
                                            } ?>>Board Chairman</option>

<option value="Business Analyst" <?php if ($errorstate && $value == 'Business Analyst') {
                                                    echo 'selected="selected"';
                                                } ?>>Business Analyst</option>

<option value="Chairman / Executive Chairman" <?php if ($errorstate && $value == 'Chairman / Executive Chairman') {
                                                                echo 'selected="selected"';
                                                            } ?>>Chairman / Executive Chairman</option>

<option value="Chief Executive Officer (CEO)" <?php if ($errorstate && $value == 'Chief Executive Officer (CEO)') {
                                                                echo 'selected="selected"';
                                                            } ?>>Chief Executive Officer (CEO)</option>

<option value="Chief Financial Officer (CFO)" <?php if ($errorstate && $value == 'Chief Financial Officer (CFO)') {
                                                                echo 'selected="selected"';
                                                            } ?>>Chief Financial Officer (CFO)</option>

<option value="Chief Information Officer (CIO)" <?php if ($errorstate && $value == 'Chief Information Officer (CIO') {
                                                                echo 'selected="selected"';
                                                            } ?><?php if ($errorstate && $value == 'Associate Director') {
                                                                    echo 'selected="selected"';
                                                                } ?>>Chief Information Officer (CIO)</option>

<option value="Chief Investment Officer " <?php if ($errorstate && $value == 'Chief Investment Officer') {
                                                            echo 'selected="selected"';
                                                        } ?>>Chief Investment Officer</option>

<option value="Chief Marketing Officer (CMO)" <?php if ($errorstate && $value == 'Chief Marketing Officer (CMO)') {
                                                                echo 'selected="selected"';
                                                            } ?>>Chief Marketing Officer (CMO)</option>

<option value="Chief of Staff" <?php if ($errorstate && $value == 'Chief of Staff') {
                                                echo 'selected="selected"';
                                            } ?>>Chief of Staff</option>

<option value="Chief Operating Officer (COO)" <?php if ($errorstate && $value == 'Chief Operating Officer (COO)') {
                                                                echo 'selected="selected"';
                                                            } ?>>Chief Operating Officer (COO)</option>
<option value="Chief Technology Officer" <?php if ($errorstate && $value == 'Chief Technology Officer') {
                                                            echo 'selected="selected"';
                                                        } ?>>Chief Technology Officer</option>
<option value="Co-Founder" <?php if ($errorstate && $value == 'Co-Founder') {
                                            echo 'selected="selected"';
                                        } ?>>Co-Founder</option>
<option value="Commissioner" <?php if ($errorstate && $value == 'Commissioner') {
                                                echo 'selected="selected"';
                                            } ?>>Commissioner</option>
<option value="Consultant / Freelancer" <?php if ($errorstate && $value == 'Consultant / Freelancer') {
                                                        echo 'selected="selected"';
                                                    } ?>>Consultant / Freelancer</option>
<option value="Coordinator" <?php if ($errorstate && $value == 'Coordinator') {
                                            echo 'selected="selected"';
                                        } ?>>Coordinator</option>
<option value="Creative Director" <?php if ($errorstate && $value == 'Creative Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Creative Director</option>
<option value="Deputy CEO" <?php if ($errorstate && $value == 'Deputy CEO') {
                                            echo 'selected="selected"';
                                        } ?>>Deputy CEO</option>
<option value="Deputy Director" <?php if ($errorstate && $value == 'Deputy Director') {
                                                echo 'selected="selected"';
                                            } ?>>Deputy Director</option>
<option value="Director" <?php if ($errorstate && $value == 'Director') {
                                            echo 'selected="selected"';
                                        } ?>>Director</option>
<option value="Director of Operations" <?php if ($errorstate && $value == 'Director of Operations') {
                                                        echo 'selected="selected"';
                                                    } ?>>Director of Operations</option>
<option value="Engineer" <?php if ($errorstate && $value == 'Engineer') {
                                            echo 'selected="selected"';
                                        } ?>>Engineer</option>
<option value="Entrepreneur" <?php if ($errorstate && $value == 'Entrepreneur') {
                                                echo 'selected="selected"';
                                            } ?>>Entrepreneur</option>
<option value="Executive Director" <?php if ($errorstate && $value == 'Executive Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Executive Director</option>
<option value="Executive President" <?php if ($errorstate && $value == 'Executive President') {
                                                    echo 'selected="selected"';
                                                } ?>>Executive President</option>
<option value="Executive Secretary" <?php if ($errorstate && $value == 'Executive Secretary') {
                                                    echo 'selected="selected"';
                                                } ?>>Executive Secretary</option>
<option value="Financial Advisor / Financial Specialist" <?php if ($errorstate && $value == 'Financial Advisor / Financial Specialist') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>Financial Advisor / Financial Specialist
</option>
<option value="Founder" <?php if ($errorstate && $value == 'Founder') {
                                        echo 'selected="selected"';
                                    } ?>>Founder</option>
<option value="General Manager" <?php if ($errorstate && $value == 'General Manager') {
                                                echo 'selected="selected"';
                                            } ?>>General Manager</option>
<option value="GIS Specialist" <?php if ($errorstate && $value == 'GIS Specialist') {
                                                echo 'selected="selected"';
                                            } ?>>GIS Specialist</option>
<option value="ICT Advisor" <?php if ($errorstate && $value == 'ICT Advisor') {
                                            echo 'selected="selected"';
                                        } ?>>ICT Advisor</option>
<option value="ICT Officer" <?php if ($errorstate && $value == 'ICT Officer') {
                                            echo 'selected="selected"';
                                        } ?>>ICT Officer</option>
<option value="ICT Specialist" <?php if ($errorstate && $value == 'ICT Specialist') {
                                                echo 'selected="selected"';
                                            } ?>>ICT Specialist</option>
<option value="ICT Technician" <?php if ($errorstate && $value == 'ICT Technician') {
                                                echo 'selected="selected"';
                                            } ?>>ICT Technician</option>
<option value="Innovation Manager" <?php if ($errorstate && $value == 'Innovation Manager') {
                                                    echo 'selected="selected"';
                                                } ?>>Innovation Manager</option>
<option value="Innovator" <?php if ($errorstate && $value == 'Innovator') {
                                            echo 'selected="selected"';
                                        } ?>>Innovator</option>
<option value="Legal Advisor" <?php if ($errorstate && $value == 'Legal Advisor') {
                                                echo 'selected="selected"';
                                            } ?>>Legal Advisor</option>
<option value="Legal Representative" <?php if ($errorstate && $value == 'Legal Representative') {
                                                        echo 'selected="selected"';
                                                    } ?>>Legal Representative</option>
<option value="Manager" <?php if ($errorstate && $value == 'Manager') {
                                        echo 'selected="selected"';
                                    } ?>>Manager</option>
<option value="Managing Director" <?php if ($errorstate && $value == 'Managing Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Managing Director</option>
<option value="Managing Partner" <?php if ($errorstate && $value == 'Managing Partner') {
                                                    echo 'selected="selected"';
                                                } ?>>Managing Partner</option>
<option value="Marketing Director" <?php if ($errorstate && $value == 'Marketing Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Marketing Director</option>
<option value="Marketing Executive" <?php if ($errorstate && $value == 'Marketing Executive') {
                                                    echo 'selected="selected"';
                                                } ?>>Marketing Executive</option>
<option value="Principal" <?php if ($errorstate && $value == 'Principal') {
                                            echo 'selected="selected"';
                                        } ?>>Principal</option>
<option value="Professor/Lecturer" <?php if ($errorstate && $value == 'Professor/Lecturer') {
                                                    echo 'selected="selected"';
                                                } ?>>Professor/Lecturer</option>
<option value="Program Analyst" <?php if ($errorstate && $value == 'Program Analyst') {
                                                echo 'selected="selected"';
                                            } ?>>Program Analyst</option>
<option value="Program Director" <?php if ($errorstate && $value == 'Program Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Program Director</option>
<option value="Public Relations Manager" <?php if ($errorstate && $value == 'Public Relations Manager') {
                                                            echo 'selected="selected"';
                                                        } ?>>Public Relations Manager</option>
<option value="Regional Director" <?php if ($errorstate && $value == 'Regional Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Regional Director</option>
<option value="Researcher / Research Assistant" <?php if ($errorstate && $value == 'Researcher / Research Assistant') {
                                                                echo 'selected="selected"';
                                                            } ?>>Researcher / Research Assistant</option>
<option value="Sales Executive" <?php if ($errorstate && $value == 'Sales Executive') {
                                                echo 'selected="selected"';
                                            } ?>>Sales Executive</option>
<option value="Secretary General" <?php if ($errorstate && $value == 'Secretary General') {
                                                    echo 'selected="selected"';
                                                } ?>>Secretary General</option>
<option value="Senior Associate" <?php if ($errorstate && $value == 'Senior Associate') {
                                                    echo 'selected="selected"';
                                                } ?>>Senior Associate</option>
<option value="Senior Executive Vice President" <?php if ($errorstate && $value == 'Senior Executive Vice President') {
                                                                echo 'selected="selected"';
                                                            } ?>>Senior Executive Vice President</option>
<option value="Senior Vice President" <?php if ($errorstate && $value == 'Senior Vice President') {
                                                        echo 'selected="selected"';
                                                    } ?>>Senior Vice President</option>
<option value="Software Developer" <?php if ($errorstate && $value == 'Software Developer') {
                                                    echo 'selected="selected"';
                                                } ?>>Software Developer</option>
<option value="Student" <?php if ($errorstate && $value == 'Student') {
                                        echo 'selected="selected"';
                                    } ?>>Student</option>
<option value="Team Leader" <?php if ($errorstate && $value == 'Team Leader') {
                                            echo 'selected="selected"';
                                        } ?>>Team Leader</option>
<option value="Technical Advisor" <?php if ($errorstate && $value == 'Technical Advisor') {
                                                    echo 'selected="selected"';
                                                } ?>>Technical Advisor</option>
<option value="Technical Director" <?php if ($errorstate && $value == 'Technical Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Technical Director</option>
<option value="Technical Manager" <?php if ($errorstate && $value == 'Technical Manager') {
                                                    echo 'selected="selected"';
                                                } ?>>Technical Manager</option>
<option value="Technology Director" <?php if ($errorstate && $value == 'Technology Director') {
                                                    echo 'selected="selected"';
                                                } ?>>Technology Director</option>
<option value="Vice Chancellor" <?php if ($errorstate && $value == 'Vice Chancellor') {
                                                echo 'selected="selected"';
                                            } ?>>Vice Chancellor</option>
<option value="Vice President" <?php if ($errorstate && $value == 'Vice President') {
                                                echo 'selected="selected"';
                                            } ?>>Vice President</option>
<option value="Web Developer" <?php if ($errorstate && $value == 'Web Developer') {
                                                echo 'selected="selected"';
                                            } ?>>Web Developer</option>

<?php
        }
        ?>
<option value="Other" <?php if ($errorstate && $value == 'Other') {
                                    echo 'selected="selected"';
                                } ?>>Other </option>
<?php
    }

    public static function getCountry()
    {
        $countries = array();
        $countries[] = array("code" => "AF", "name" => "Afghanistan", "d_code" => "+93", "icon" => "Afghanistan.png");
        $countries[] = array("code" => "AL", "name" => "Albania", "d_code" => "+355", "icon" => "Albania.png");
        $countries[] = array("code" => "DZ", "name" => "Algeria", "d_code" => "+213", "icon" => "Algeria.png");
        $countries[] = array("code" => "AS", "name" => "American Samoa", "d_code" => "+1", "icon" => "American_Samoa.png");
        $countries[] = array("code" => "AD", "name" => "Andorra", "d_code" => "+376", "icon" => "Andorra.png");
        $countries[] = array("code" => "AO", "name" => "Angola", "d_code" => "+244", "icon" => "Angola.png");
        $countries[] = array("code" => "AI", "name" => "Anguilla", "d_code" => "+1", "icon" => "Anguilla.png");
        $countries[] = array("code" => "AG", "name" => "Antigua & Barbuda", "d_code" => "+1", "icon" => "Antigua_Barbuda.png");
        $countries[] = array("code" => "AR", "name" => "Argentina", "d_code" => "+54", "icon" => "Argentina.png");
        $countries[] = array("code" => "AM", "name" => "Armenia", "d_code" => "+374", "icon" => "Armenia.png");
        $countries[] = array("code" => "AW", "name" => "Aruba", "d_code" => "+297", "icon" => "Aruba.png");
        $countries[] = array("code" => "AU", "name" => "Australia", "d_code" => "+61", "icon" => "Australia.png");
        $countries[] = array("code" => "AT", "name" => "Austria", "d_code" => "+43", "icon" => "Austria.png");
        $countries[] = array("code" => "AZ", "name" => "Azerbaijan", "d_code" => "+994", "icon" => "Azerbaijan.png");
        $countries[] = array("code" => "BH", "name" => "Bahamas", "d_code" => "+973", "icon" => "Bahamas.png");
        $countries[] = array("code" => "BH", "name" => "Bahrain", "d_code" => "+973", "icon" => "Bahrain.png");
        $countries[] = array("code" => "BD", "name" => "Bangladesh", "d_code" => "+880", "icon" => "Bangladesh.png");
        $countries[] = array("code" => "BB", "name" => "Barbados", "d_code" => "+1", "icon" => "Barbados.png");
        $countries[] = array("code" => "BY", "name" => "Belarus", "d_code" => "+375", "icon" => "Belarus.png");
        $countries[] = array("code" => "BE", "name" => "Belgium", "d_code" => "+32", "icon" => "Belgium.png");
        $countries[] = array("code" => "BZ", "name" => "Belize", "d_code" => "+501", "icon" => "Belize.png");
        $countries[] = array("code" => "BJ", "name" => "Benin", "d_code" => "+229", "icon" => "Benin.png");
        $countries[] = array("code" => "BM", "name" => "Bermuda", "d_code" => "+1", "icon" => "Bermuda.png");
        $countries[] = array("code" => "BT", "name" => "Bhutan", "d_code" => "+975", "icon" => "Bhutan.png");
        $countries[] = array("code" => "BO", "name" => "Bolivia", "d_code" => "+591", "icon" => "Bolivia.png");
        $countries[] = array("code" => "BA", "name" => "Bosnia and Herzegovina", "d_code" => "+387", "icon" => "Bosnia_Herzegovina.png");
        $countries[] = array("code" => "BW", "name" => "Botswana", "d_code" => "+267", "icon" => "Botswana.png");
        $countries[] = array("code" => "BR", "name" => "Brazil", "d_code" => "+55", "icon" => "Brazil.png");
        $countries[] = array("code" => "IO", "name" => "British Indian Ocean Territory", "d_code" => "+246", "icon" => "british_indian_ocean_territory.png");
        $countries[] = array("code" => "VG", "name" => "British Virgin Islands", "d_code" => "+1", "icon" => "British_Virgin_Islands.png");
        $countries[] = array("code" => "BN", "name" => "Brunei", "d_code" => "+673", "icon" => "Brunei.png");
        $countries[] = array("code" => "BG", "name" => "Bulgaria", "d_code" => "+359", "icon" => "Bulgaria.png");
        $countries[] = array("code" => "BF", "name" => "Burkina Faso", "d_code" => "+226", "icon" => "Burkina_Faso.png");
        $countries[] = array("code" => "MM", "name" => "Burma Myanmar", "d_code" => "+95", "icon" => "Burma_Myanmar.png");
        $countries[] = array("code" => "BI", "name" => "Burundi", "d_code" => "+257", "icon" => "Burundi.png");
        $countries[] = array("code" => "KH", "name" => "Cambodia", "d_code" => "+855", "icon" => "Cambodia.png");
        $countries[] = array("code" => "CM", "name" => "Cameroon", "d_code" => "+237", "icon" => "Cameroon.png");
        $countries[] = array("code" => "CA", "name" => "Canada", "d_code" => "+1", "icon" => "Canada.png");
        $countries[] = array("code" => "CV", "name" => "Cape Verde", "d_code" => "+238", "icon" => "Cape_Verde.png");
        $countries[] = array("code" => "KY", "name" => "Cayman Islands", "d_code" => "+1", "icon" => "Cayman_Islands.png");
        $countries[] = array("code" => "CF", "name" => "Central African Republic", "d_code" => "+236", "icon" => "Central_African_Republic.png");
        $countries[] = array("code" => "TD", "name" => "Chad", "d_code" => "+235", "icon" => "Chad.png");
        $countries[] = array("code" => "CL", "name" => "Chile", "d_code" => "+56", "icon" => "Chile.png");
        $countries[] = array("code" => "CN", "name" => "China", "d_code" => "+86", "icon" => "China.png");
        $countries[] = array("code" => "CO", "name" => "Colombia", "d_code" => "+57", "icon" => "Colombia.png");
        $countries[] = array("code" => "KM", "name" => "Comoros", "d_code" => "+269", "icon" => "Comoros.png");
        $countries[] = array("code" => "CK", "name" => "Cook Islands", "d_code" => "+682", "icon" => "Cook_Islands.png");
        $countries[] = array("code" => "CR", "name" => "Costa Rica", "d_code" => "+506", "icon" => "Costa_Rica.png");
        $countries[] = array("code" => "CI", "name" => "Cote d'Ivoire", "d_code" => "+225", "icon" => "Cote_d_Ivoire.png");
        $countries[] = array("code" => "HR", "name" => "Croatia", "d_code" => "+385", "icon" => "Croatia.png");
        $countries[] = array("code" => "CU", "name" => "Cuba", "d_code" => "+53", "icon" => "Cuba.png");
        $countries[] = array("code" => "CY", "name" => "Cyprus", "d_code" => "+357", "icon" => "Cyprus.png");
        $countries[] = array("code" => "CZ", "name" => "Czech Republic", "d_code" => "+420", "icon" => "Czech_Republic.png");
        $countries[] = array("code" => "CD", "name" => "Democratic Republic of Congo", "d_code" => "+243", "icon" => "Congo_Kinshasa.png");
        $countries[] = array("code" => "DK", "name" => "Denmark", "d_code" => "+45", "icon" => "Denmark.png");
        $countries[] = array("code" => "DJ", "name" => "Djibouti", "d_code" => "+253", "icon" => "Djibouti.png");
        $countries[] = array("code" => "DM", "name" => "Dominica", "d_code" => "+1", "icon" => "Dominica.png");
        $countries[] = array("code" => "DO", "name" => "Dominican Republic", "d_code" => "+1", "icon" => "Dominican_Republic.png");
        $countries[] = array("code" => "EC", "name" => "Ecuador", "d_code" => "+593", "icon" => "Ecuador.png");
        $countries[] = array("code" => "EG", "name" => "Egypt", "d_code" => "+20", "icon" => "Egypt.png");
        $countries[] = array("code" => "SV", "name" => "El Salvador", "d_code" => "+503", "icon" => "El_Salvador.png");
        $countries[] = array("code" => "GQ", "name" => "Equatorial Guinea", "d_code" => "+240", "icon" => "Equatorial_Guinea.png");
        $countries[] = array("code" => "ER", "name" => "Eritrea", "d_code" => "+291", "icon" => "Eritrea.png");
        $countries[] = array("code" => "EE", "name" => "Estonia", "d_code" => "+372", "icon" => "Estonia.png");
        $countries[] = array("code" => "ET", "name" => "Ethiopia", "d_code" => "+251", "icon" => "Ethiopia.png");
        $countries[] = array("code" => "FK", "name" => "Falkland Islands", "d_code" => "+500", "icon" => "Falkland_Islands.png");
        $countries[] = array("code" => "FO", "name" => "Faroe Islands", "d_code" => "+298", "icon" => "Faroes.png");
        $countries[] = array("code" => "FM", "name" => "Federated States of Micronesia", "d_code" => "+691", "icon" => "Micronesia.png");
        $countries[] = array("code" => "FJ", "name" => "Fiji", "d_code" => "+679", "icon" => "Fiji.png");
        $countries[] = array("code" => "FI", "name" => "Finland", "d_code" => "+358", "icon" => "Finland.png");
        $countries[] = array("code" => "FR", "name" => "France", "d_code" => "+33", "icon" => "France.png");
        $countries[] = array("code" => "GF", "name" => "French Guiana", "d_code" => "+594", "icon" => "");
        $countries[] = array("code" => "PF", "name" => "French Polynesia", "d_code" => "+689", "icon" => "");
        $countries[] = array("code" => "GA", "name" => "Gabon", "d_code" => "+241", "icon" => "Gabon.png");
        $countries[] = array("code" => "GE", "name" => "Georgia", "d_code" => "+995", "icon" => "Georgia.png");
        $countries[] = array("code" => "DE", "name" => "Germany", "d_code" => "+49", "icon" => "Germany.png");
        $countries[] = array("code" => "GH", "name" => "Ghana", "d_code" => "+233", "icon" => "Ghana.png");
        $countries[] = array("code" => "GI", "name" => "Gibraltar", "d_code" => "+350", "icon" => "Gibraltar.png");
        $countries[] = array("code" => "GR", "name" => "Greece", "d_code" => "+30", "icon" => "Greece.png");
        $countries[] = array("code" => "GL", "name" => "Greenland", "d_code" => "+299", "icon" => "Greenland.png");
        $countries[] = array("code" => "GD", "name" => "Grenada", "d_code" => "+1", "icon" => "Grenada.png");
        $countries[] = array("code" => "GP", "name" => "Guadeloupe", "d_code" => "+590", "icon" => "Guadeloupe.png");
        $countries[] = array("code" => "GU", "name" => "Guam", "d_code" => "+1", "icon" => "Guam.png");
        $countries[] = array("code" => "GT", "name" => "Guatemala", "d_code" => "+502", "icon" => "Guademala.png");
        $countries[] = array("code" => "GN", "name" => "Guinea", "d_code" => "+224", "icon" => "Guinea.png");
        $countries[] = array("code" => "GW", "name" => "Guinea-Bissau", "d_code" => "+245", "icon" => "Guinea_Bissau.png");
        $countries[] = array("code" => "GY", "name" => "Guyana", "d_code" => "+592", "icon" => "Guyana.png");
        $countries[] = array("code" => "HT", "name" => "Haiti", "d_code" => "+509", "icon" => "Haiti.png");
        $countries[] = array("code" => "HN", "name" => "Honduras", "d_code" => "+504", "icon" => "Honduras.png");
        $countries[] = array("code" => "HK", "name" => "Hong Kong", "d_code" => "+852", "icon" => "Hong_Kong.png");
        $countries[] = array("code" => "HU", "name" => "Hungary", "d_code" => "+36", "icon" => "Hungary.png");
        $countries[] = array("code" => "IS", "name" => "Iceland", "d_code" => "+354", "icon" => "Iceland.png");
        $countries[] = array("code" => "IN", "name" => "India", "d_code" => "+91", "icon" => "India.png");
        $countries[] = array("code" => "ID", "name" => "Indonesia", "d_code" => "+62", "icon" => "Indonesia.png");
        $countries[] = array("code" => "IR", "name" => "Iran", "d_code" => "+98", "icon" => "Iran.png");
        $countries[] = array("code" => "IQ", "name" => "Iraq", "d_code" => "+964", "icon" => "Iraq.png");
        $countries[] = array("code" => "IE", "name" => "Ireland", "d_code" => "+353", "icon" => "Ireland.png");
        $countries[] = array("code" => "IL", "name" => "Israel", "d_code" => "+972", "icon" => "Israel.png");
        $countries[] = array("code" => "IT", "name" => "Italy", "d_code" => "+39", "icon" => "Italy.png");
        $countries[] = array("code" => "JM", "name" => "Jamaica", "d_code" => "+1", "icon" => "Jamaica.png");
        $countries[] = array("code" => "JP", "name" => "Japan", "d_code" => "+81", "icon" => "Japan.png");
        $countries[] = array("code" => "JO", "name" => "Jordan", "d_code" => "+962", "icon" => "Jordan.png");
        $countries[] = array("code" => "KZ", "name" => "Kazakhstan", "d_code" => "+7", "icon" => "Kazakhstan.png");
        $countries[] = array("code" => "KE", "name" => "Kenya", "d_code" => "+254", "icon" => "Kenya.png");
        $countries[] = array("code" => "KI", "name" => "Kiribati", "d_code" => "+686", "icon" => "Kiribati.png");
        $countries[] = array("code" => "XK", "name" => "Kosovo", "d_code" => "+381", "icon" => "Kosovo.png");
        $countries[] = array("code" => "KW", "name" => "Kuwait", "d_code" => "+965", "icon" => "Kuwait.png");
        $countries[] = array("code" => "KG", "name" => "Kyrgyzstan", "d_code" => "+996", "icon" => "Kyrgyzstan.png");
        $countries[] = array("code" => "LA", "name" => "Laos", "d_code" => "+856", "icon" => "Laos.png");
        $countries[] = array("code" => "LV", "name" => "Latvia", "d_code" => "+371", "icon" => "Latvia.png");
        $countries[] = array("code" => "LB", "name" => "Lebanon", "d_code" => "+961", "icon" => "Lebanon.png");
        $countries[] = array("code" => "LS", "name" => "Lesotho", "d_code" => "+266", "icon" => "Lesotho.png");
        $countries[] = array("code" => "LR", "name" => "Liberia", "d_code" => "+231", "icon" => "Liberia.png");
        $countries[] = array("code" => "LY", "name" => "Libya", "d_code" => "+218", "icon" => "Libya.png");
        $countries[] = array("code" => "LI", "name" => "Liechtenstein", "d_code" => "+423", "icon" => "Liechtenstein.png");
        $countries[] = array("code" => "LT", "name" => "Lithuania", "d_code" => "+370", "icon" => "Lithuania.png");
        $countries[] = array("code" => "LU", "name" => "Luxembourg", "d_code" => "+352", "icon" => "Luxembourg.png");
        $countries[] = array("code" => "MO", "name" => "Macau", "d_code" => "+853", "icon" => "Macau.png");
        $countries[] = array("code" => "MK", "name" => "Macedonia", "d_code" => "+389", "icon" => "Macedonia.png");
        $countries[] = array("code" => "MG", "name" => "Madagascar", "d_code" => "+261", "icon" => "Madagascar.png");
        $countries[] = array("code" => "MW", "name" => "Malawi", "d_code" => "+265", "icon" => "Malawi.png");
        $countries[] = array("code" => "MY", "name" => "Malaysia", "d_code" => "+60", "icon" => "Malaysia.png");
        $countries[] = array("code" => "MV", "name" => "Maldives", "d_code" => "+960", "icon" => "Maldives.png");
        $countries[] = array("code" => "ML", "name" => "Mali", "d_code" => "+223", "icon" => "Mali.png");
        $countries[] = array("code" => "MT", "name" => "Malta", "d_code" => "+356", "icon" => "Malta.png");
        $countries[] = array("code" => "MH", "name" => "Marshall Islands", "d_code" => "+692", "icon" => "Marshall_Islands.png");
        $countries[] = array("code" => "MQ", "name" => "Martinique", "d_code" => "+596", "icon" => "Martinique.png");
        $countries[] = array("code" => "MR", "name" => "Mauritania", "d_code" => "+222", "icon" => "Mauritania.png");
        $countries[] = array("code" => "MU", "name" => "Mauritius", "d_code" => "+230", "icon" => "Mauritius.png");
        $countries[] = array("code" => "YT", "name" => "Mayotte", "d_code" => "+262", "icon" => "Mayotte.png");
        $countries[] = array("code" => "MX", "name" => "Mexico", "d_code" => "+52", "icon" => "Mexico.png");
        $countries[] = array("code" => "MD", "name" => "Moldova", "d_code" => "+373", "icon" => "Moldova.png");
        $countries[] = array("code" => "MC", "name" => "Monaco", "d_code" => "+377", "icon" => "Monaco.png");
        $countries[] = array("code" => "MN", "name" => "Mongolia", "d_code" => "+976", "icon" => "Mongolia.png");
        $countries[] = array("code" => "ME", "name" => "Montenegro", "d_code" => "+382", "icon" => "Montenegro.png");
        $countries[] = array("code" => "MS", "name" => "Montserrat", "d_code" => "+664", "icon" => "Montserrat.png");
        $countries[] = array("code" => "MA", "name" => "Morocco", "d_code" => "+212", "icon" => "Morocco.png");
        $countries[] = array("code" => "MZ", "name" => "Mozambique", "d_code" => "+258", "icon" => "Mozambique.png");
        $countries[] = array("code" => "NA", "name" => "Namibia", "d_code" => "+264", "icon" => "Namibia.png");
        $countries[] = array("code" => "NR", "name" => "Nauru", "d_code" => "+674", "icon" => "Nauru.png");
        $countries[] = array("code" => "NP", "name" => "Nepal", "d_code" => "+977", "icon" => "Nepal.png");
        $countries[] = array("code" => "NL", "name" => "Netherlands", "d_code" => "+31", "icon" => "Netherlands.png");
        $countries[] = array("code" => "AN", "name" => "Netherlands Antilles", "d_code" => "+599", "icon" => "Netherlands_Antilles.png");
        $countries[] = array("code" => "NC", "name" => "New Caledonia", "d_code" => "+687", "icon" => "New_Caledonia.png");
        $countries[] = array("code" => "NZ", "name" => "New Zealand", "d_code" => "+64", "icon" => "New_Zealand.png");
        $countries[] = array("code" => "NI", "name" => "Nicaragua", "d_code" => "+505", "icon" => "Nicaragua.png");
        $countries[] = array("code" => "NE", "name" => "Niger", "d_code" => "+227", "icon" => "Niger.png");
        $countries[] = array("code" => "NG", "name" => "Nigeria", "d_code" => "+234", "icon" => "Nigeria.png");
        $countries[] = array("code" => "NU", "name" => "Niue", "d_code" => "+683", "icon" => "Niue.png");
        $countries[] = array("code" => "NF", "name" => "Norfolk Island", "d_code" => "+672", "icon" => "Norfolk_Island.png");
        $countries[] = array("code" => "KP", "name" => "North Korea", "d_code" => "+850", "icon" => "North_Korea.png");
        $countries[] = array("code" => "MP", "name" => "Northern Mariana Islands", "d_code" => "+1", "icon" => "Northern_Mariana_Islands.png");
        $countries[] = array("code" => "NO", "name" => "Norway", "d_code" => "+47", "icon" => "Norway.png");
        $countries[] = array("code" => "OM", "name" => "Oman", "d_code" => "+968", "icon" => "Oman.png");
        $countries[] = array("code" => "PK", "name" => "Pakistan", "d_code" => "+92", "icon" => "Pakistan.png");
        $countries[] = array("code" => "PW", "name" => "Palau", "d_code" => "+680", "icon" => "Palau.png");
        $countries[] = array("code" => "PS", "name" => "Palestine", "d_code" => "+970", "icon" => "Palestine.png");
        $countries[] = array("code" => "PA", "name" => "Panama", "d_code" => "+507", "icon" => "Panama.png");
        $countries[] = array("code" => "PG", "name" => "Papua New Guinea", "d_code" => "+675", "icon" => "Papua_New_Guinea.png");
        $countries[] = array("code" => "PY", "name" => "Paraguay", "d_code" => "+595", "icon" => "Paraguay.png");
        $countries[] = array("code" => "PE", "name" => "Peru", "d_code" => "+51", "icon" => "Peru.png");
        $countries[] = array("code" => "PH", "name" => "Philippines", "d_code" => "+63", "icon" => "Philippines.png");
        $countries[] = array("code" => "PL", "name" => "Poland", "d_code" => "+48", "icon" => "Poland.png");
        $countries[] = array("code" => "PT", "name" => "Portugal", "d_code" => "+351", "icon" => "Portugal.png");
        $countries[] = array("code" => "PR", "name" => "Puerto Rico", "d_code" => "+1", "icon" => "Puerto_Rico.png");
        $countries[] = array("code" => "QA", "name" => "Qatar", "d_code" => "+974", "icon" => "Qatar.png");
        $countries[] = array("code" => "CG", "name" => "Republic of the Congo", "d_code" => "+242", "icon" => "Congo_Brazzaville.png");
        $countries[] = array("code" => "RE", "name" => "Reunion", "d_code" => "+262", "icon" => "Reunion.png");
        $countries[] = array("code" => "RO", "name" => "Romania", "d_code" => "+40", "icon" => "Romania.png");
        $countries[] = array("code" => "RU", "name" => "Russian Federation", "d_code" => "+7", "icon" => "Russian_Federation.png");
        $countries[] = array("code" => "RW", "name" => "Rwanda", "d_code" => "+250", "icon" => "Rwanda.png");

        $countries[] = array("code" => "BL", "name" => "Saint Barthélemy", "d_code" => "+590", "icon" => "");
        $countries[] = array("code" => "SH", "name" => "Saint Helena", "d_code" => "+290", "icon" => "");

        $countries[] = array("code" => "KN", "name" => "Saint Kitts and Nevis", "d_code" => "+1", "icon" => "St_Kitts_Nevis.png");

        $countries[] = array("code" => "MF", "name" => "Saint Martin", "d_code" => "+590", "icon" => "");
        $countries[] = array("code" => "PM", "name" => "Saint Pierre and Miquelon", "d_code" => "+508", "icon" => "");

        $countries[] = array("code" => "VC", "name" => "Saint Vincent and the Grenadines", "d_code" => "+1", "icon" => "St_Vincent_the_Grenadines.png");
        $countries[] = array("code" => "WS", "name" => "Samoa", "d_code" => "+685", "icon" => "Samoa.png");
        $countries[] = array("code" => "SM", "name" => "San Marino", "d_code" => "+378", "icon" => "San_Marino.png");
        $countries[] = array("code" => "ST", "name" => "Sao Tome and Principe", "d_code" => "+239", "icon" => "Sao_Tome_Principe.png");
        $countries[] = array("code" => "SA", "name" => "Saudi Arabia", "d_code" => "+966", "icon" => "Saudi_Arabia.png");
        $countries[] = array("code" => "SN", "name" => "Senegal", "d_code" => "+221", "icon" => "Senegal.png");
        $countries[] = array("code" => "RS", "name" => "Serbia", "d_code" => "+381", "icon" => "Serbia.png");
        $countries[] = array("code" => "SC", "name" => "Seychelles", "d_code" => "+248", "icon" => "Seyshelles.png");
        $countries[] = array("code" => "SL", "name" => "Sierra Leone", "d_code" => "+232", "icon" => "Sierra_Leone.png");
        $countries[] = array("code" => "SG", "name" => "Singapore", "d_code" => "+65", "icon" => "Singapore.png");
        $countries[] = array("code" => "SK", "name" => "Slovakia", "d_code" => "+421", "icon" => "Slovakia.png");
        $countries[] = array("code" => "SI", "name" => "Slovenia", "d_code" => "+386", "icon" => "Slovenia.png");
        $countries[] = array("code" => "SB", "name" => "Solomon Islands", "d_code" => "+677", "icon" => "Solomon_Islands.png");
        $countries[] = array("code" => "SO", "name" => "Somalia", "d_code" => "+252", "icon" => "Somalia.png");

        $countries[] = array("code" => "ZA", "name" => "South Africa", "d_code" => "+27", "icon" => "South_Afriica.png");
        $countries[] = array("code" => "KR", "name" => "South Korea", "d_code" => "+82", "icon" => "South_Korea.png");
        $countries[] = array("code" => "SS", "name" => "South Sudan", "d_code" => "+211", "icon" => "South_Sudan.png");
        $countries[] = array("code" => "ES", "name" => "Spain", "d_code" => "+34", "icon" => "Spain.png");
        $countries[] = array("code" => "LK", "name" => "Sri Lanka", "d_code" => "+94", "icon" => "Sri_Lanka.png");
        $countries[] = array("code" => "LC", "name" => "St. Lucia", "d_code" => "+1", "icon" => "Saint_Lucia.png");
        $countries[] = array("code" => "SD", "name" => "Sudan", "d_code" => "+249", "icon" => "Sudan.png");
        $countries[] = array("code" => "SR", "name" => "Suriname", "d_code" => "+597", "icon" => "Suriname.png");
        $countries[] = array("code" => "SZ", "name" => "Swaziland", "d_code" => "+268", "icon" => "Swaziland.png");
        $countries[] = array("code" => "SE", "name" => "Sweden", "d_code" => "+46", "icon" => "Sweden.png");
        $countries[] = array("code" => "CH", "name" => "Switzerland", "d_code" => "+41", "icon" => "Switzerland.png");
        $countries[] = array("code" => "SY", "name" => "Syria", "d_code" => "+963", "icon" => "Syria.png");
        $countries[] = array("code" => "TW", "name" => "Taiwan", "d_code" => "+886", "icon" => "Taiwan.png");
        $countries[] = array("code" => "TJ", "name" => "Tajikistan", "d_code" => "+992", "icon" => "Tajikistan.png");
        $countries[] = array("code" => "TZ", "name" => "Tanzania", "d_code" => "+255", "icon" => "Tanzania.png");
        $countries[] = array("code" => "TH", "name" => "Thailand", "d_code" => "+66", "icon" => "Thailand.png");

        $countries[] = array("code" => "BS", "name" => "The Bahamas", "d_code" => "+1", "icon" => "");
        $countries[] = array("code" => "GM", "name" => "The Gambia", "d_code" => "+220", "icon" => "Gambia.png");

        $countries[] = array("code" => "TL", "name" => "Timor-Leste", "d_code" => "+670", "icon" => "Timor_Leste.png");
        $countries[] = array("code" => "TG", "name" => "Togo", "d_code" => "+228", "icon" => "Togo.png");
        $countries[] = array("code" => "TK", "name" => "Tokelau", "d_code" => "+690", "icon" => "tokelau.png");
        $countries[] = array("code" => "TO", "name" => "Tonga", "d_code" => "+676", "icon" => "Tonga.png");
        $countries[] = array("code" => "TT", "name" => "Trinidad and Tobago", "d_code" => "+1", "icon" => "Trinidad_Tobago.png");
        $countries[] = array("code" => "TN", "name" => "Tunisia", "d_code" => "+216", "icon" => "Tunisia.png");
        $countries[] = array("code" => "TR", "name" => "Turkey", "d_code" => "+90", "icon" => "Turkey.png");
        $countries[] = array("code" => "TM", "name" => "Turkmenistan", "d_code" => "+993", "icon" => "Turkmenistan.png");
        $countries[] = array("code" => "TC", "name" => "Turks and Caicos Islands", "d_code" => "+1", "icon" => "Turks_and_Caicos_Islands.png");
        $countries[] = array("code" => "TV", "name" => "Tuvalu", "d_code" => "+688", "icon" => "Tuvalu.png");
        $countries[] = array("code" => "UG", "name" => "Uganda", "d_code" => "+256", "icon" => "Uganda.png");
        $countries[] = array("code" => "UA", "name" => "Ukraine", "d_code" => "+380", "icon" => "Ukraine.png");
        $countries[] = array("code" => "AE", "name" => "United Arab Emirates", "d_code" => "+971", "icon" => "United_Arab_Emirates.png");
        $countries[] = array("code" => "GB", "name" => "United Kingdom", "d_code" => "+44", "icon" => "United_Kingdom.png");
        $countries[] = array("code" => "US", "name" => "United States", "d_code" => "+1", "icon" => "United_States_of_America.png");
        $countries[] = array("code" => "UY", "name" => "Uruguay", "d_code" => "+598", "icon" => "Uruguay.png");
        $countries[] = array("code" => "VI", "name" => "US Virgin Islands", "d_code" => "+1", "icon" => "Virgin_Islands_US.png");
        $countries[] = array("code" => "UZ", "name" => "Uzbekistan", "d_code" => "+998", "icon" => "Uzbekistan.png");
        $countries[] = array("code" => "VU", "name" => "Vanuatu", "d_code" => "+678", "icon" => "Vanutau.png");
        $countries[] = array("code" => "VA", "name" => "Vatican City", "d_code" => "+39", "icon" => "Vatican_City.png");
        $countries[] = array("code" => "VE", "name" => "Venezuela", "d_code" => "+58", "icon" => "Venezuela.png");
        $countries[] = array("code" => "VN", "name" => "Vietnam", "d_code" => "+84", "icon" => "Vietnam.png");
        $countries[] = array("code" => "WF", "name" => "Wallis and Futuna", "d_code" => "+681", "icon" => "Wallis_and_Futuna.png");
        $countries[] = array("code" => "YE", "name" => "Yemen", "d_code" => "+967", "icon" => "Yemen.png");
        $countries[] = array("code" => "ZM", "name" => "Zambia", "d_code" => "+260", "icon" => "Zambia.png");
        $countries[] = array("code" => "ZW", "name" => "Zimbabwe", "d_code" => "+263", "icon" => "Zimbabwe.png");

        return $countries;
    }
}