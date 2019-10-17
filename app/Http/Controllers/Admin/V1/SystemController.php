<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 19-7-28
 * Time: 下午8:50
 */

namespace App\Http\Controllers\Admin\V1;


use App\Models\SystemConfig;
use Illuminate\Http\Request;

class SystemController extends BaseController {

    /**
     * 系统配置
    */
    public function configList(Request $request)
    {
        $limit = $request->input('limit',10);

        $systemConfig = SystemConfig::select('*');

        //搜索
        if (!empty($request->key))
            $systemConfig->where('key','like','%'.$request->key.'%');
        if (!empty($request->name))
            $systemConfig->where('name','like','%'.$request->name.'%');
        if (!empty($request->value))
            $systemConfig->where('value','like','%'.$request->value.'%');

        $info = $systemConfig->paginate($limit);
        foreach ($info['data'] as &$vo) {
            $vo['created_at'] = date('Y-m-d',$vo['created_at']);
            $vo['updated_at'] = date('Y-m-d',$vo['updated_at']);
        }
        return admin_success($info);
    }

    /**
     * 系统配置添加
    */
    public function configCreate(Request $request)
    {
        $data = $this->validate($request,[
            'key'=>'required',
            'value'=>'required',
            'name'=>'required',
            'explain'=>'',
        ]);
        if (SystemConfig::create($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }

    /**
     * 系统配置修改
    */
    public function configSave()
    {
        $data = $this->validate($request,[
            'key'=>'required',
            'value'=>'required',
            'name'=>'required',
            'explain'=>'',
        ]);
//        $data['permission'] = implode(',',$data['permission']);
        if (SystemConfig::where('id',$request->id)->update($data)) {
            return admin_success();
        }
        return admin_error(5000);
    }
}