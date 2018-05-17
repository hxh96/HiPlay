<?php
/**
 * 用户模型
 */

namespace Common\Model;


use Think\Model;

class UserModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('user');
    }

    /**
     * 注册
     * @param $data 用户信息
     * @return mixed 用户ID
     */
    public function addUser($data)
    {
        $id = $this->_db->add($data);
        return $id;
    }

    /**
     * 获取用户信息
     * @param $map 条件表达式
     * @return mixed 用户信息
     */
    public function getUser($map)
    {
        $arr = $this->_db->where($map)->find();
        return $arr;
    }


    /**
     * 获取所有信息
     * @param $map 条件表达式
     * @return mixed
     */
    public function getAll($map='')
    {
        $arr = $this->_db->where($map)->order('is_robot asc,id desc')->select();
        return $arr;
    }

    /**
     * 根据手机号查询用户信息
     * @param $phone 手机号
     * @return mixed 用户信息
     */
    public function getUserByPhone($phone)
    {
        $arr = $this->_db->where(array('phone'=>$phone))->find();
        return $arr;
    }


    /**
     * 根据id查询用户信息
     * @param $id id
     * @return mixed 用户信息
     */
    public function getById($id)
    {
        $arr = $this->_db->where(array('id'=>$id))->find();
        return $arr;
    }



    /**
     * 根据openid查询用户信息
     * @param $openid openid
     * @return mixed 用户信息
     */
    public function getByOpenid($openid)
    {
        $arr = $this->_db->where(array('openid'=>$openid))->find();
        return $arr;
    }

    /**
     * 更新用户信息
     * @param $data 更新数据
     * @return bool 结果
     */
    public function updateUser($data)
    {
        $re = $this->_db->save($data);
        return $re;
    }


    /**
     * 删除机器人
     * @param $id 机器人ID
     * @return mixed
     */
    public function deleteRobot($id)
    {
        $re = $this->_db->where(array('id'=>$id))->delete();
        return $re;
    }
}