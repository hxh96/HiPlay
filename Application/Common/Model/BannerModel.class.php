<?php
/**
 * Banner模型
 */

namespace Common\Model;


use Think\Model;

class BannerModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('banner');
    }


    /**
     * 获取所有数据
     * @param string $map 条件
     * @return mixed
     */
    public function getAll($map='')
    {
        return $this->_db->where($map)->order('sort desc,id desc')->select();
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

}