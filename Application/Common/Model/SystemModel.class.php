<?php
/**
 * 系统配置模型
 */

namespace Common\Model;


use Think\Model;

class SystemModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('system');
    }

    /**
     * 根据var获取data内容
     * @param $var
     * @return mixed
     */
    public function getData($var)
    {
        return $this->_db->where(array('var'=>$var))->find()['data'];
    }

    /**
     * 根据var获取img内容
     * @param $var
     * @return mixed
     */
    public function getImg($var)
    {
        return $this->_db->where(array('var'=>$var))->find()['img'];
    }

    /**
     * 获取所有数据
     * @return mixed
     */
    public function getAll()
    {
        return $this->_db->order('id asc')->select();
    }

    /**
     * 根据ID获取一条数据
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
     * 修改一条数据
     * @param $data 数据
     * @return bool
     */
    public function editData($data)
    {
        return $this->_db->save($data);
    }

    /**
     * 删除数据
     * @param array|mixed $id id
     * @return mixed
     */
    public function deleteData($id)
    {
        return $this->_db->where(array('id'=>$id))->delete();
    }
}