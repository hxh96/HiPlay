<?php
/**
 * 活动费用模型
 */

namespace Common\Model;


use Think\Model;

class ActivityCateModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('ActivityCate');
    }

    /**
     * 根据ID获取一条数据
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        $arr = $this->_db->where(array('id'=>$id))->find();
        return $arr;
    }

    /**
     * 获取所有数据
     * @param $map 条件
     * @return mixed
     */
    public function getAll($map='')
    {
        $arr = $this->_db->where($map)->select();
        return $arr;
    }


    /**
     * 添加数据
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        $arr = $this->_db->add($data);
        return $arr;
    }

    /**
     * 更新数据
     * @param $data 数据
     * @return bool
     */
    public function updateData($data)
    {
        $arr = $this->_db->save($data);
        return $arr;
    }

    /**
     * 删除数据
     * @param $id id
     * @return mixed
     */
    public function deleteData($id)
    {
        $re = $this->_db->where(array('id'=>$id))->delete();
        return $re;
    }



}