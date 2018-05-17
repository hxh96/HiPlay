<?php
/**
 * 用户相册模型
 */

namespace Common\Model;


use Think\Model;

class UserPhotoAlbumModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('UserPhotoAlbum');
    }


    /**
     * 获取所有
     * @param $map 条件
     * @return mixed
     */
    public function getAll($map='')
    {
        return $this->_db->where($map)->order('status desc,id desc')->select();
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