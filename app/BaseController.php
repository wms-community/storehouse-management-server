<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * Controller basic class
 */
abstract class BaseController
{
    /**
     * Request examples
     * @var \think\Request
     */
    protected $request;

    /**
     * Application examples
     * @var \think\App
     */
    protected $app;

    /**
     * Batch verification
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * Controller Middleware
     * @var array
     */
    protected $middleware = [];

    /**
     * Construction method
     * @access public
     * @param  App  $app  Application object
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // Controller initialization
        $this->initialize();
    }

    // initialization
    protected function initialize()
    {}

    /**
     * Validation data
     * @access protected
     * @param  array        $data     Data
     * @param  string|array $validate Validator name or validation rule array
     * @param  array        $message  Prompt information
     * @param  bool         $batch    Batch verification
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // Support scenario
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // Batch verification
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}
