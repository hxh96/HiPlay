<?php
/**
 * 用户互评模型
 */

namespace Common\Model;


use Think\Model;

class UserCommentsModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('UserComments');
    }


    /**
     * 根据条件获取所有
     * @param string $map 条件
     * @param string $limit 显示条数
     * @return mixed
     */
    public function getAll($map = '', $limit = '')
    {
        $arr = $this->_db->where($map)->limit($limit)->select();
        return $arr;
    }


    /**
     * 根据条件获取所有
     * @param string $map 条件
     * @param string $limit 显示条数
     * @return mixed
     */
    public function getAllJoinActivityAndUser($map = '', $limit = '')
    {
        $arr = $this->_db
            ->field([
                'hiwan_activity.title as activity_name',
                'hiwan_user.user_name',
                'hiwan_user_comments.id',
                'hiwan_user_comments.comment_user_id',
                'hiwan_user_comments.activity_id',
                'hiwan_user_comments.act_friendly',
                'hiwan_user_comments.is_punctual',
                'hiwan_user_comments.active_degree',
                'hiwan_user_comments.comment_content',
                'hiwan_user_comments.comment_object_user_id',
                'hiwan_user_comments.status',
                'hiwan_user_comments.inputtime',
            ])
            ->join('hiwan_activity on hiwan_user_comments.activity_id = hiwan_activity.id')
            ->join('hiwan_user on hiwan_user_comments.comment_user_id = hiwan_user.id')
            ->where($map)
            ->order('id desc')
            ->limit($limit)
            ->select();
        return $arr;
    }


    /**
     * 根据id查找数据
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        $arr = $this->_db->where(array('id' => $id))->find();
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
     * 根据条件查询一条
     * @param $map 条件
     * @return mixed
     */
    public function getOne($map)
    {
        $arr = $this->_db->where($map)->find();
        return $arr;
    }

}