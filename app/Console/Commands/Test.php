<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $top = Category::query()->where("title", "顶级栏目")->first();

        $right = $result = [];

        $nodes = Category::query()
            ->select(["id", "type", "title", "rgt"])
            ->where("lft", ">=", $top->lft)
            ->where("lft", "<=", $top->rgt)
            ->get();

        foreach ($nodes as $node) {
            if (count($right)) {
                while ($right[count($right) - 1] < $node->rgt) {
                    array_pop($right);
                }
            }

            $title = $node->title;

            if (count($right)) {
                $title = "|-" . $title;
            }

            $result[] = [
                "id"    => $node->id,
                "type"  => $node->type,
                "title" => str_repeat("|-", count($right)) . $title,
                "name"  => $node->title
            ];
            $right[]  = $node->rgt;
        }
        dd($result);
    }
}
