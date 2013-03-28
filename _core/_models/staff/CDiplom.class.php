<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.01.13
 * Time: 8:25
 * To change this template use File | Settings | File Templates.
 */
class CDiplom extends CActiveModel {
    protected $_table = TABLE_DIPLOMS; // protected (защищенный) разрешает доступ наследуемым и родительским классам
    protected $_mark = null;
    protected $_student = null;
    protected $_person = null;
    protected $_previews = null;
    protected $_language = null;
    protected $_recomendationProtocol = null;
    protected $_confirmation = null;
    protected $_practPlace = null;
    protected $_reviewer = null;
    protected function relations() { // метод relations проверяет наличие связей с другими записями
        return array(
            "mark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_mark",
                "storageField" => "study_mark",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            ),
            "student" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_student",
                "storageField" => "student_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudent"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "previews" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_previews",
                "relationFunction" => "getDiplomPreviews"
            ),
            "language" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_language",
                "storageField" => "foreign_lang",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getLanguage"
            ),
            "recomendationProtocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_recomendationProtocol",
                "storageField" => "protocol_2aspir_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "confirmation" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_confirmation",
                "storageField" => "diplom_confirm",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiplomConfirmation"
            ),
            "practPlace" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_practPlace",
                "storageField" => "pract_place_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getPracticePlace"
            ),
            "reviewer" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_reviewer",
                "storageField" => "recenz_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
        );
    }
    public function attributeLabels() {
        return array(
            "diplom_confirm" => "РЈС‚РІРµСЂР¶РґРµРЅ",
            "dipl_name" => "РўРµРјР° РґРёРїР»РѕРјР°",
            "pract_place" => "РњРµСЃС‚Рѕ РїСЂР°РєС‚РёРєРё",
            "pract_place_id" => "РњРµСЃС‚Рѕ РїСЂР°РєС‚РёРєРё",
            "kadri_id" => "Р”РёРїР»РѕРјРЅС‹Р№ СЂСѓРєРѕРІРѕРґРёС‚РµР»СЊ",
            "student_id" => "РЎС‚СѓРґРµРЅС‚",
            "group_id" => "Р“СЂСѓРїРїР°",
            "diplom_preview" => "Р”Р°С‚Р° РїСЂРµРґР·Р°С‰РёС‚С‹",
            "date_act" => "Р”Р°С‚Р° Р·Р°С‰РёС‚С‹",
            "foreign_lang" => "Р�РЅРѕСЃС‚СЂР°РЅРЅС‹Р№ СЏР·С‹Рє",
            "protocol_2aspir_id" => "РџСЂРѕС‚РѕРєРѕР» СЂРµРєРѕРјРµРЅРґР°С†РёРё РІ Р°СЃРёРїСЂР°РЅС‚СѓСЂСѓ",
            "recenz_id" => "Р РµС†РµРЅР·РµРЅС‚",
            "study_mark" => "РћС†РµРЅРєР°",
            "gak_num" => "РќРѕРјРµСЂ Р“РђРљ",
            "comment" => "РљРѕРјРјРµРЅС‚Р°СЂРёР№",
            "diplom_number" => "РќРѕРјРµСЂ РґРёРїР»РѕРјР°",
            "diplom_regnum" => "Р РµРіРёСЃС‚СЂР°С†РёРѕРЅРЅС‹Р№ РЅРѕРјРµСЂ",
            "diplom_regdate" => "Р”Р°С‚Р° СЂРµС€РµРЅРёСЏ Р“РђРљ",
            "diplom_issuedate" => "Р”Р°С‚Р° РІС‹РґР°С‡Рё"
        );
    }

    /**
     * @return CArrayList|null
     */
    protected function getDiplomPreviews() {
        if (is_null($this->_previews)) {
            $this->_previews = new CArrayList();
            /**
             * Р•СЃР»Рё РµСЃС‚СЊ РєР»СЋС‡ РґРёРїР»РѕРјР°, С‚Рѕ РёС‰РµРј РїРѕ РЅРµРјСѓ,
             * РµСЃР»Рё РЅРµС‚Сѓ, С‚Рѕ РёС‰РµРј РїРѕ СЃС‚СѓРґРµРЅС‚Сѓ
             */
            foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEWS, "diplom_id = ".$this->getId())->getItems() as $item) {
                $preview = new CDiplomPreview($item);
                $this->_previews->add($preview->getId(), $preview);
            }
            if ($this->_previews->getCount() == 0) {
                if (!is_null($this->_student)) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEWS, "student_id = ".$this->student->getId())->getItems() as $item) {
                        $preview = new CDiplomPreview($item);
                        $this->_previews->add($preview->getId(), $preview);
                    }
                }
            }
        }
        return $this->_previews;
    }

    /**
     * Р”Р°С‚Р° РїРѕСЃР»РµРґРЅРµРіРѕ РїСЂРµРґРїСЂРѕСЃРјРѕС‚СЂР° РґРёРїР»РѕРјР° РІ С„РѕСЂРјР°С‚Рµ unix timestamp
     *
     * @return int
     */
    public function getLastPreviewDate() {
        $last = 0;
        foreach ($this->previews->getItems() as $preview) {
            if (strtotime($preview->date_preview) > $last) {
                $last = strtotime($preview->date_preview);
            }
        }
        return $last;
    }
}
