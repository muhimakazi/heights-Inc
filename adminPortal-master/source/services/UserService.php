<?php
require_once("../entities/User.php");
class UserService
{

    public static function getUserCode()
    {
        $user = new User();
        return $user->getUserCode();
    }

    public static function getCompanyCode()
    {
        $user = new User();
        return $user->getCompanyCode();
    }

    public static function getUsers($selection, $search_query = '', $order = '', $limit = '')
    {
        $user = new User();
        return $user->getUsers($selection, $search_query, $order, $limit);
    }

    public static function insert($data)
    {
        return $data->insert($data);
    }

    public static function update($data)
    {
        return $data->update($data);
    }

    public static function delete($data)
    {
        return $data->delete($data);
    }

    public static function userList($selection, $search_query = '', $order = '', $limit = '')
    {
        $user = new User();
        return $user->userList($selection, $search_query, $order, $limit);
    }

    public static function checkUserDetails($selection, $search_query, $order = '', $limit = '')
    {
        $user = new User();
        return $user->checkUserDetails($selection, $search_query, $order, $limit);
    }

    public static function lock($data)
    {
        return $data->lock($data);
    }

    public static function unlock($data)
    {
        return $data->unlock($data);
    }

    public static function changePassword($data)
    {
        return $data->changePassword($data);
    }

    public static function updateToken($data)
    {
        return $data->updateToken($data);
    }
}
