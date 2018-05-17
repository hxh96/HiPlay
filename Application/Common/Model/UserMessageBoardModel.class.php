<?php
/**
 * 用户留言板模型
 */

namespace Common\Model;


use Think\Model;

class UserMessageBoardModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('UserMessageBoard');
    }


    /**
     * 根据条件获取所有
     * @param string $map 条件
     * @param string $limit 条数
     * @return mixed
     */
    public function getAll($map = '',$limit = '')
    {
        $arr = $this->_db->where($map)->order('id desc')->limit($limit)->select();
        return $arr;
    }


    /**
     * 关联活动表根据条件获取所有
     * @param string $map 条件
     * @param string $limit 条数
     * @return mixed
     */
    public function getAllJoinActivity($map = '',$limit = '')
    {
        $arr = $this->_db
            ->field([
                'hiwan_user_message_board.id',
                'hiwan_user_message_board.activity_id',
                'hiwan_user_message_board.user_id',
                'hiwan_user_message_board.content',
                'hiwan_user_message_board.reply',
                'hiwan_user_message_board.is_see',
                'hiwan_user_message_board.status',
                'hiwan_user_message_board.inputtime',
                'hiwan_activity.title',
            ])
            ->join('hiwan_activity on hiwan_user_message_board.activity_id = hiwan_activity.id')
            ->where($map)
            ->order('id desc')
            ->limit($limit)
            ->select();
        return $arr;
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
     * @return mixed
     */
    public function updateData($data)
    {
        $arr = $this->_db->save($data);
        return $arr;
    }

}