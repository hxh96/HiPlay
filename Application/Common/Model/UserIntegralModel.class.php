<?php
/**
 * 用户积分模型
 */

namespace Common\Model;


use Think\Model;

class UserIntegralModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('UserIntegral');
    }

    /**
     * 获取所有信息
     * @param $map 条件表达式
     * @param $limit 条数
     * @return mixed
     */
    public function getAll($map='',$limit='')
    {
        $arr = $this->_db->where($map)->order('id desc')->limit($limit)->select();
        return $arr;
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
     * 根据用户ID获取数据
     * @param $userId 用户ID
     * @return mixed
     */
    public function getByUserId($userId)
    {
        return $this->_db->where(array('user_id'=>$userId,'status'=>1))->select();
    }

    /**
     * 根据积分说明获取数据
     * @param $userId 用户id
     * @param $instructions 积分说明
     * @return mixed
     */
    public function getByInstructions($userId,$instructions)
    {
        return $this->_db->where(array('user_id'=>$userId,'instructions'=>$instructions))->select();
    }

    /**
     * 添加数据
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

    /**
     * 删除数据
     * @param $id id
     * @return bool
     */
    public function deleteData($id)
    {
        return $this->_db->where(array('id'=>$id))->delete();
    }

}