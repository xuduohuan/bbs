<?php
/**
 * @copyright tuhuokeji
 * @author zaizai <297897479@qq.com>
 * @author xuhuan <[<email address>]>
 * 
 */

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once  IA_ROOT . '/addons/lth/PHPExcel/Classes/PHPExcel.php';


//导出业绩汇总表
function totalperformance($data)
{
    global $_W, $_GPC;
    $weid = $_W['weid'];
    if(!isset($data)){
        return false;
    }


    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '序号')
        ->setCellValue('B1', '月嫂姓名')
        ->setCellValue('C1', '服务级别')
        ->setCellValue('D1', '客户姓名')
        ->setCellValue('E1', '服务时间')
        ->setCellValue('F1', '完单情况')
        ->setCellValue('G1', '总服务费')
        ->setCellValue('H1', '月嫂工资+奖金')
        ->setCellValue('I2', '介绍人')
        ->setCellValue('J1', '金额')
        ->setCellValue('K1', '总毛利')
        ->setCellValue('L1', '订金时间')
        ->setCellValue('M1', '订金金额')
        ->setCellValue('N1', '尾款时间')
        ->setCellValue('O1', '尾款金额')
        ->setCellValue('P1', '续单')
        ->setCellValue('Q1', '公司利润')
        ->setCellValue('R1', '个人工资');

    if(is_array($data)){
        foreach ($data as $k=>$value) {
            $k+=2;
            if($value['mtype']=='0'){
                $servertime=$value['edc'].'~'.dateadd($value['edc'],26);
            }else{
                $servertime=$value['bs'].'~'.dateadd($value['bs'],26);
            }
            if(strpos($value['mt'],'<br/>')){
                $mt=str_replace('<br/>',',',$value['mt']);
            }else{
                $mt=$value['mt'];
            }

            // Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k . '', $k-1)
                ->setCellValue('B' . $k . '', $mt)
                ->setCellValue('C' . $k . '', $value['lname'])
                ->setCellValue('D' . $k . '', $value['clientname'])
                ->setCellValue('E' . $k . '', $servertime)
                ->setCellValue('F' . $k . '', 26)
                ->setCellValue('G' . $k . '', $value['cost'])
                ->setCellValue('H' . $k . '', $value['mtsalary'])
                ->setCellValue('I' . $k . '', $value['source'])
                ->setCellValue('J' . $k . '', $value['tip'])
                ->setCellValue('K' . $k . '', $value['fee'])
                ->setCellValue('L' . $k . '', $value['deposittime'])
                ->setCellValue('M' . $k . '', $value['earnest'])
                ->setCellValue('N' . $k . '', $value['paydate'])
                ->setCellValue('O' . $k . '', $value['finalpay'])
                ->setCellValue('P' . $k . '', '')
                ->setCellValue('Q' . $k . '', '')
                ->setCellValue('Q' . $k . '', '');
        }
    }

// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('总业绩汇总表 '.date('Y-m-d'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="总业绩汇总表'.date('Y-m-d').'.xlsx"');
    header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}



//导出月嫂工资表,参数2,3是所选时间段，参数4是部门
function msalary($data,$segment,$segment2,$dpart)
{
    global $_W, $_GPC;
    $weid = $_W['weid'];
    if(!isset($data) || !isset($segment) || !isset($segment2)){
        return false;
    }
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    $objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
    $objPHPExcel->getActiveSheet()->mergeCells('F1:H1');
    $objPHPExcel->getActiveSheet()->mergeCells('B3:L3');

    if($dpart){
        $dname=pdo_fetch('select name from '.tablename('jber_department').' where id=:id',array(':id'=>$dpart));
    }else{
        $dname['name']='所有';
    }

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '制表日期')
        ->setCellValue('B1', date('Y-m-d'))
        ->setCellValue('E1', '时间段')
        ->setCellValue('F1', $segment.'——'.$segment2)
        ->setCellValue('A2', '序号')
        ->setCellValue('B2', '月嫂姓名')
        ->setCellValue('C2', '客户名')
        ->setCellValue('D2', '服务级别')
        ->setCellValue('E2', '服务日期')
        ->setCellValue('F2', '服务天数')
        ->setCellValue('G2', '客户反馈')
        ->setCellValue('H2', '基本工资')
        ->setCellValue('I2', '奖金')
        ->setCellValue('J2', '额外奖金')
        ->setCellValue('K2', '合计金额')
        ->setCellValue('L2', '备注')
        ->setCellValue('A3', '分部')
        ->setCellValue('B3', $dname['name']);

    if(is_array($data)){
        foreach ($data as $k=>$value) {
          $k+=4;
            if($value['feedback']=='1'){
                $feedback='满意';
                $basicsal=round($value['rsalary']*0.7,2);
                $rewardsal=round($value['rsalary']*0.3,2);
            }else{
                $feedback='投诉';
                $basicsal=$value['rsalary'];
                $rewardsal=0;
            }
            // Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k . '', $k-3)
                ->setCellValue('B' . $k . '', $value['mtname'])
                ->setCellValue('C' . $k . '', $value['clientname'])
                ->setCellValue('D' . $k . '', $value['lname'])
                ->setCellValue('E' . $k . '', $value['rsdate'].'~'.$value['redate'])
                ->setCellValue('F' . $k . '', $value['workds']-$value['half']*0.5)
                ->setCellValue('G' . $k . '', $feedback)
                ->setCellValue('H' . $k . '', $basicsal)
                ->setCellValue('I' . $k . '', $rewardsal)
                ->setCellValue('J' . $k . '', $value['reward'])
                ->setCellValue('K' . $k . '', $value['totalsal'])
                ->setCellValue('L' . $k . '', '');
        }
    }

// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('月嫂工资表 '.date('Y-m-d'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="月嫂工资表'.date('Y-m-d').'.xlsx"');
    header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}




//导出业务统计表
function buinesscount($data)
{
    global $_W, $_GPC;
    $weid = $_W['weid'];
    if(!isset($data)){
        return false;
    }

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    $objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
    $objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
    $objPHPExcel->getActiveSheet()->mergeCells('E1:E2');

    $objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
    $objPHPExcel->getActiveSheet()->mergeCells('F1:G1');
    $objPHPExcel->getActiveSheet()->mergeCells('H1:I1');
    $objPHPExcel->getActiveSheet()->mergeCells('J1:K1');
    $objPHPExcel->getActiveSheet()->mergeCells('L1:M1');
    $objPHPExcel->getActiveSheet()->mergeCells('N1:O1');
    $objPHPExcel->getActiveSheet()->mergeCells('P1:Q1');
    $objPHPExcel->getActiveSheet()->mergeCells('R1:S1');
    $objPHPExcel->getActiveSheet()->mergeCells('T1:U1');
    $objPHPExcel->getActiveSheet()->mergeCells('V1:W1');
    $objPHPExcel->getActiveSheet()->mergeCells('X1:Y1');
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '月份')
        ->setCellValue('B1', '总单数')
        ->setCellValue('C1', '比例')
        ->setCellValue('C2', '月嫂')
        ->setCellValue('D2', '育儿嫂')
        ->setCellValue('E1', '总毛利')
        ->setCellValue('F1', '比例')
        ->setCellValue('F2', '月嫂')
        ->setCellValue('G2', '育儿嫂')
        ->setCellValue('H1', '一级月嫂')
        ->setCellValue('H2', '单数')
        ->setCellValue('I2', '毛利')
        ->setCellValue('J1', '二级月嫂')
        ->setCellValue('J2', '单数')
        ->setCellValue('K2', '毛利')
        ->setCellValue('L1', '三级月嫂')
        ->setCellValue('L2', '单数')
        ->setCellValue('M2', '毛利')
        ->setCellValue('N1', '四级月嫂')
        ->setCellValue('N2', '单数')
        ->setCellValue('O2', '毛利')
        ->setCellValue('P1', '五级月嫂')
        ->setCellValue('P2', '单数')
        ->setCellValue('Q2', '毛利')
        ->setCellValue('R1', '金牌一月嫂')
        ->setCellValue('R2', '单数')
        ->setCellValue('S2', '毛利')
        ->setCellValue('T1', '金牌二月嫂')
        ->setCellValue('T2', '单数')
        ->setCellValue('U2', '毛利')
        ->setCellValue('V1', '育儿嫂')
        ->setCellValue('V2', '单数')
        ->setCellValue('W2', '毛利')
        ->setCellValue('X1', '育婴师')
        ->setCellValue('X2', '单数')
        ->setCellValue('Y2', '毛利');

        $we=2;
        if(is_array($data)){
            foreach ($data as $k=>$value) {
                $we++;
                $k = $we;
            // Miscellaneous glyphs, UTF-8
            @$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k . '', $value['month'])
                ->setCellValue('B' . $k . '', $value['mtotal'])
                ->setCellValue('C' . $k . '', $value['mtotal1'])
                ->setCellValue('D' . $k . '', $value['mtotal']-$value['mtotal1'])
                ->setCellValue('E' . $k . '', $value['feetotal'])
                ->setCellValue('F' . $k . '', $value['feetotal1'])
                ->setCellValue('G' . $k . '', $value['feetotal']-$value['feetotal1'])
                ->setCellValue('H' . $k . '', $value['many_l'][7])
                ->setCellValue('I' . $k . '', $value['feetotal_l'][7])
                ->setCellValue('J' . $k . '', $value['many_l'][6])
                ->setCellValue('K' . $k . '', $value['feetotal_l'][6])
                ->setCellValue('L' . $k . '', $value['many_l'][5])
                ->setCellValue('M' . $k . '', $value['feetotal_l'][5])
                ->setCellValue('N' . $k . '', $value['many_l'][4])
                ->setCellValue('O' . $k . '', $value['feetotal_l'][4])
                ->setCellValue('P' . $k . '', $value['many_l'][3])
                ->setCellValue('Q' . $k . '', $value['feetotal_l'][3])
                ->setCellValue('R' . $k . '', $value['many_l'][2])
                ->setCellValue('S' . $k . '', $value['feetotal_l'][2])
                ->setCellValue('T' . $k . '', $value['many_l'][1])
                ->setCellValue('U' . $k . '', $value['feetotal_l'][1])
                ->setCellValue('V' . $k . '', $value['mtotal2'])
                ->setCellValue('W' . $k . '', $value['feetotal2'])
                ->setCellValue('X' . $k . '', $value['mtotal3'])
                ->setCellValue('Y' . $k . '', $value['feetotal3']);
            }
        }

// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('统计报表 '.date('Y-m-d'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="统计报表'.date('Y-m-d').'.xlsx"');
    header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}


//客户导入模板
function user_temp()
{
    global $_W, $_GPC;
    $weid = $_W['weid'];

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '部门')
        ->setCellValue('B1', '姓名')
        ->setCellValue('C1', '预产期/服务日期')
        ->setCellValue('D1', '级别')
        ->setCellValue('E1', '电话')
        ->setCellValue('F1', '地址')
        ->setCellValue('G1', '月嫂姓名')
        ->setCellValue('H1', '入户')
        ->setCellValue('I1', '出户')
        ->setCellValue('J1', '备注');


// Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('客户档案管理模板');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="客户档案管理模板.xlsx"');
    header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}


////客户档案导出
//function userinfo($data)
//{
//    global $_W, $_GPC;
//    $weid = $_W['weid'];
//    if(!isset($data)){
//        return false;
//    }
//
//    // Create new PHPExcel object
//    $objPHPExcel = new PHPExcel();
//
//    // Set document properties
//    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
//        ->setLastModifiedBy("Maarten Balliauw")
//        ->setTitle("Office 2007 XLSX Test Document")
//        ->setSubject("Office 2007 XLSX Test Document")
//        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
//        ->setKeywords("office 2007 openxml php")
//        ->setCategory("Test result file");
//
//    if(is_array($data)){
//        foreach ($data as $k=>$value) {
//            $k+=4;
//            if($value['feedback']=='1'){
//                $feedback='满意';
//            }else{
//                $feedback='投诉';
//            }
//            // Miscellaneous glyphs, UTF-8
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $k . '', $k-3)
//                ->setCellValue('B' . $k . '', $value['mtname'])
//                ->setCellValue('C' . $k . '', $value['clientname'])
//                ->setCellValue('D' . $k . '', $value['lname'])
//                ->setCellValue('E' . $k . '', $value['rsdate'].'~'.$value['redate'])
//                ->setCellValue('F' . $k . '', $value['workds']-$value['half'])
//                ->setCellValue('G' . $k . '', $feedback)
//                ->setCellValue('H' . $k . '', $value['basicsal'])
//                ->setCellValue('I' . $k . '', $value['rewardsal'])
//                ->setCellValue('J' . $k . '', $value['reward'])
//                ->setCellValue('K' . $k . '', $value['totalsal'])
//                ->setCellValue('L' . $k . '', '');
//        }
//    }
//
//// Rename worksheet
//    $objPHPExcel->getActiveSheet()->setTitle('月嫂工资表 '.date('Y-m-d'));
//
//// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//    $objPHPExcel->setActiveSheetIndex(0);
//
//// Redirect output to a client’s web browser (Excel2007)
//    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//    header('Content-Disposition: attachment;filename="月嫂工资表'.date('Y-m-d').'.xlsx"');
//    header('Cache-Control: max-age=0');
//// If you're serving to IE 9, then the following may be needed
//    header('Cache-Control: max-age=1');
//
//// If you're serving to IE over SSL, then the following may be needed
//    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
//    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//    header ('Pragma: public'); // HTTP/1.0
//
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//    $objWriter->save('php://output');
//    exit;
//}