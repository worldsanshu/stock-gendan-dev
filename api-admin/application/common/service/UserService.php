<?php
// +----------------------------------------------------------------------
// | 用户服务层
// +----------------------------------------------------------------------
namespace app\common\service;

use app\member\model\Member;
use app\common\model\AdminUser;

class UserService
{
    //获得代理旗下用户ids
    public function getAgentUserIds()
    {
        $ids = [];
        $admin_user = AdminUser::where('id', UID)->find();
        if ($admin_user['role'] == 2) {
            $mid = $admin_user['for_user'];
            $ids = Member::where('agent_far',$mid)->cache(30)->column('id');
        }
        return $ids;
    }
    //获得代理旗下用户 where sql
    public function getAgentSql($field='uid')
    {
        $ids = [];
        if (defined('UID') && constant('UID')) {
            $admin_user = AdminUser::where('id', UID)->find();
            if ($admin_user['role'] == 2) {
                $mid = $admin_user['for_user'];
                $ids = Member::where('agent_far',$mid)->cache(30)->column('id');
            }else{
                return '1=1';
            }
            return [[$field,'in',$ids]];
        } else {
            return '1=1';
        }
    }
    //获得用户信息
    public function getUserInfo($id,$field='id,mobile,name')
    {
        $ids = Member::field($field)->cache(60)->find($id);
        return $ids;
    }
    
    //代理合伙人搜索整理map查询条件
    public function agentPartnerSearchMap($map,$field='uid') {
        $seachKey = '';
        foreach ($map as $key => $subArray) {
            if ($subArray[0] === 'agent_search') {
                $seachKey = $subArray[2];
                $agentUid = Member::where('mobile',$seachKey)->whereOr('id',$seachKey)->whereOr('name',$seachKey)->value('id');
                if (empty($agentUid)) {
                    $map[$key] = ['1','<>','1'];
                }else{
                    $ids = Member::where('agent_far',$agentUid)->cache(30)->column('id');
                    $map[$key] = [$field,'in',$ids];
                }
            }
            if ($subArray[0] === 'partner_search') {
                $seachKey = $subArray[2];
                $user = Member::where('mobile',$seachKey)->whereOr('id',$seachKey)->whereOr('name',$seachKey)->field('id,partner_parent_net,partner_parent_level')->find();
                if (empty($user)) {
                    $map[$key] = ['1','<>','1'];
                }else{
                    $path = $user->partner_parent_net . ',' . $user->id;
                    $level = $user->partner_parent_level + 3; //计算三层团队
                    $teamIds = Member::where('1=1')
                    ->where([['partner_parent_net', 'like', $path . '%']])
                    //->where([['partner_parent_level', '<=', $level]])
                    //->where('is_buy',1)
                    ->column('id');
                    $map[$key] = [$field,'in',$teamIds];
                }
            }
        };
        return $map;
    }
    
    //获得代理旗下用户数据map
    public function getAgentMap($map,$field='uid')
    {
        $ids = [];
        if (defined('UID') && constant('UID')) {
            $admin_user = AdminUser::where('id', UID)->find();
            if ($admin_user['role'] == 2) {
                $mid = $admin_user['for_user'];
                $ids = Member::where('agent_far',$mid)->cache(30)->column('id');
                $map[] = [[$field,'in',$ids]];
            }
        }
        return $map;
    }
}