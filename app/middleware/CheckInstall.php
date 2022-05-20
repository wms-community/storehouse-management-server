<?php
declare (strict_types = 1);

namespace app\middleware;

use app\controller\Install;
use think\db\exception\PDOException;
use think\facade\Db;

class CheckInstall extends Install
{
    /**
     * Processing requests
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // Judge whether the system is installed effectively
        if (!preg_match('/install/i',$request->url())){
            if (!self::isLock()){
                return redirect('/install');
            }
            try {
                $table = Db::name('config')->where('1=1')->find();
            }catch (PDOException $e){
                unlink(self::$installLock);
                return redirect('/install');
            }
        }// Install skip validation
        return $next($request);
    }
}
