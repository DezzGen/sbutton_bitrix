<?php

   // используем namespace
   use Bitrix\Main\Localization\Loc;
   use Bitrix\Main\Config\Option;
   
   // Надо задавать переменную с id модуля именно через переменную $module_id. 
   // Объясняется это тем, что старое ядро Битрикса отвалиться, если делать по другому
   $module_id = 'apecoder.sbutton';

   // подключаем языковые файлы
   Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."modules/main/options.php");
   Loc::loadMessages(_FILE_);

   // подлкючаем собственно сам модуль
   \Bitrix\Main\Loader::includeModule($module_id);

   // Объект для получения данных из формы, которые буду заполнены и отправлены пользователем.
   // Почему просто не пользоваться $_POST или $_GET ? - потому что создатели битрикс считают, 
   // что это плохая практика и напрягают всех пользоваться этим вот классом, что описан ниже:
   $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

   // Нужнореализовать специальны массив $aTabs, который опишет все наши вкладки, 
   // параметры этих вкладок и все параметры настроек модуля по этим вкладкам.
   // Через переменную $aTabs задаются вкладки и их содержимое на странице настроек.
   // Количество массивов в массиве $aTabs это количество вкладок на странице настроек.
   // Порядок отображения вкладок зависит от того порядка в котором массивы заданы тут

   $aTabs = array(
      array(
         'DIV' => 'edit1',
         // TAB - имя ячейки, которая написана на вкладке
         'TAB' => Loc::getMessage('SBUTTON_TAB_SETTINGS'),
         // OPTIONS - массив опций настроек модуля, которые имеются на данной вкладке:
         // - Первый элемент - ключ опции, используется как имя поля у формы. 
         // С таким ключом она будет размещать сохранённые значения в БД опции ;
         // - Второй элемент - это имя поля на обычном языке. Используется языковая константа;
         // - Третье поле - значение имя поля по умолчанию (здесь не указано ни одно);
         // - Четвёртое поле - это вложенный массив описывающий поле формы.
         // Первое поле указывает на то какое будет поле, все остальные описывают параметры поля
         'OPTIONS' => array(
            array(
               'field_text', 
               Loc::getMessage('SBUTTON_FIELD_TEXT_TITLE'),
               '',
               array('textarea', 10, 50)
            ),
            array(
               'field_line',
               Loc::getMessage('SBUTTON_FIELD_LINE_TITLE'),
               '',
               array('text', 10)
            ),
            array(
               'field_list',
               Loc::getMessage('SBUTTON_FIELD_LIST_TITLE'),
               '',
               array(
                  'multiselectbox', 
                  array(
                     'var1'=>'var1',
                     'var2'=>'var2',
                     'var3'=>'var3',
                     'var4'=>'var4'
                  )
               )
            )
         )
      ),
      array(
         'DIV' => 'edit2',
         // TAB - имя ячейки, которая написана на вкладке
         'TAB' => Loc::getMessage('MAIN_TAB_RIGHTS'),
         // TITLE - всплывающая подсказка на вкладке
         'TITLE' => Loc::getMessage('MAIN_TAB_TITLE_RIGHTS')
      )
   );


   // СОХРАНЕНИЕ
   if( $request->isPost() && $request['Update'] && check_bitrix_sessid() ){
      foreach($aTabs as $aTab){
         // Или можно использовать __AdmSettingsSaveOptions($MODULE_ID, $arOptions);
         foreach($aTab['OPTIONS'] as $arOption){
            // Строка с подсветкой. Используется для разделения настроек в одной вкладке
            if(!is_array($arOption)){
               continue;
            }
            // Уведомление с подсветкой
            if($arOption['note']){
               continue;
            }

            // Или __AdmSettingsSaveOptions($MODULE_ID, $arOptions);
            $optionName = $arOption[0];

            $optionValue = $request->getPost($optionName);

            Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue):$optionValue);
         }
      }
   }
      


   // ВИЗУАЛЬНЫЙ ВЫВОД
   // первый параметр это id формы со всеми вкладками
   $tabControl = new CAdminTabControl('tabControl', $aTabs);
   
   // открываем форму
   $tabControl->Begin();
   ?>

   <form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($request['mid'])?>&amp;lang=<?=$request['lang']?>" name="apecoder_sbutton_setting">

   <?
      // в цикле проходим по массиву $aTabs 
      foreach($aTabs as $aTab){
         if($aTab['OPTIONS']){
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
         }
      }
      // так появляется последняя вкладка с настройками прав доступа
      $tabControl->BeginNextTab();

      $tabControl->Buttons();

   ?>

      <input type="submit" name="Update" value="<?echo GetMessage('MAIN_SAVE')?>">
      <input type="reset" name="reset" value="<?echo GetMessage('MAIN_RESET')?>">
      
      <?=bitrix_sessid_post();?>
   </form>
   <? 
   // закрываем форму
   $tabControl->End(); 