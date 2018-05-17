<?php
/**
 * 管理员模型
 */

namespace Common\Model;


use Think\Model;

class AdminModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('admin');
    }

    /**
     * 根据用户名获取数据
     * @param $username 用户名
     * @return mixed
     */
    public function getUsername($username)
    {
        return $this->_db->where(array('username'=>$username))->find();
    }

    /**
     * 获取所有管理员
     * @return mixed
     */
    public function getAll()
    {
        return $this->_db->select();
    }


    /**
     * 根据id获取一条数据
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->_db->where(array('id'=>$id))->find();
    }


    /**
     * 添加管理员
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        return $this->_db->add($data);
    }

    /**
     * 更新数据
     * @param $data 数据
     * @return bool
     */
    public function updateData($data)
    {
        return $this->_db->save($data);
    }


}