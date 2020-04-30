<?php

use Illuminate\Database\Seeder;

class kamokuMstTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "";
        $sql .= " INSERT INTO `kamoku_mst` (`kamoku_id`, `kamoku`, `kamoku_sum`, `amount_flg`, `priority_flg`, `updated_at`, `created_at`) VALUES ";
        $sql .= " (1, 'DVD・CD', '遊興費', 2, 0, NULL, NULL), ";
        $sql .= " (2, 'インターネット', '通信費', 2, 0, NULL, NULL), ";
        $sql .= " (3, 'エステ', '美容費', 2, 0, NULL, NULL), ";
        $sql .= " (4, 'お菓子・ドリンク', '食費', 2, 9, NULL, NULL), ";
        $sql .= " (5, 'ガス', '水道光熱費', 2, 5, NULL, NULL), ";
        $sql .= " (6, 'ガソリン', '交通費', 2, 0, NULL, NULL), ";
        $sql .= " (7, 'クリーニング', '被服費', 2, 0, NULL, NULL), ";
        $sql .= " (8, 'その他', 'その他費用', 2, 0, NULL, NULL), ";
        $sql .= " (9, 'その他交通費', '交通費', 2, 0, NULL, NULL), ";
        $sql .= " (10, 'その他交際費', '交際費', 2, 0, NULL, NULL), ";
        $sql .= " (11, 'その他住宅費用', '住宅費', 2, 0, NULL, NULL), ";
        $sql .= " (12, 'その他保険', '保険', 2, 0, NULL, NULL), ";
        $sql .= " (13, 'その他医療費', '医療費', 2, 0, NULL, NULL), ";
        $sql .= " (14, 'その他教育費', '教育費', 2, 0, NULL, NULL), ";
        $sql .= " (15, 'その他税金', '税金', 2, 0, NULL, NULL), ";
        $sql .= " (16, 'その他美容費', '美容費', 2, 0, NULL, NULL), ";
        $sql .= " (17, 'その他被服費', '被服費', 2, 0, NULL, NULL), ";
        $sql .= " (18, 'その他通信費', '通信費', 2, 0, NULL, NULL), ";
        $sql .= " (19, 'その他遊興費', '遊興費', 2, 0, NULL, NULL), ";
        $sql .= " (20, 'その他雑貨・日用品', '生活雑貨・日用品', 2, 0, NULL, NULL), ";
        $sql .= " (21, 'バッグ・アクセサリ', '被服費', 2, 0, NULL, NULL), ";
        $sql .= " (22, '旅行・レジャー', '遊興費', 2, 3, NULL, NULL), ";
        $sql .= " (23, 'ローン返済(家計簿上では費用計上)', '住宅費', 2, 0, NULL, NULL), ";
        $sql .= " (24, '上下水道', '水道光熱費', 2, 5, NULL, NULL), ";
        $sql .= " (25, '交際費', '交際費', 2, 0, NULL, NULL), ";
        $sql .= " (26, '企業年金', '社会保険', 2, 0, NULL, NULL), ";
        $sql .= " (27, '住民税', '税金', 2, 0, NULL, NULL), ";
        $sql .= " (28, '修繕費', '住宅費', 2, 0, NULL, NULL), ";
        $sql .= " (29, '健康保険', '社会保険', 2, 0, NULL, NULL), ";
        $sql .= " (30, '公共交通', '交通費', 2, 0, NULL, NULL), ";
        $sql .= " (31, '化粧品・整髪料', '美容費', 2, 0, NULL, NULL), ";
        $sql .= " (32, '医薬品', '医療費', 2, 0, NULL, NULL), ";
        $sql .= " (33, '呑み会', '遊興費', 2, 6, NULL, NULL), ";
        $sql .= " (34, '固定資産税', '税金', 2, 0, NULL, NULL), ";
        $sql .= " (35, '固定電話', '通信費', 2, 0, NULL, NULL), ";
        $sql .= " (36, '国民年金・厚生年金・共済年金', '社会保険', 2, 0, NULL, NULL), ";
        $sql .= " (37, '塾・習い事', '教育費', 2, 0, NULL, NULL), ";
        $sql .= " (38, '外食', '外食', 2, 10, NULL, NULL), ";
        $sql .= " (39, '学費', '教育費', 2, 0, NULL, NULL), ";
        $sql .= " (40, '学資保険', '保険', 2, 0, NULL, NULL), ";
        $sql .= " (41, '定期', '交通費', 2, 0, NULL, NULL), ";
        $sql .= " (42, '家具', '生活雑貨・日用品', 2, 2, NULL, NULL), ";
        $sql .= " (43, '家賃', '住宅費', 2, 3, NULL, NULL), ";
        $sql .= " (44, '家電・ＰＣ', '生活雑貨・日用品', 2, 1, NULL, NULL), ";
        $sql .= " (45, '慶弔費', '交際費', 2, 0, NULL, NULL), ";
        $sql .= " (46, '所得税', '税金', 2, 0, NULL, NULL), ";
        $sql .= " (47, '携帯', '通信費', 2, 0, NULL, NULL), ";
        $sql .= " (48, '文房具', '生活雑貨・日用品', 2, 0, NULL, NULL), ";
        $sql .= " (49, '日用品', '生活雑貨・日用品', 2, 10, NULL, NULL), ";
        $sql .= " (50, '映画', '遊興費', 2, 4, NULL, NULL), ";
        $sql .= " (51, '書籍', '教育費', 2, 4, NULL, NULL), ";
        $sql .= " (53, '服・靴', '被服費', 2, 3, NULL, NULL), ";
        $sql .= " (54, '理容院・美容院', '美容費', 2, 4, NULL, NULL), ";
        $sql .= " (55, '生命保険・医療保険', '保険', 2, 0, NULL, NULL), ";
        $sql .= " (56, '病院', '医療費', 2, 0, NULL, NULL), ";
        $sql .= " (57, '自動車保険', '保険', 2, 0, NULL, NULL), ";
        $sql .= " (58, '自動車重量税', '税金', 2, 0, NULL, NULL), ";
        $sql .= " (59, '部活', '教育費', 2, 0, NULL, NULL), ";
        $sql .= " (60, '郵便・宅配便', '通信費', 2, 0, NULL, NULL), ";
        $sql .= " (61, '雇用保険', '社会保険', 2, 0, NULL, NULL), ";
        $sql .= " (62, '雑貨', '生活雑貨・日用品', 2, 1, NULL, NULL), ";
        $sql .= " (63, '電気', '水道光熱費', 2, 5, NULL, NULL), ";
        $sql .= " (64, '食費', '食費', 2, 10, NULL, NULL), ";
        $sql .= " (65, '定期送金', '固定収入', 1, 6, NULL, NULL), ";
        $sql .= " (66, '旅行', '遊興費', 2, 2, NULL, NULL), ";
        $sql .= " (67, '飲み会', '遊興費', 2, 5, NULL, NULL); ";
 
        DB::statement($sql);
    }
}
