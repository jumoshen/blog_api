<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitSiteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'site init';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * 1⃣ 配置文件
     * 2⃣ 生成app key
     * 3⃣ 生成jwt key
     * 4⃣ 运行迁移文件
     * 5⃣ 运行databaseSeeder
     *
     * @return mixed
     */
    public function handle()
    {
        shell_exec('cp .env.example .env');
        $this->call('key:generate');
        $this->call('jwt:secret');
        $this->call('migrate');
        $this->call('db:seed');
    }
}
