<?php
/**
 * 分类模型
 */

namespace Common\Model;


use Think\Model;

class CategoryModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('category');
    }

    /**
     * 根据条件查询所有
     * @param $map 条件
     */
    public function getAll($map='')
    {
        $arr = $this->_db
            ->where($map)
            ->order('sort desc')
            ->select();
        return $arr;
    }

    /**
     * 根据pid找到所有数据
     * @param $pid pid
     * @return mixed
     */
    public function getByPid($pid)
    {
        $arr = $this->_db
            ->where(array('pid'=>$pid))
            ->order('sort desc')
            ->select();
        return $arr;
    }


    /**
     * 添加一条数据
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        $arr = $this->_db
            ->add($data);
        return $arr;
    }


    /**
     * 根据id找到数据
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        $arr = $this->_db
            ->where(array('id'=>$id))
            ->find();
        return $arr;
    }


    /**
     * 修改数据
     * @param $data 数据
     * @return bool
     */
    public function updateData($data)
    {
        $arr = $this->_db
            ->save($data);
        return $arr;
    }

    /**
     * 删除分类
     * @param $id 分类ID
     * @return mixed
     */
    public function deleteData($id)
    {
        $arr = $this->_db
            ->where(array('id'=>$id))
            ->delete();
        return $arr;
    }
}