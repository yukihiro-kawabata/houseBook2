<?php

use Illuminate\Database\Seeder;

class cashConstantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "";
        $sql .= " INSERT INTO `constant_cash` (`id`, `name`, `price`, `kamoku_id`, `tag`, `comment`, `detail`, `priceFlg`, `sumFlg`, `delete_flg`, `created_at`, `updated_at`) VALUES ";
        $sql .= " (521, 'devit', 1043, 64, '食費', '水2l×9', NULL, 2, 1, 0, '2020-04-18 23:18:22', '2020-04-18 23:18:22'), ";
        $sql .= " (524, 'devit', 936, 49, '日用品', 'ボディソープ', NULL, 2, 1, 0, '2020-04-19 18:09:48', '2020-04-19 18:09:48'), ";
        $sql .= " (525, 'john', 4186, 64, '食費', '炭酸水_Amazon', NULL, 2, 1, 0, '2020-04-22 22:32:53', '2020-04-22 22:32:53'), ";
        $sql .= " (526, 'john', 890, 64, '食費', 'キトサン_Amazon', NULL, 2, 1, 0, '2020-04-22 22:33:30', '2020-04-22 22:33:30'), ";
        $sql .= " (527, 'john', 1020, 64, '食費', 'ブルーベリーサプリ_Amazon', NULL, 2, 1, 0, '2020-04-22 22:34:08', '2020-04-22 22:34:08'); ";
        DB::statement($sql);
    }
}