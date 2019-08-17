<?php
/**
 * Created by PhpStorm.
 * User: liurongdong
 * Date: 2016/10/8
 * Time: 11:00
 */

namespace Ido\Tools\Util;


class Excel
{
    /** excel文件转数组
     * @param $file
     * @param $columns
     * @return array
     * @throws \PHPExcel_Exception
     */
    public function excelToArray($file, $columns)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $rs = [];
        for ($i = 1; $i <= $highestRow; $i++) {
            $row = [];
            foreach ($columns as $column) {
                $row[$column] = $objPHPExcel->getActiveSheet()->getCell($column . $i)->getValue();//获取A列的值
            }
            $rs[] = $row;
        }
        return $rs;
    }

   /** excel导出
    * @param array $data
    * @param array $title
    * @param string $filename
    */
   public function exportExcel($data = array(), $title = array(), $filename = 'report')
   {
       header("Content-type:application/octet-stream");
       header("Accept-Ranges:bytes");
       header("Content-type:application/vnd.ms-excel");
       header("Content-Disposition:attachment;filename=" . $filename . ".xls");
       header("Pragma: no-cache");
       header("Expires: 0");
       //导出xls 开始
       if (!empty($title)) {
           foreach ($title as $k => $v) {
               $title[$k] = iconv("UTF-8", "GB2312", $v);
           }
           $title = implode("\t", $title);
           echo "$title\n";
       }
       if (!empty($data)) {
           foreach ($data as $key => $val) {
               foreach ($val as $ck => $cv) {
                   $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
               }
               $data[$key] = implode("\t", $data[$key]);

           }
           echo implode("\n", $data);
       }
   }
}