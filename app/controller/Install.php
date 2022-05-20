<?php
namespace app\controller;

//install program

use think\db\exception\PDOException;
use think\db\Query;
use think\exception\HttpResponseException;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;
use think\facade\Env;
use think\facade\Lang;

class Install
{
    protected static $installLock = '';
    protected static $envFile = '';
    protected static $sqlFile = '';
    protected static $defaultConfig = [
        'hostname' => '127.0.0.1',
        'hostport' => 3306,
        'database' => 'kfgl',
        'username' => 'root',
        'password' => 'root',
        'prefix' => 'kfgl_',
    ];
    protected static $db;
    protected static $envData = [];
    public function __construct()
    {
        self::init();
    }
    /**
     * Initialize common information
     */
    private static function init()
    {
        // Installation lock address
        self::$installLock = app()->getAppPath() . 'install.lock';
        // Environment variable location
        self::$envFile = app()->getRootPath() . '.env';
        // Database installation file location
        self::$sqlFile = app()->getRootPath() . 'install.sql';
    }
    /**
     * Judge installation lock
     * @return bool
     */
    public static function isLock()
    {
        self::init();
        if (file_exists(self::$installLock)) {
            return true;
        } else
            return false;
    }
    /**
     * Get the content in Env and parse it into an array to return
     * @param string $name
     * @return array|false|mixed|null
     */
    private function loadEnv(string $name = '')
    {
        if (!file_exists(self::$envFile) || !is_file(self::$envFile))
            return null;
        if (empty(self::$envData && file_exists(self::$envFile) && is_file(self::$envFile))) {
            self::$envData = parse_ini_file(self::$envFile, true, INI_SCANNER_RAW) ?: [];
        }
        if (empty($name)) {
            return self::$envData;
        }
        $name = strtoupper($name);
        if (isset($env[$name]))
            return $env[$name];
        return null;
    }
    /**
     * Write env file
     * @param string $name key
     * @param array|string|null $value Assignment, only one-dimensional array is supported
     * @return void
     */
    private function writeEnv(string $name, $value = ''): void
    {
        $path = self::$envFile;
        $env = $this->loadEnv();

        $name = strtoupper($name);
        if ('' === $value)
            return;
        if (is_array($value))
            $value = array_change_key_case($value, CASE_UPPER);
        if (isset($env[$name]) && is_array($env[$name]) && is_array($value)) {
            $value = array_merge($env[$name], $value); // merge
        }
        if (!is_array($value) && !is_string($value)) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } else
                $value = (string)$value;
        }
        $env[$name] = $value;
        unset($value);
        asort($env);
        // Write file to blank
        $write = file_put_contents($path, '');
        $bindStr = function ($key, $val = null) {
            $key = strtoupper($key);
            if (empty($val))
                $key = '[' . $key . ']';
            else
                $key .= ' = ' . $val;
            return $key . PHP_EOL;
        };
        if (false !== $write) {
            foreach ($env as $item => $value) {
                $envStr = '';
                if (is_array($value)) {
                    $envStr .= $bindStr($item);
                    foreach ($value as $key => $val) {
                        if (!is_array($val))
                            $envStr .= $bindStr($key, $val);
                    }
                } else {
                    $envStr .= $bindStr($item, $value);
                }
                file_put_contents($path, $envStr . PHP_EOL . PHP_EOL, FILE_APPEND);// Write once in a loop
            }
        } else {
            $this->endJson(500, 'Failed to write env file');
        }
    }
    /**
     * mysqli Connector
     * @return false|\mysqli|string
     */
    private static function connectMysql()
    {
        if (empty(self::$db)) {
            // Get parameters
            $hostname = input('hostname', '', 'htmlspecialchars');
            $hostport = input('hostport', '', 'htmlspecialchars');
            $username = input('username', '', 'htmlspecialchars');
            $password = input('password', '', 'htmlspecialchars');
            $database = input('dbname', '', 'htmlspecialchars');
            $prefix = input('prefix', '', 'htmlspecialchars');

            self::$defaultConfig['hostname'] = !empty($hostname) ? $hostname : self::$defaultConfig['hostname'];
            self::$defaultConfig['hostport'] = !empty($hostport) ? $hostport : self::$defaultConfig['hostport'];
            self::$defaultConfig['username'] = !empty($username) ? $username : self::$defaultConfig['username'];
            self::$defaultConfig['password'] = !empty($password) ? $password : self::$defaultConfig['password'];
            self::$defaultConfig['database'] = !empty($database) ? $database : self::$defaultConfig['database'];
            self::$defaultConfig['prefix'] = !empty($prefix) ? $prefix : self::$defaultConfig['prefix'];
            try {
                $db = mysqli_connect(self::$defaultConfig['hostname'],
                    self::$defaultConfig['username'],
                    self::$defaultConfig['password'],
                    self::$defaultConfig['database'],
                    self::$defaultConfig['hostport']);
                $db->query('charset = utf8');
                mysqli_query($db, 'charset=utf8');
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            self::$db = $db;
        }
        return self::$db;
    }
    /**
     * TP built-in function output JSON, API interface
     * @param int $status
     * @param string $msg
     * @param array $data
     */
    private function endJson($status = 200, $msg = 'Request succeeded', $data = [],$other = [])
    {
        $data = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
        ];
        if (is_array($other))
            $data = array_merge($data,$other);
        $response = json($data);
        throw new HttpResponseException($response);
    }
    /**
     * Send the front page and assign the initial value
     * Display protocol, etc
     * @return string|\think\response\Redirect
     */
    public function index()
    {
        if (self::isLock()) { // Determine whether it is locked
            return redirect('/');
        } else {
            return View::fetch('install/index', [
                'sqlHost' => Env::get('database.hostname', self::$defaultConfig['hostname']),
                'sqlPort' => Env::get('database.hostport', self::$defaultConfig['hostport']),
                'sqlName' => Env::get('database.database', self::$defaultConfig['database']),
                'sqlUser' => Env::get('database.username', self::$defaultConfig['username']),
                'sqlPass' => Env::get('database.password', self::$defaultConfig['password']),
                'sqlPrefix' => Env::get('database.prefix', self::$defaultConfig['prefix']),
            ]);
        }
    }
    // Version number
    public function version()
    {
        header('Content-Type:application/json;charset=utf-8');
        $data = array('version' => '0.0.1-alpha-202204220120');
        $result = array('status' => 200, 'data' => $data);
        exit (json_encode($result));
    }
    /**
     * Environmental verification, not yet developed
     * Step 1
     */
    public function envmonitor()
    {
        header('Content-Type:application/json;charset=utf-8');
        $data = array('envs' => [], 'modules' => [], 'folders' => [], 'error' => 0);
        $result = array('status' => 200, 'data' => $data);
        exit (json_encode($result));
    }
    /**
     * Database configuration
     * Step 2
     */
    public function dbmonitor()
    {
        $db = self::connectMysql();
        if (is_string($db)) {
            $this->endJson(500, $db);
        }
        $this->writeEnv('database', self::$defaultConfig);

        $check = mysqli_query($db, 'SELECT * FROM `' . self::$defaultConfig['prefix'] . 'config`');

        if (false !== $check) {
            $this->endJson(500, 'The database table already exists. Please delete the existing library table before installing');
        }
        // Return information
        $this->endJson(200, 'database available');

    }
    /**
     * Data validation and caching
     */
    function codemonitor(){
        $sitename      = input('sitename', '', 'htmlspecialchars');
        $domain        = input('domain', '', 'htmlspecialchars');
        $manager       = input('manager', '', 'htmlspecialchars');
        $manager_pwd   = input('manager_pwd', '', 'htmlspecialchars');
        $manager_ckpwd = input('manager_ckpwd', '', 'htmlspecialchars');
        $manager_email = input('manager_email', '', 'htmlspecialchars');
        if (empty($sitename))
            $this->endJson(0, 'Please enter a site name');
        if (empty($domain))
            $this->endJson(0, 'Please enter URL');
        if (empty($manager))
            $this->endJson(0, 'Please enter the admin account');
        if (empty($manager_pwd))
            $this->endJson(0, 'Please input a password');
        if (empty($manager_email))
            $this->endJson(0, 'Please input a email');
        if ($manager_pwd !== $manager_ckpwd)
            $this->endJson(0, 'The two passwords are different');
        $siteTmpConfig = [
            'sitename' => $sitename,
            'domain' => $domain,
            'manager' => $manager,
            'manager_pwd' => $manager_pwd,
            'manager_ckpwd' => $manager_ckpwd,
            'manager_email' => $manager_email,
        ];
        Session::set('install_site_config', $siteTmpConfig);
        $this->endJson(200, 'Success');
    }
    /**
     * Establish database and configure important information such as site and administrator
     * Step 3
     */
    function installing()
    {
        $line = input('line/d',0);// Which line was last read
        // File verification
        $sqlPath = self::$sqlFile;
        if (!file_exists($sqlPath)){
            $this->endJson(500, 'Database file does not exist, in Path : ' . $sqlPath);
        }
        $op = fopen($sqlPath,'r') or $this->endJson(400,'open file failure');
        $i = 1;
        $tmpSql = '';
        while (false === feof($op)){
            $t = fgets($op);
            if ($i === $line && strpos($t,';')){
                // Execution line found
                $t_arr = explode(';',$t);
                unset($t_arr[0]);
                $tmpSql .= implode(';',$t_arr);
            }elseif ($i>=$line){
                if(strpos($t,';')){
                    $t_arr = explode(';',$t);
                    $tmpSql .= $t_arr[0];
                    break;
                }else{
                    $tmpSql .= $t;
                }
            }
            ++$i;
        }
        fclose($op);

        $msg = '';
        $run =  false;
        if (!empty($tmpSql)){
            // Get prefix
            $prefix = Env::get('database.prefix',self::$defaultConfig['prefix']);
            $tmpSql = str_replace('__PREFIX__',$prefix,$tmpSql);
            // Execute SQL
            try{
                $run = false !== Db::execute($tmpSql);
            }catch (\Exception $e){
            }
            // Matching SQL
            if (strpos(strtolower($tmpSql),'insert')){
                // Insert
                $msg = 'insert';
            }elseif (strpos(strtolower($tmpSql),'create')){
                // Establish
                $msg = 'create';
            }elseif (strpos(strtolower($tmpSql),'alter')){
                // Indexes
                $msg = 'alter';
            }elseif (strpos(strtolower($tmpSql),'dr')){
                // Delete
                $msg = 'delete';
            }
            $name = explode('`',$tmpSql);
            if (isset($name[1]))
                $msg .= ' table [' . $name[1] . '] ' . ($run?'success':'Failed');
        }
        // Execute all. Set status to 200 to set important information
        $this->endJson(
            empty($tmpSql)?200:400,
            $msg,[
                'run' => $run,
                'sql' => $tmpSql,
        ],
            [
                // Set start point
                "num"  => $i,
            ]
        );
    }
    /**
     * Check administrator and other information. Step 4
     * Return required information. Step 5
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function complete()
    {
        // Configure time variables
        $date = date("Y-m-d");
        // Cache configuration
        $session = Session::get('install_site_config');
        // Database configuration
        $config = Db::name('config')->where('1=1')->order('id desc')->find();
        $admin_config = Db::name('user')->where('1=1')->order('id desc')->find();
        // Define database configuration
        $data = [
            'sitename' => $session['sitename'],
            'siteurl' => $session['domain'],
            'support' => $session['manager_email'],
        ];
        // Define administrator information
        $admin_data = [
            'username' => $session['manager'],
            'password' => MD5($session['manager_pwd']),
            'mail' => $session['manager_email'],
            'regdate' => $date,
        ];
        // Modify database configuration
        if (!empty($config)) {
            Db::name('config')
                ->where('id', '=', $config['id'])
                ->update($data);
        } else {
            Db::name('config')->insert($data);
        }
        // Modify administrator configuration
        if (!empty($admin_config)) {
            Db::name('user')
                ->where('id', '=', $config['id'])
                ->update($admin_data);
        } else {
            Db::name('user')->insert($admin_data);
        }
        // Create installation lock
        file_put_contents(self::$installLock, '安装锁');
        $this->endJson(200, 'Installed success', [
            // Installation is complete
            'admin_url' => '/admin/',
            'admin_name' => $session['manager'],
            'admin_pass' => $session['manager_pwd'],
        ]);
    }

}
