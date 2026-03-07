<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'nickname' => 'require|length:2,20|chsAlphaNum|regex:/^\S+$/u|filterSpecial|unique:users,name',
    ];
    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'nickname.require'     => '昵称不能为空',
        'nickname.length'      => '昵称长度需在2~20个字符之间',
        'nickname.chsAlphaNum' => '昵称只能包含中文、字母和数字',
        'nickname.filterSpecial'=> '昵称包含非法字符',
        'nickname.unique'       => '昵称已被使用',
        'nickname.regex' => '昵称不能包含空格',
    ];
     // 自定义验证规则：过滤敏感词或特殊字符
    protected function filterSpecial($value, $rule, $data = [])
    {
        $forbidden = ['admin', 'test','小风','管理员']; // 从配置文件读取
        foreach ($forbidden as $word) {
            if (stripos($value, $word) !== false) {
                return false;
            }
        }
        return true;
    }
}
