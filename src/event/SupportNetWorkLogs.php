<?php

namespace bright\support\event;

use Illuminate\Queue\SerializesModels;


class SupportNetWorkLogs
{
    use SerializesModels;
    /**
     * 返回数据
     */
    public $request;
    /**
     * 备注
     * @var string
     */
    public $desc;
    /**
     * 类型
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $url;
    /**
     * 参数
     * @var
     */
    public $param;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request, string $type, string $desc,string $url,array $param)
    {
        $this->request = $request;
        $this->desc = $desc;
        $this->type = $type;
        $this->url = $url;
        //
    }
}
