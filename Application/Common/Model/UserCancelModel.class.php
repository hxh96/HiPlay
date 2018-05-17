<?php
/**
 * 用户取消模型
 */

namespace Common\Model;


use Think\Model;

class UserCancelModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('UserCancel');
    }

    /**
     * 根据条件获取所有
     * @param $map 条件
     * @return mixed
     */
    public function getAll($map='')
    {
        $arr = $this->_db->where($map)->order('id desc')->select();
        return $arr;
    }


    /**
     * 找到对应取消者的取消信息
     * @param $id 取消者id
     * @param $limit 条数
     * @return mixed
     */
    public function getByUserId($id,$limit='')
    {
        $arr = $this->_db->where(array('user_id'=>$id))->limit($limit)->select();
        return $arr;
    }


    /**
     * 删除取消的机器人活动信息
     * @param $id 机器人发布活动ID
     * @return mixed
     */
    public function deleteRobot($id)
    {
        $arr = $this->_db->where(array('activity_id'=>$id))->delete();
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
}